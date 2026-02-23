# Hairdresser Booking System - Technical Documentation

## 1. Client-Server Architecture

The application follows the traditional client-server model:

**Client (Frontend):** Users interact with HTML forms in their web browser to browse stylists, select services, choose time slots, and submit bookings. The browser sends HTTP requests to the server.

**Server (Backend):** A PHP application running on Apache processes incoming requests, executes business logic (validating bookings, checking availability), queries the MySQL database, and returns HTML responses to the client.

**Data Flow:**
1. User enters data in browser (form submission)
2. Browser sends HTTP POST/GET request to server
3. Server processes request through the router
4. Controllers execute business logic and model queries
5. Views render the response as HTML
6. Server sends response back to browser
7. Browser displays the result to the user

## 2. Framework & Architecture

**Framework Used:** PHP with MVC (Model-View-Controller) pattern

**Why MVC?**
- **Separation of Concerns:** Each component has a single responsibility
- **Reusability:** Models and views can be reused across multiple controllers
- **Testability:** Each layer can be tested independently
- **Maintainability:** Changes in one layer don't affect others
- **Scalability:** Easy to add new features without breaking existing code

**Components:**

- **Models** (`src/Models/`): Handle database operations via prepared statements
  - `BookingModel`: CRUD operations for bookings
  - `ServiceModel`: Fetch available services
  - `StylistModel`: Fetch available stylists

- **Controllers** (`src/Controllers/`): Process requests and execute business logic
  - `BookingController`: Handle booking creation, availability checks
  - `AdminController`: Manage admin views and booking cancellations

- **Views** (`src/Views/`): Render HTML responses
  - `booking.php`: Main booking form
  - `admin.php`: Admin booking management
  - `confirmation.php`: Booking confirmation
  - Partials: Header, footer (reusable components)

- **Core Services** (`src/Core/`): Shared utilities
  - `Router`: URL routing and request dispatching
  - `Db`: Singleton database connection
  - `Config`: Environment variable management
  - `View`: Template rendering helper
  - `Auth`: HTTP Basic Authentication for admin

- **Business Logic** (`src/Services/`):
  - `AvailabilityService`: Calculate free time slots, prevent double bookings

## 3. Security Measures

### Identified Security Risks & Mitigation

| Risk | Description | Mitigation |
|------|-------------|-----------|
| **SQL Injection** | Attacker could manipulate database queries through input | ✅ All database queries use prepared statements with parameterized inputs (PDO) |
| **Cross-Site Scripting (XSS)** | Malicious scripts injected through user input | ✅ Output is escaped using `htmlspecialchars()` in views |
| **Double Booking** | Multiple bookings for the same stylist at same time | ✅ Database UNIQUE constraint on (stylist_id, date, start_time); application-level checks in AvailabilityService |
| **Unauthorized Admin Access** | Anyone could access admin panel | ✅ HTTP Basic Authentication with .env credentials; admin routes checked before rendering |
| **Invalid Input** | Invalid data saved to database | ✅ Server-side validation: email format, required fields, date/time bounds |
| **Session Hijacking** | Limited (stateless), but HTTP Basic Auth is vulnerable | ✅ HTTPS enforced in production (Railway provides SSL) |
| **Information Disclosure** | Error messages reveal system details | ✅ Generic error messages displayed; detailed logs only server-side |

### Implementation Details

```php
// Prepared statements (SQL Injection prevention)
$stmt = $db->prepare('SELECT * FROM stylists WHERE id = ?');
$stmt->execute([$stylist_id]);

// Output escaping (XSS prevention)
<h1><?= htmlspecialchars($booking['customer_name']) ?></h1>

// Double booking prevention (database level)
CREATE UNIQUE INDEX uniq_booking_slot ON bookings (stylist_id, date, start_time);

// Admin authentication (Access control)
if (!Auth::check($admin_user, $admin_pass)) {
    http_response_code(401);
    exit;
}
```

## 4. Code Standards

**Standard Used:** PSR-12 (PHP Standard Recommendations)

**Principles Applied:**

1. **Indentation:** 4 spaces (no tabs)
   ```php
   class BookingController {
       public function index() {
           // code indented 4 spaces
       }
   }
   ```

2. **Naming Conventions:**
   - Classes: PascalCase (`BookingController`, `AvailabilityService`)
   - Methods/Functions: camelCase (`getAvailableSlots`, `createBooking`)
   - Constants: UPPER_SNAKE_CASE (`DB_HOST`, `ADMIN_USER`)
   - Variables: $camelCase (`$stylistId`, `$bookingData`)

3. **File Organization:**
   - One class per file
   - File name matches class name
   - Logical directory structure (Controllers, Models, Views, Services)

4. **Code Clarity:**
   - Meaningful variable names (`$availableSlots` not `$as`)
   - Short methods with single responsibility
   - Comments for complex logic
   - Consistent spacing and formatting

5. **Reusable Components:**
   ```php
   // Shared database connection
   $db = Db::get();
   
   // Shared configuration
   $host = Config::get('DB_HOST');
   
   // Shared routing
   $router->get('/', [BookingController::class, 'index']);
   
   // Shared view rendering
   View::render('booking', $data);
   ```

6. **Error Handling:**
   - Exceptions caught and logged
   - User-friendly error messages
   - HTTP status codes properly set

## 5. Key Features Implemented

✅ **Booking System:**
- Users select stylist, date, service, and available time slot
- Contact information collected (name, email, phone)
- Real-time availability calculation

✅ **Double Booking Prevention:**
- Database level: UNIQUE constraint
- Application level: AvailabilityService checks
- Atomic operations ensure consistency

✅ **Admin Management:**
- View all bookings filtered by date
- Cancel bookings with one click
- Protected with HTTP Basic Auth

✅ **Responsive Design:**
- Clean, modern UI
- Consistent header/footer across pages
- Mobile-friendly forms

## 6. Deployment

**Production Environment:** Railway (railway.app)
**Custom Domain:** hairydresser.site (DNS configured)
**Web Server:** Apache 2.4 with PHP 8.3
**Database:** MySQL 5.7+
**SSL/TLS:** Automatic via Railway + Let's Encrypt

**Configuration Files:**
- `Dockerfile`: Container configuration
- `.htaccess`: URL rewriting and security headers
- `DEPLOYMENT.md`: Complete deployment guide
- `.env.production`: Production environment variables

## Conclusion

This application demonstrates:
- ✅ Proper separation of concerns (MVC pattern)
- ✅ Security best practices (prepared statements, input validation, authentication)
- ✅ Code quality and consistency (PSR-12 standard)
- ✅ Scalable architecture with reusable components
- ✅ Real-world deployment capabilities (Dockerized, production-ready)
