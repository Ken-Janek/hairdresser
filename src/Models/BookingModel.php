<?php

declare(strict_types=1);

class BookingModel
{
    public static function listBookingsByDate(string $date, int $stylistId): array
    {
        $pdo = Db::get();
        $stmt = $pdo->prepare(
            'SELECT start_time, end_time FROM bookings WHERE date = ? AND stylist_id = ?'
        );
        $stmt->execute([$date, $stylistId]);
        return $stmt->fetchAll();
    }

    public static function listAdminBookings(string $date): array
    {
        $pdo = Db::get();
        $stmt = $pdo->prepare(
            'SELECT b.id, b.customer_name, b.email, b.phone, b.date, b.start_time, b.end_time,
                    s.name AS service_name, st.name AS stylist_name
             FROM bookings b
             JOIN services s ON s.id = b.service_id
             JOIN stylists st ON st.id = b.stylist_id
             WHERE b.date = ?
             ORDER BY b.start_time'
        );
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public static function createBooking(array $data): array
    {
        $pdo = Db::get();

        $service = ServiceModel::find((int)$data['service_id']);
        if ($service === null) {
            throw new RuntimeException('Service not found.');
        }

        $stylist = StylistModel::find((int)$data['stylist_id']);
        if ($stylist === null) {
            throw new RuntimeException('Stylist not found.');
        }

        $startTime = $data['time'];
        $endTime = AvailabilityService::addMinutes($startTime, (int)$service['duration_minutes']);

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'SELECT start_time, end_time FROM bookings
                 WHERE stylist_id = ? AND date = ?
                 FOR UPDATE'
            );
            $stmt->execute([(int)$data['stylist_id'], $data['date']]);
            $existing = $stmt->fetchAll();

            foreach ($existing as $row) {
                if (AvailabilityService::overlaps($startTime, $endTime, $row['start_time'], $row['end_time'])) {
                    $pdo->rollBack();
                    throw new RuntimeException('That time is already booked.');
                }
            }

            $insert = $pdo->prepare(
                'INSERT INTO bookings
                    (customer_name, email, phone, service_id, stylist_id, date, start_time, end_time)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $insert->execute([
                $data['customer_name'],
                $data['email'],
                $data['phone'],
                (int)$data['service_id'],
                (int)$data['stylist_id'],
                $data['date'],
                $startTime,
                $endTime,
            ]);

            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $error;
        }

        return [
            'id' => (int)$pdo->lastInsertId(),
            'service_name' => $service['name'],
            'stylist_name' => $stylist['name'],
            'date' => $data['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];
    }

    public static function cancelBooking(int $id): void
    {
        $pdo = Db::get();
        $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = ?');
        $stmt->execute([$id]);
    }
}
