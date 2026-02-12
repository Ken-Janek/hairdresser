CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price_cents INT NOT NULL,
  duration_minutes INT NOT NULL
);

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(160) NOT NULL,
  email VARCHAR(160) NOT NULL,
  phone VARCHAR(40),
  service_id INT NOT NULL,
  date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_service FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE UNIQUE INDEX uniq_booking_slot ON bookings (date, start_time);

INSERT INTO services (name, price_cents, duration_minutes) VALUES
  ("Classic Cut", 3500, 45),
  ("Wash & Style", 2500, 30),
  ("Color Refresh", 6500, 90)
ON DUPLICATE KEY UPDATE name = name;
