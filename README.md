BUS PASS MANAGEMENT SYSTEM — SETUP GUIDE (XAMPP)
==================================================

WHAT THIS PROJECT IS
---------------------
A PHP + MySQL mini project for managing city bus passes:
- Public website with Home / About / Pass Types / How it Works / Contact
- Commuter registration & login
- Apply for a pass (Student / General / Senior Citizen) and track status
- Admin panel to approve/reject applications and view contact messages
- Contact form that saves messages into the database
- Face photo upload on the application form; photo shows on dashboards and on the printable pass card (pass.php)

HOW TO RUN IT ON XAMPP
-----------------------
1. Install XAMPP if you don't already have it: https://www.apachefriends.org

2. Copy the whole "bus_pass_management" folder into your XAMPP htdocs folder:
      Windows:  C:\xampp\htdocs\bus_pass_management
      Mac:      /Applications/XAMPP/htdocs/bus_pass_management
      Linux:    /opt/lampp/htdocs/bus_pass_management

3. Open the XAMPP Control Panel and click "Start" next to Apache and MySQL.

4. Create the database:
      a. Go to http://localhost/phpmyadmin
      b. Click "Import" in the top menu
      c. Choose the file: bus_pass_management/database/bus_pass_db.sql
      d. Click "Go" — this creates the "bus_pass_db" database and all tables.

5. Open the project in your browser:
      http://localhost/bus_pass_management/

That's it — the site is live.

LOGIN DETAILS
-------------
Commuter account: register your own from the "Apply Now" button on the site.

Admin panel: http://localhost/bus_pass_management/admin/login.php
   Username: admin
   Password: admin123
(This admin account is created automatically the first time you open the
admin login page — you don't need to add it manually.)

IF SOMETHING DOESN'T WORK
--------------------------
- "Connection failed" error → make sure MySQL is running in XAMPP and that
  you imported bus_pass_db.sql in phpMyAdmin.
- If your MySQL root user has a password (some setups do), open config.php
  in the project's root folder and set $DB_PASS to that password.
- Blank white page → open XAMPP's php_error_log or check that PHP version
  is 7.4 or higher (needed for password_hash / mysqli).

PROJECT STRUCTURE
------------------
bus_pass_management/
├── config.php                Database connection settings
├── index.php                 Homepage (About, Pass Types, How it Works, Contact)
├── register.php / login.php / logout.php     Commuter authentication
├── apply.php                 Bus pass application form
├── dashboard.php             Commuter's own applications & status
├── pass.php                  Printable pass card with face photo (approved passes)
├── uploads/photos/           Uploaded face photos
├── admin/
│   ├── login.php              Admin login (auto-creates default admin)
│   ├── dashboard.php          Approve/reject applications, view messages
│   ├── update_status.php      Handles the approve/reject action
│   └── logout.php
├── includes/
│   ├── header.php             Shared navigation bar
│   └── footer.php             Shared footer
├── css/style.css              All styling
└── database/bus_pass_db.sql   Database schema (import this first)

UPGRADING AN EXISTING DATABASE
-------------------------------
If you already imported the old bus_pass_db.sql, run database/add_photo_column.sql
in phpMyAdmin once (adds the "photo" column). Fresh installs don't need this.
Uploaded photos are saved in uploads/photos/ (make sure this folder is writable).

FEEL FREE TO CUSTOMISE
------------------------
- Pass prices/types: edit the <select> options in apply.php and the pricing
  cards in index.php, and the ENUM values in the SQL file if you add types.
- Colours/fonts: all defined as CSS variables at the top of css/style.css.
- Support contact details: edit the "Contact" section in index.php.
