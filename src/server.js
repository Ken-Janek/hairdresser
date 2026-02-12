const path = require("path");
const express = require("express");
require("dotenv").config();

const bookingRoutes = require("./routes/bookingRoutes");
const adminRoutes = require("./routes/adminRoutes");

const app = express();
const port = process.env.PORT || 3000;

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));

app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, "public")));

app.use("/", bookingRoutes);
app.use("/admin", adminRoutes);

app.use((req, res) => {
  res.status(404).render("notFound");
});

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).render("error", { message: "Something went wrong." });
});

app.listen(port, () => {
  console.log(`Server listening on http://localhost:${port}`);
});
