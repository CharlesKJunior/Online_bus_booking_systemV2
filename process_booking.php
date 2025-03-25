<?php
session_start();
require 'db.php';

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_id = intval($_POST['trip_id']);
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $seats   = intval($_POST['seats']);

    if (empty($name) || empty($email) || empty($phone) || $seats < 1) {
        die("All fields are required, and seats must be at least 1.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate phone number (simple check)
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        die("Invalid phone number format.");
    }

    // Check if trip exists using store_result() for better compatibility
    $trip_query = "SELECT id FROM trips WHERE id = ?";
    $stmt = $conn->prepare($trip_query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $trip_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
        die("Trip not found.");
    }
    $stmt->close();

    // Insert booking into the database
    $insert_query = "INSERT INTO bookings (trip_id, name, email, phone, seats, booking_time) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isssi", $trip_id, $name, $email, $phone, $seats);
    
    if ($stmt->execute()) {
        echo "<script>alert('Booking successful! You will receive a confirmation email soon.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Booking failed. Please try again later.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
