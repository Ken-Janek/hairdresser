# Hairdresser Booking (Minimal)

Minimal booking system with a customer booking form and a simple admin view.

## Setup

1. Create the database and tables:

```sql
CREATE DATABASE hairdresser_booking;
USE hairdresser_booking;
SOURCE db/schema.sql;
```

2. Create a `.env` file based on `.env.example`.

3. Install dependencies:

```bash
npm install
```

4. Run the server:

```bash
npm run dev
```

Visit http://localhost:3000 for booking and http://localhost:3000/admin for admin.

## Notes

- Fixed hours: Monday to Friday, 09:00-17:00.
- Single stylist.
- Overlapping bookings are blocked by the app and database.
