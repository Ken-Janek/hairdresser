# Hairdresser Booking (PHP MVC)

Minimal booking system with public booking and protected admin view. Built with PHP using a simple MVC structure (controllers, models, views).

## Setup (Local)

1. Create the database and tables:

```sql
CREATE DATABASE hairdresser_booking;
USE hairdresser_booking;
SOURCE db/schema.sql;
```

If you previously used an older schema, recreate the database so `stylist_id` and `stylists` exist.

2. Create a `.env` file based on `.env.example` and set DB + admin credentials.

3. Run the PHP built-in server:

```bash
php -S localhost:3000 -t public
```

Visit http://localhost:3000 for booking and http://localhost:3000/admin for admin.

## Notes

- Fixed hours: Monday to Friday, 09:00-17:00.
- Two sample stylists are seeded in the database.
- Overlapping bookings are blocked in the application and at the database level for same stylist and start time.

## MVC Structure

- Controllers: request handling (e.g. [src/Controllers](src/Controllers))
- Models: data access (e.g. [src/Models](src/Models))
- Views: user-facing templates (e.g. [src/Views](src/Views))

## Client-Server Overview

User actions in the browser (client) send HTTP requests to the PHP server (server). The router maps the request to a controller, the controller calls models to read/write data, and then the server renders a view (HTML) which is returned as the response.

## Security Overview

Relevant risks and mitigations:

- SQL injection: all DB access uses prepared statements in models.
- XSS: output is escaped in views via the shared escape helper.
- Unauthorized admin access: admin routes require HTTP Basic Auth using credentials stored in `.env`.
- Invalid input: server-side validation for required fields, date format, and email.

## Code Standard

Code follows a consistent PHP style inspired by PSR-12: 4-space indentation, class-per-file, explicit visibility, and consistent naming.

## Deployment (fill in for submission)

- Public URL: TODO
- Hosting environment: TODO
