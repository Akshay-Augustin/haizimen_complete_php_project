# Haizimen – Project Explanation & Viva Q&A Guide

## 1. Project Overview

**Haizimen** is a **Child Healthcare Management System** built using **PHP (procedural + OOP), MySQL, HTML, CSS, and JavaScript**. It serves as a centralized platform connecting **parents, doctors, caretakers, and daycare centers** for managing child healthcare needs.

---

## 2. System Architecture

```
haizimen_complete_php_project/
├── index.php              ← Landing / home page
├── login.php              ← Login form + session creation
├── register.php           ← Dynamic multi-role registration
├── dashboard.php          ← Role-based user dashboard
├── admin_dashboard.php    ← Admin-only control panel
├── connect.php            ← DB connection entry point
│
├── app/
│   ├── Controllers/
│   │   └── AuthController.php   ← Handles login, register, logout logic
│   ├── Models/
│   │   └── User.php             ← DB operations for users, doctors, caretakers, daycares
│   └── Helpers/
│       └── helpers.php          ← Session helpers, flash messages, XSS escaping
│
├── includes/
│   └── connect.php              ← MySQLi connection (actual credentials)
│
├── database/
│   └── schema.sql               ← DB table definitions
│
└── public/uploads/certificates/ ← Uploaded birth certificates
```

**Architecture Pattern:** The project follows a **lightweight MVC-like structure** — PHP pages (Views) call Controllers, which interact with Models. There is no full framework, but the separation of concerns is manually maintained.

---

## 3. User Roles

| Role | What They Can Do |
|---|---|
| **Parent** | Book appointments, book vaccines, book caretakers, enroll in daycares, view histories |
| **Doctor** | View & manage incoming appointments and vaccine bookings |
| **Caretaker** | View caretaker requests from parents, manage availability |
| **Daycare** | View enrollment requests from parents, manage center details |
| **Admin** | Full control panel — view all users, manage vaccines, monitor all bookings/requests |

---

## 4. Complete Project Flow

### Step 1: Registration (`register.php`)
1. User selects a **role** (Parent / Doctor / Caretaker / Daycare).
2. JavaScript (`toggleRoleFields()`) **dynamically shows/hides** role-specific form fields.
3. On submit, `AuthController::registerUser()` is called:
   - **Validates** all inputs (checks email format, required fields, role-specific required fields).
   - Checks if **username/email already exists** (via `User::usernameExists()`, `User::emailExists()`).
   - Handles **certificate file upload** (allowed: jpg, jpeg, png, pdf; max 2MB).
   - **Hashes the password** using `password_hash($password, PASSWORD_DEFAULT)` (bcrypt).
   - Inserts into `users` table, then into the role-specific table (`doctors`, `caretakers`, or `daycares`).
   - Also inserts into the **legacy `login` table** for compatibility.
4. On success, redirects to `login.php`.

### Step 2: Login (`login.php`)
1. User submits username + password.
2. `AuthController::login()` is called:
   - Fetches user from DB by username (`User::findByUsername()`).
   - Verifies password with `password_verify()`.
   - Creates a **`$_SESSION['auth']`** array containing `id`, `role`, `name`, `username`, `email`.
3. Admin → redirected to `admin_dashboard.php`; others → `dashboard.php`.

### Step 3: Dashboard (`dashboard.php`)
- Calls `ensure_auth()` (from helpers) — if not logged in, redirects to login.
- Reads `$_SESSION['auth']['role']` and renders **role-specific cards** using PHP `if/elseif` blocks.
- Each role sees a different set of feature cards with navigation links.

### Step 4: Parent Features
| Feature | Page |
|---|---|
| View Vaccines | `vaccines.php` |
| Book Vaccine | `book_vaccine.php` |
| Vaccine History | `vaccine_history.php` |
| Vaccine Reminders | `vaccine_reminders.php` |
| Book Doctor Appointment | `book_appointment.php` |
| My Appointments | `my_appointments.php` |
| Browse Caretakers | `caretakers.php` |
| Book Caretaker | `book_caretaker.php` |
| Caretaker History | `caretaker_history.php` |
| Browse Daycares | `daycares.php` |
| Book Daycare | `book_daycare.php` |
| Daycare History | `daycare_history.php` |

