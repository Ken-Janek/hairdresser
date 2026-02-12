const pool = require("../db");

function addMinutes(timeString, minutesToAdd) {
  const [hours, minutes] = timeString.split(":").map(Number);
  const totalMinutes = hours * 60 + minutes + minutesToAdd;
  const endHours = Math.floor(totalMinutes / 60);
  const endMinutes = totalMinutes % 60;
  return `${String(endHours).padStart(2, "0")}:${String(endMinutes).padStart(2, "0")}:00`;
}

async function listServices() {
  const [rows] = await pool.query(
    "SELECT id, name, price_cents, duration_minutes FROM services ORDER BY id"
  );
  return rows;
}

async function listBookings() {
  const [rows] = await pool.query(
    `SELECT b.id, b.customer_name, b.email, b.phone, b.date, b.start_time, b.end_time,
            s.name AS service_name
     FROM bookings b
     JOIN services s ON s.id = b.service_id
     ORDER BY b.date, b.start_time`
  );
  return rows;
}

async function listBookingsByDate(date) {
  const [rows] = await pool.query(
    "SELECT start_time, end_time FROM bookings WHERE date = ?",
    [date]
  );
  return rows;
}

async function createBooking({
  customerName,
  email,
  phone,
  serviceId,
  date,
  startTime
}) {
  const [services] = await pool.query(
    "SELECT id, name, duration_minutes FROM services WHERE id = ?",
    [serviceId]
  );

  if (!services.length) {
    throw new Error("Service not found.");
  }

  const service = services[0];
  const endTime = addMinutes(startTime, service.duration_minutes);

  const [conflicts] = await pool.query(
    `SELECT id FROM bookings
     WHERE date = ? AND NOT (end_time <= ? OR start_time >= ?)
     LIMIT 1`,
    [date, startTime, endTime]
  );

  if (conflicts.length) {
    throw new Error("That time is already booked.");
  }

  const [result] = await pool.query(
    `INSERT INTO bookings
      (customer_name, email, phone, service_id, date, start_time, end_time)
     VALUES (?, ?, ?, ?, ?, ?, ?)`,
    [customerName, email, phone, service.id, date, startTime, endTime]
  );

  return {
    id: result.insertId,
    serviceName: service.name,
    date,
    startTime,
    endTime
  };
}

async function cancelBooking(id) {
  await pool.query("DELETE FROM bookings WHERE id = ?", [id]);
}

module.exports = {
  listServices,
  listBookings,
  listBookingsByDate,
  createBooking,
  cancelBooking
};
