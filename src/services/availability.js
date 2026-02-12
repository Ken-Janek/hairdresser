const OPENING_HOURS = {
  1: { open: "09:00", close: "17:00" },
  2: { open: "09:00", close: "17:00" },
  3: { open: "09:00", close: "17:00" },
  4: { open: "09:00", close: "17:00" },
  5: { open: "09:00", close: "17:00" }
};

const SLOT_INCREMENT_MINUTES = 30;

function parseTimeToMinutes(timeString) {
  const [hours, minutes] = timeString.split(":").map(Number);
  return hours * 60 + minutes;
}

function minutesToTime(minutes) {
  const hours = Math.floor(minutes / 60);
  const mins = minutes % 60;
  return `${String(hours).padStart(2, "0")}:${String(mins).padStart(2, "0")}`;
}

function getOpeningHours(dateString) {
  const date = new Date(`${dateString}T00:00:00`);
  return OPENING_HOURS[date.getDay()] || null;
}

function getSlotsForDate(dateString, durationMinutes = SLOT_INCREMENT_MINUTES) {
  const hours = getOpeningHours(dateString);
  if (!hours) {
    return [];
  }

  const openMinutes = parseTimeToMinutes(hours.open);
  const closeMinutes = parseTimeToMinutes(hours.close);
  const lastStart = closeMinutes - durationMinutes;

  const slots = [];
  for (let current = openMinutes; current <= lastStart; current += SLOT_INCREMENT_MINUTES) {
    slots.push(minutesToTime(current));
  }

  return slots;
}

function filterSlotsByBookings(slots, bookings, durationMinutes) {
  if (!bookings.length) {
    return slots;
  }

  const bookingRanges = bookings.map((booking) => {
    const start = parseTimeToMinutes(booking.start_time.slice(0, 5));
    const end = parseTimeToMinutes(booking.end_time.slice(0, 5));
    return { start, end };
  });

  return slots.filter((slot) => {
    const slotStart = parseTimeToMinutes(slot);
    const slotEnd = slotStart + durationMinutes;

    return !bookingRanges.some((range) => slotStart < range.end && slotEnd > range.start);
  });
}

function getNextOpenDate(baseDate = new Date()) {
  const next = new Date(baseDate);
  for (let i = 0; i < 14; i += 1) {
    const dateString = next.toISOString().slice(0, 10);
    if (getOpeningHours(dateString)) {
      return dateString;
    }
    next.setDate(next.getDate() + 1);
  }

  return baseDate.toISOString().slice(0, 10);
}

module.exports = {
  getSlotsForDate,
  filterSlotsByBookings,
  getOpeningHours,
  getNextOpenDate
};