### Step 5: Doctor Features
- `doctor_appointments.php` — View & update appointment status.
- `doctor_vaccine_bookings.php` — Manage vaccine bookings assigned to them.

### Step 6: Admin Panel (`admin_dashboard.php`)
- Runs **9 COUNT queries** to display system-wide statistics.
- Provides links to manage: Parents, Doctors, Caretakers, Daycares, Appointments, Vaccines, Vaccine Bookings, Caretaker Requests, Daycare Requests.
- Admin role is verified explicitly: `if ($user['role'] !== 'admin') { die('Access denied.'); }`.

### Step 7: Logout (`logout.php`)
- `unset($_SESSION['auth'])` destroys the authenticated session.

---

## 5. Database Schema

| Table | Key Columns |
|---|---|
| `users` | `id`, `role`, `first_name`, `last_name`, `email`, `username`, `password_hash` |
| `login` | `user_id` (FK → users), `Username`, `Password` (legacy table) |
| `doctors` | `user_id` (FK), `doctor_name`, `department`, `qualification`, `clinic_name` |
| `vaccines` | `vaccine_name`, `age_group`, `description`, `status` |
| `appointments` | `parent_user_id` (FK), `doctor_id` (FK), `appointment_date`, `appointment_time`, `status` |
| `vaccinations` | `user_id` (FK), `vaccine_id` (FK), `dose_number`, `scheduled_date` |
| `caretakers` | `user_id` (FK), `caretaker_name`, `experience_years`, `skills`, `fee` |
| `daycares` | `user_id` (FK), `center_name`, `capacity`, `opening_time`, `closing_time` |

---

---

# Viva Questions & Answers

## 🔵 Section A: Project Overview

**Q1. What is Haizimen? What problem does it solve?**
> Haizimen is a child healthcare management system that provides a single platform for parents to book doctor appointments, manage vaccinations, hire caretakers, and enroll children in daycare centers. It eliminates the need to contact each service separately, centralizing child healthcare management.

**Q2. What technology stack did you use?**
> - **Backend:** PHP (OOP with MVC-like structure)
> - **Database:** MySQL (via MySQLi extension)
> - **Frontend:** HTML, CSS, JavaScript (Vanilla)
> - **Server:** Apache (XAMPP/WAMP)
> - **Icons:** Font Awesome CDN

**Q3. How many user roles are there? Explain each.**
> Five roles: **Parent** (primary user who books services), **Doctor** (manages appointments and vaccinations), **Caretaker** (manages care requests), **Daycare** (manages enrollment requests), and **Admin** (full system oversight and management).

**Q4. What is the main purpose of the `dashboard.php` page?**
> It acts as a central hub after login. It reads the user's role from the session (`$_SESSION['auth']['role']`) and conditionally renders a role-specific set of cards with relevant navigation links using PHP `if/elseif` blocks.

---

## 🔵 Section B: Architecture & Code Design

**Q5. What architecture pattern does your project follow?**
> A lightweight **MVC (Model-View-Controller)** pattern:
> - **Model:** `app/Models/User.php` handles all database queries.
> - **Controller:** `app/Controllers/AuthController.php` handles business logic (validation, registration, login).
> - **View:** PHP page files like `login.php`, `register.php`, `dashboard.php` handle HTML rendering.

**Q6. Why did you separate the `AuthController` and `User` model?**
> To follow the **Single Responsibility Principle**. The `User` model only handles database operations (SELECT, INSERT), while the `AuthController` handles validation logic, file uploads, and deciding what to do with the data. This makes the code easier to maintain and test.

**Q7. What is the role of `connect.php` and `includes/connect.php`?**
> The root `connect.php` simply includes `includes/connect.php`. The actual database connection logic (host, username, password, database name) is in `includes/connect.php` using `mysqli_connect()`. This design allows any page to include `connect.php` cleanly.

