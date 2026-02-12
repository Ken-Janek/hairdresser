const express = require("express");
const { listBookings, cancelBooking } = require("../services/bookings");

const router = express.Router();

router.get("/", async (req, res, next) => {
  try {
    const bookings = await listBookings();
    res.render("admin", { bookings });
  } catch (error) {
    next(error);
  }
});

router.post("/cancel/:id", async (req, res, next) => {
  try {
    await cancelBooking(req.params.id);
    res.redirect("/admin");
  } catch (error) {
    next(error);
  }
});

module.exports = router;
