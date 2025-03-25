<?php
session_start();
include 'db.php'; // Ensure database connection

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT b.id, t.trip_name, b.boarding_point, b.dropoff_point, b.booking_date, b.seat_number, b.status, b.payment_status 
                         FROM bookings b 
                         JOIN trips t ON b.trip_id = t.id 
                         WHERE b.user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

echo "<h2>My Bookings</h2>";
echo "<table border='1'>
<tr>
<th>Trip Name</th>
<th>Boarding Point</th>
<th>Drop-off Point</th>
<th>Booking Date</th>
<th>Seat Number</th>
<th>Status</th>
<th>Payment Status</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['trip_name']}</td>
    <td>{$row['boarding_point']}</td>
    <td>{$row['dropoff_point']}</td>
    <td>{$row['booking_date']}</td>
    <td>{$row['seat_number']}</td>
    <td>{$row['status']}</td>
    <td>{$row['payment_status']}</td>
    </tr>";
}

echo "</table>";
?>