**Q8. What is `helpers.php` and what helper functions does it provide?**
> It is a utility file providing:
> - `ensure_auth()` — Redirects to login if no session exists (authentication guard).
> - `flash_set()` / `flash_get()` — One-time flash messages stored in session (e.g., "Login successful").
> - `e()` — Escapes HTML output using `htmlspecialchars()` to prevent XSS.
> - `old()` — Retrieves previously submitted form values (for form re-population on validation failure).
> - `remember_old_input()` / `clear_old_input()` — Stores/clears form data in session.

---

## 🔵 Section C: Registration & Login

**Q9. How does the dynamic registration form work?**
> The form has sections hidden by CSS (`display: none`). A JavaScript function `toggleRoleFields()` listens to the role `<select>` dropdown's `change` event. When the user picks a role (e.g., Doctor), it shows doctor-specific fields (department, qualification, clinic name) and hides other role fields. It also dynamically sets `required` attributes on relevant inputs.

**Q10. How is the password stored securely?**
> Using PHP's built-in `password_hash($password, PASSWORD_DEFAULT)` which uses the **bcrypt algorithm**. This creates a salted, one-way hash. On login, `password_verify($inputPassword, $storedHash)` is used to check — the plain password is never stored.

**Q11. What validations are performed during registration?**
> - Email format (`filter_var($email, FILTER_VALIDATE_EMAIL)`)
> - Minimum password length (6 characters)
> - Duplicate username and email check (DB query)
> - Role-specific required fields (e.g., department for doctor, center_name for daycare)
> - File upload validation (extension check: jpg/png/pdf, size limit: 2MB)

**Q12. What happens after a successful registration?**
> The `createUser()` method inserts into the `users` table. Then, based on role, `createDoctorProfile()`, `createCaretakerProfile()`, or `createDaycareProfile()` inserts into the respective table. `createLegacyLogin()` also inserts into the `login` table. The user is then redirected to `login.php` with a flash success message.

**Q13. What is the purpose of the `login` table if `users` already stores credentials?**
> The `login` table is described as a **legacy table** — it was likely used in an earlier version of the system where authentication was done separately. It's kept for backward compatibility and stores a mirror of username and hashed password linked by `user_id`.

**Q14. How does login work step by step?**
> 1. `AuthController::login()` reads `$_POST['username']` and `$_POST['password']`.
> 2. Calls `User::findByUsername()` → runs a prepared statement to fetch the user row.
> 3. Calls `password_verify()` to compare input against stored hash.
> 4. On success, stores user data in `$_SESSION['auth']`.
> 5. Redirects admin to `admin_dashboard.php`, others to `dashboard.php`.

---

## 🔵 Section D: Security

**Q15. What is SQL Injection and how did you prevent it?**
> SQL Injection is an attack where malicious SQL code is inserted through form inputs to manipulate the database (e.g., `' OR '1'='1`). We prevented it using **Prepared Statements** with `mysqli::prepare()` and `bind_param()` throughout all database queries. The user input is never concatenated directly into a SQL string.

**Q16. What is XSS and how did you prevent it?**
> XSS (Cross-Site Scripting) is when an attacker injects malicious JavaScript into a page that gets executed in other users' browsers. We prevent it using the `e()` helper function, which calls `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` on every variable echoed to the HTML.

**Q17. How is authentication enforced on protected pages?**
> Every protected page calls `ensure_auth()` at the top. This function checks if `$_SESSION['auth']` is set. If not, it redirects the user to `login.php` preventing unauthorized access.

**Q18. How is admin access controlled?**
> In `admin_dashboard.php`, after `ensure_auth()` confirms the user is logged in, there is an explicit role check: `if ($user['role'] !== 'admin') { die('Access denied.'); }`. This ensures only admin-role users can access the admin panel.

**Q19. What file upload security measures are implemented?**
> - **Extension whitelist:** Only `jpg`, `jpeg`, `png`, `pdf` allowed.
> - **Size limit:** Maximum 2MB (`2 * 1024 * 1024` bytes).
> - **Rename on upload:** Files are renamed with `uniqid('cert_', true)` to prevent overwriting and path traversal.
> - **Stored outside webroot access:** Stored in `public/uploads/certificates/`.

---

## 🔵 Section E: Database Design

