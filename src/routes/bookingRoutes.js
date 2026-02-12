const express = require("express");
const {
  listServices,
  listBookingsByDate,
  createBooking
} = require("../services/bookings");
const {
  getSlotsForDate,
  filterSlotsByBookings,
  getNextOpenDate
} = require("../services/availability");

const router = express.Router();

router.get("/", async (req, res, next) => {
  try {
    const services = await listServices();
    const date = req.query.date || getNextOpenDate();
    const serviceId = Number(req.query.service_id || services[0]?.id || 0);
    const selectedService = services.find((service) => service.id === serviceId);

    let slots = [];
    if (selectedService) {
      const bookings = await listBookingsByDate(date);
      slots = filterSlotsByBookings(
        getSlotsForDate(date, selectedService.duration_minutes),
        bookings,
        selectedService.duration_minutes
      );
    }

    res.render("booking", {
      services,
      date,
      serviceId,
      slots,
      error: null,
      success: null
    });
  } catch (error) {
    next(error);
  }
});

router.post("/book", async (req, res, next) => {
  try {
    const services = await listServices();
    const {
      customer_name: customerName,
      email,
      phone,
      service_id: serviceId,
      date,
      time
    } = req.body;

    if (!customerName || !email || !serviceId || !date || !time) {
      const bookings = await listBookingsByDate(date);
      const selectedService = services.find(
        (service) => service.id === Number(serviceId)
      );
      const slots = selectedService
        ? filterSlotsByBookings(
            getSlotsForDate(date, selectedService.duration_minutes),
            bookings,
            selectedService.duration_minutes
          )
        : [];

      return res.render("booking", {
        services,
        date,
        serviceId: Number(serviceId),
        slots,
        error: "Please fill in all required fields.",
        success: null
      });
    }

    const booking = await createBooking({
      customerName,
      email,
      phone,
      serviceId: Number(serviceId),
      date,
      startTime: time
    });

    return res.render("confirmation", {
      booking,
      customerName,
      email,
      phone
    });
  } catch (error) {
    if (error.message === "That time is already booked.") {
      try {
        const services = await listServices();
        const { date, service_id: serviceId } = req.body;
        const selectedService = services.find(
          (service) => service.id === Number(serviceId)
        );
        const bookings = await listBookingsByDate(date);
        const slots = selectedService
          ? filterSlotsByBookings(
              getSlotsForDate(date, selectedService.duration_minutes),
              bookings,
              selectedService.duration_minutes
            )
          : [];

        return res.render("booking", {
          services,
          date,
          serviceId: Number(serviceId),
          slots,
          error: error.message,
          success: null
        });
      } catch (renderError) {
        return next(renderError);
      }
    }

    return next(error);
  }
});

module.exports = router;
