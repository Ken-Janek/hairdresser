<?php

declare(strict_types=1);

class AvailabilityService
{
    private const OPENING_HOURS = [
        1 => ['open' => '09:00', 'close' => '17:00'],
        2 => ['open' => '09:00', 'close' => '17:00'],
        3 => ['open' => '09:00', 'close' => '17:00'],
        4 => ['open' => '09:00', 'close' => '17:00'],
        5 => ['open' => '09:00', 'close' => '17:00'],
    ];

    private const SLOT_INCREMENT_MINUTES = 30;

    public static function getSlotsForDate(string $dateString, int $durationMinutes): array
    {
        $hours = self::getOpeningHours($dateString);
        if ($hours === null) {
            return [];
        }

        $openMinutes = self::parseTimeToMinutes($hours['open']);
        $closeMinutes = self::parseTimeToMinutes($hours['close']);
        $lastStart = $closeMinutes - $durationMinutes;

        $slots = [];
        for ($current = $openMinutes; $current <= $lastStart; $current += self::SLOT_INCREMENT_MINUTES) {
            $slots[] = self::minutesToTime($current);
        }

        return $slots;
    }

    public static function filterSlotsByBookings(array $slots, array $bookings, int $durationMinutes): array
    {
        if (count($bookings) === 0) {
            return $slots;
        }

        $ranges = [];
        foreach ($bookings as $booking) {
            $ranges[] = [
                'start' => self::parseTimeToMinutes(substr($booking['start_time'], 0, 5)),
                'end' => self::parseTimeToMinutes(substr($booking['end_time'], 0, 5)),
            ];
        }

        $available = [];
        foreach ($slots as $slot) {
            $slotStart = self::parseTimeToMinutes($slot);
            $slotEnd = $slotStart + $durationMinutes;

            $overlap = false;
            foreach ($ranges as $range) {
                if ($slotStart < $range['end'] && $slotEnd > $range['start']) {
                    $overlap = true;
                    break;
                }
            }

            if (!$overlap) {
                $available[] = $slot;
            }
        }

        return $available;
    }

    public static function nextOpenDate(?DateTimeImmutable $base = null): string
    {
        $date = $base ?? new DateTimeImmutable('today');
        for ($i = 0; $i < 14; $i += 1) {
            $dateString = $date->format('Y-m-d');
            if (self::getOpeningHours($dateString) !== null) {
                return $dateString;
            }
            $date = $date->modify('+1 day');
        }

        return $date->format('Y-m-d');
    }

    public static function isValidDate(string $date): bool
    {
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }

    public static function addMinutes(string $timeString, int $minutesToAdd): string
    {
        $totalMinutes = self::parseTimeToMinutes($timeString) + $minutesToAdd;
        $hours = (int)floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    public static function overlaps(string $startA, string $endA, string $startB, string $endB): bool
    {
        $aStart = self::parseTimeToMinutes(substr($startA, 0, 5));
        $aEnd = self::parseTimeToMinutes(substr($endA, 0, 5));
        $bStart = self::parseTimeToMinutes(substr($startB, 0, 5));
        $bEnd = self::parseTimeToMinutes(substr($endB, 0, 5));

        return $aStart < $bEnd && $aEnd > $bStart;
    }

    private static function getOpeningHours(string $dateString): ?array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $dateString);
        if ($date === false) {
            return null;
        }

        $weekday = (int)$date->format('N');
        return self::OPENING_HOURS[$weekday] ?? null;
    }

    private static function parseTimeToMinutes(string $timeString): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $timeString));
        return $hours * 60 + $minutes;
    }

    private static function minutesToTime(int $minutes): string
    {
        $hours = (int)floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }
}