**Q20. What is a Foreign Key? Give an example from your project.**
> A Foreign Key is a column that links one table to another, enforcing referential integrity. Example: In the `appointments` table, `parent_user_id` is a FK referencing `users(id)`, and `doctor_id` references `doctors(id)`. This ensures appointments cannot exist for non-existent users.

**Q21. What does `ON DELETE CASCADE` mean? Where did you use it?**
> It means if a parent row is deleted, all child rows referencing it are also automatically deleted. Example: In `appointments`, `ON DELETE CASCADE` on `parent_user_id` means if a parent user is deleted, all their appointments are also deleted, preventing orphan records.

**Q22. What does `ON DELETE SET NULL` mean? Where did you use it?**
> It sets the foreign key column to NULL when the referenced row is deleted. Used in the `login` table: if a user is deleted, `login.user_id` is set to NULL rather than deleting the login record.

**Q23. Why is `password_hash` stored in `users` but also in `login`?**
> The `login` table is a legacy/compatibility table. The primary authentication now uses the `password_hash` column in `users`. The `login.Password` is a duplicate maintained for backward-compatibility reasons.

**Q24. Why is `AUTO_INCREMENT PRIMARY KEY` used with `INT UNSIGNED`?**
> `AUTO_INCREMENT` automatically generates unique sequential IDs for each new row. `INT UNSIGNED` allows only non-negative integers, effectively doubling the maximum value range (0 to ~4.3 billion) compared to signed INT, which is more than enough for user IDs.

**Q25. What is `utf8mb4` charset and why is it used?**
> `utf8mb4` is a 4-byte Unicode encoding that supports the full Unicode character set including emojis and special characters from all languages. Regular `utf8` in MySQL is limited to 3 bytes, which doesn't cover all Unicode code points. `utf8mb4` is the safe, recommended choice.

---

## 🔵 Section F: PHP Concepts

**Q26. What is the difference between `include` and `require_once`?**
> - `include` — Includes a file; if not found, shows a warning and continues execution.
> - `require_once` — Includes a file; if not found, throws a fatal error and stops. Also ensures the file is only included once, preventing duplicate class/function definitions. We use `require_once` for controllers and models because they are critically needed.

**Q27. What is a PHP session and how is it used in your project?**
> A PHP session stores user data on the server across multiple page requests, identified by a session cookie sent to the browser. In our project, `$_SESSION['auth']` stores the logged-in user's `id`, `role`, `name`, `username`, and `email`. This is set on login and checked on every protected page.

**Q28. What is `$_POST` vs `$_GET`? Which did you use for forms and why?**
> `$_GET` sends data in the URL (visible, bookmarkable, limited size). `$_POST` sends data in the HTTP body (not visible in URL, supports larger data). We use `$_POST` for login and registration forms because they contain sensitive data (passwords) and large payloads (file uploads), which must not appear in the URL.

