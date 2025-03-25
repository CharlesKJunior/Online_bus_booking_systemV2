<?php
session_start();
require 'db.php';

// Check if a trip ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid trip selection.");
}

$trip_id = intval($_GET['id']);

// Fetch trip details from the database
$trip_query = "SELECT * FROM trips WHERE id = ?";
$stmt = $conn->prepare($trip_query);
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$trip_result = $stmt->get_result();
$trip = $trip_result->fetch_assoc();

if (!$trip) {
    die("Trip not found.");
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Trip - Dapo Travels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.jpg" alt="Dapo Travels" height="50">
            </a>
        </div>
    </nav>

    <main class="container my-5">
        <h2 class="text-center fw-bold">Book Your Trip</h2>
        <div class="card p-4 shadow-sm">
            <h4 class="fw-bold">Trip Details</h4>
            <p><strong>Trip:</strong> <?php echo htmlspecialchars($trip['trip_name']); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($trip['origin']); ?></p>
            <p><strong>Stopovers:</strong> <?php echo htmlspecialchars($trip['stopovers']); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($trip['destination']); ?></p>
            <p><strong>Departure:</strong> <?php echo htmlspecialchars(date("g:ia", strtotime($trip['departure_time']))); ?></p>
            <p><strong>Arrival:</strong> <?php echo htmlspecialchars(date("g:ia", strtotime($trip['expected_arrival_time']))); ?></p>
            <p><strong>Fare:</strong> UGX <?php echo htmlspecialchars(number_format($trip['fare'])); ?></p>
        </div>
        
        <form action="process_booking.php" method="POST" class="mt-4">
            <input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="seats" class="form-label">Number of Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Confirm Booking</button>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 Dapo Travels. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
