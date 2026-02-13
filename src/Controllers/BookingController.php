<?php

declare(strict_types=1);

class BookingController
{
    public function index(): void
    {
        $stylists = StylistModel::all();
        $services = ServiceModel::all();

        $date = $_GET['date'] ?? AvailabilityService::nextOpenDate();
        $stylistId = (int)($_GET['stylist_id'] ?? ($stylists[0]['id'] ?? 0));
        $serviceId = (int)($_GET['service_id'] ?? ($services[0]['id'] ?? 0));

        $selectedService = null;
        foreach ($services as $service) {
            if ((int)$service['id'] === $serviceId) {
                $selectedService = $service;
                break;
            }
        }

        $slots = [];
        if ($selectedService !== null && $stylistId > 0) {
            $bookings = BookingModel::listBookingsByDate($date, $stylistId);
            $slots = AvailabilityService::filterSlotsByBookings(
                AvailabilityService::getSlotsForDate($date, (int)$selectedService['duration_minutes']),
                $bookings,
                (int)$selectedService['duration_minutes']
            );
        }

        View::render('booking', [
            'title' => 'Book an Appointment',
            'stylists' => $stylists,
            'services' => $services,
            'date' => $date,
            'stylistId' => $stylistId,
            'serviceId' => $serviceId,
            'slots' => $slots,
            'error' => null,
        ]);
    }

    public function store(): void
    {
        $stylists = StylistModel::all();
        $services = ServiceModel::all();

        $customerName = trim($_POST['customer_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $stylistId = (int)($_POST['stylist_id'] ?? 0);
        $serviceId = (int)($_POST['service_id'] ?? 0);

        $error = $this->validateInput($customerName, $email, $date, $time, $stylistId, $serviceId);
        if ($error !== null) {
            $this->renderFormWithError($error, $stylists, $services, $date, $stylistId, $serviceId);
            return;
        }

        try {
            $booking = BookingModel::createBooking([
                'customer_name' => $customerName,
                'email' => $email,
                'phone' => $phone,
                'date' => $date,
                'time' => $time,
                'stylist_id' => $stylistId,
                'service_id' => $serviceId,
            ]);

            View::render('confirmation', [
                'title' => 'Booking Confirmed',
                'booking' => $booking,
            ]);
        } catch (RuntimeException $error) {
            $this->renderFormWithError($error->getMessage(), $stylists, $services, $date, $stylistId, $serviceId);
        }
    }

    private function validateInput(
        string $customerName,
        string $email,
        string $date,
        string $time,
        int $stylistId,
        int $serviceId
    ): ?string {
        if ($customerName === '' || $email === '' || $date === '' || $time === '') {
            return 'Please fill in all required fields.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }

        if (!AvailabilityService::isValidDate($date)) {
            return 'Please select a valid date.';
        }

        if ($stylistId <= 0 || $serviceId <= 0) {
            return 'Please choose a stylist and service.';
        }

        return null;
    }

    private function renderFormWithError(
        string $error,
        array $stylists,
        array $services,
        string $date,
        int $stylistId,
        int $serviceId
    ): void {
        $selectedService = null;
        foreach ($services as $service) {
            if ((int)$service['id'] === $serviceId) {
                $selectedService = $service;
                break;
            }
        }

        $slots = [];
        if ($selectedService !== null && $stylistId > 0) {
            $bookings = BookingModel::listBookingsByDate($date, $stylistId);
            $slots = AvailabilityService::filterSlotsByBookings(
                AvailabilityService::getSlotsForDate($date, (int)$selectedService['duration_minutes']),
                $bookings,
                (int)$selectedService['duration_minutes']
            );
        }

        View::render('booking', [
            'title' => 'Book an Appointment',
            'stylists' => $stylists,
            'services' => $services,
            'date' => $date,
            'stylistId' => $stylistId,
            'serviceId' => $serviceId,
            'slots' => $slots,
            'error' => $error,
        ]);
    }
}