**Q29. What is `isset()` and how is it used?**
> `isset()` checks if a variable exists and is not NULL. In `login.php`, `if (isset($_POST['login']))` checks whether the form was submitted (the submit button's `name="login"` is only in `$_POST` on submission). Without this check, the login logic would run on every page load.

**Q30. What is the `trim()` function? Why is it used on form inputs?**
> `trim()` removes leading and trailing whitespace from a string. It's used on all form inputs so that a username like `"  admin  "` is treated as `"admin"`, preventing accidental whitespace from causing validation failures or duplicate entries.

**Q31. What is `header('Location: ...')` and why must nothing be printed before it?**
> `header()` sends HTTP response headers to the browser. `Location:` redirects the browser to another page. Headers must be sent before any HTML output because once output starts, HTTP headers are already sent. `exit` is called immediately after to stop script execution after the redirect.

**Q32. What does `password_hash()` and `password_verify()` use internally?**
> They use the **bcrypt algorithm** by default (`PASSWORD_BCRYPT` is the default for `PASSWORD_DEFAULT`). Bcrypt automatically generates a unique **salt** and embeds it in the hash string itself, making rainbow table attacks impossible. The hash format is: `$2y$cost$salt+hash`.

---

## 🔵 Section G: Frontend & UX

**Q33. How does the registration form dynamically change based on role selection?**
> Using JavaScript. Role-specific form rows are initially hidden with CSS (`display: none`). The `toggleRoleFields()` function is attached to the role dropdown's `change` event via `addEventListener`. When Daycare is selected, daycare-specific fields appear; personal fields are hidden; HTML `required` attributes are dynamically set/removed to ensure correct browser validation.

**Q34. What is responsive design? How is it implemented here?**
> Responsive design ensures the UI adapts to different screen sizes. This is done using CSS **media queries** (`@media (max-width: 768px) { ... }`). For example, on mobile, the login page switches from a two-column layout (image + form) to a single-column layout.

**Q35. What is CSS Grid and where is it used?**
> CSS Grid is a 2D layout system. It's used in `dashboard.php` for the feature cards: `display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr))` — this creates a responsive grid that automatically adjusts column count based on screen width.

---

## 🔵 Section H: Advanced / Tricky Questions

**Q36. What is the difference between authentication and authorization?**
> **Authentication** = verifying *who* you are (login with username/password).
> **Authorization** = verifying *what* you are allowed to do (checking role before showing admin panel).
> Our project does both: `ensure_auth()` handles authentication; role checks (`if $role !== 'admin'`) handle authorization.

**Q37. Why is storing plain-text passwords dangerous?**
> If the database is compromised, attackers get all passwords immediately. They can use those passwords on other sites (password reuse attacks). Hashing with bcrypt means even database access doesn't reveal real passwords.

**Q38. Can you explain what `bind_param('s', $username)` means?**
> It binds the `$username` PHP variable to the `?` placeholder in the prepared SQL statement. `'s'` is the type specifier meaning **string**. Other types: `'i'` = integer, `'d'` = double, `'b'` = blob. This separation of SQL structure and data prevents SQL injection.

**Q39. What would happen if two users register simultaneously with the same username?**
> The `users` table has a `UNIQUE` constraint on `username`. Even if both users pass the application-level `usernameExists()` check simultaneously (race condition), MySQL's UNIQUE constraint will reject the second INSERT with a duplicate key error. Ideally, this error should be caught and handled gracefully.

**Q40. What is a flash message? How does it work in your project?**
> A flash message is a one-time notification stored in the session. `flash_set('success', 'Login successful.')` saves the message to `$_SESSION['flash']`. `flash_get('success')` reads and then **deletes** it from the session, so it only appears once. This is used for post-redirect feedback (e.g., after login or registration).

**Q41. Why is `uniqid()` used when naming uploaded certificate files?**
> To generate a unique filename for every upload, preventing filename collisions (two users uploading `certificate.pdf` would overwrite each other). `uniqid('cert_', true)` generates a string like `cert_6430f1a234e57.89` based on current microtime, which is virtually guaranteed to be unique.

**Q42. What is the `enctype="multipart/form-data"` attribute in the registration form?**
> It tells the browser to encode the form data in multipart format, which is **required** for file uploads. Without it, uploaded file data is not sent to the server and `$_FILES` will be empty.

**Q43. What improvements could you make to this project?**
> - Implement **email verification** after registration using a token-based system.
> - Add **CSRF (Cross-Site Request Forgery) protection** using hidden tokens in forms.
> - Use **PDO** instead of MySQLi for database-agnostic code and more consistent error handling.
> - Add **pagination** to admin listing pages.
> - Implement a **notification system** (push or email) for appointment reminders.
> - Add **input sanitization on the server** beyond just trimming (e.g., strip HTML tags from text areas).
> - Move to a full **MVC framework** like Laravel for scalability.

**Q44. What is the difference between `session_start()` and session data?**
> `session_start()` initializes or resumes a session. It must be called before any output is sent. Once started, `$_SESSION` superglobal is accessible to read/write session data. Session data is stored on the server (in temp files by default); only a small session ID is stored in the browser cookie.

**Q45. How do you make one user an admin in this system?**
> The project has a `make_admin.php` file. Typically this would update a user's `role` in the `users` table to `'admin'` via a SQL UPDATE query. This is usually a one-time setup script run by the developer directly.
