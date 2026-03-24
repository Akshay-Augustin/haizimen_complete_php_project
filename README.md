# Haizimen complete PHP project scaffold

This scaffold is designed for a college project where you already have UI pages and now need:
- PHP project structure
- routes
- DB connection
- backend logic
- compatibility with existing `connect.php` includes

## Important note
A fully working form submit/login flow cannot be wired **without at least connecting the UI page to backend code**.
So this package does the safest possible version of "without touching existing code":

1. It keeps your original UI pages visually unchanged as much as possible.
2. It adds backend files, controllers, models, routes, schema, helpers, and DB config.
3. It also includes **compatible working pages** (`parent.php`, `login_cgt.php`) that preserve your UI and add processing at the top.

## Run locally
```bash
php -S localhost:8000 -t .
```

Open:
- http://localhost:8000/index.php
- http://localhost:8000/parent.php
- http://localhost:8000/login_cgt.php

## Database setup
1. Create MySQL database named `haizimen_db`
2. Import `database/schema.sql`
3. Edit `config/database.php`

## Main files
- `config/database.php`
- `includes/connect.php`
- `index/connect.php`
- `routes/web.php`
- `app/Controllers/AuthController.php`
- `app/Models/User.php`
- `app/Helpers/helpers.php`
- `database/schema.sql`

## Compatibility
Your existing code uses:
- `include("connect.php");`
- `include("index/connect.php");`

Both are provided.

## What is included
- parent registration backend
- login backend
- dashboard stub
- logout
- simple routing map
- upload handling
- validation helpers
- password hashing
- session handling

## What you can add next
- doctor registration
- daycare registration
- caretaker registration
- appointment booking
- vaccination booking
- chat
- admin panel
