<?php
// ============================================================
// Database configuration - edit here if your MySQL setup differs
// ============================================================
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";        // default XAMPP MySQL password is blank
$DB_NAME = "bus_pass_db";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() .
        "<br>Make sure XAMPP's Apache and MySQL are running, and that you " .
        "imported database/bus_pass_db.sql in phpMyAdmin.");
}

// Start session on every page that includes this file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
