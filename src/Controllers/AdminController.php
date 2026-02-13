<?php

declare(strict_types=1);

class AdminController
{
    public function index(): void
    {
        $this->requireAuth();

        $date = $_GET['date'] ?? date('Y-m-d');
        if (!AvailabilityService::isValidDate($date)) {
            $date = date('Y-m-d');
        }

        $bookings = BookingModel::listAdminBookings($date);

        View::render('admin', [
            'title' => 'Admin Bookings',
            'date' => $date,
            'bookings' => $bookings,
        ]);
    }

    public function cancel(): void
    {
        $this->requireAuth();

        $id = (int)($_POST['id'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');

        if ($id > 0) {
            BookingModel::cancelBooking($id);
        }

        header('Location: /admin?date=' . urlencode($date));
        exit;
    }

    private function requireAuth(): void
    {
        $user = Config::get('ADMIN_USER', 'admin');
        $pass = Config::get('ADMIN_PASS', 'admin123');
        Auth::requireBasic($user, $pass);
    }
}
