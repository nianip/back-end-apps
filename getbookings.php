<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connect to the database
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "uas";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$month = isset($_GET['month']) ? (int)$_GET['month'] : null;

if ($month && $month >= 1 && $month <= 12) {
    $sql = "SELECT id, nama_pembooking, tanggal_booking, jam_booking 
            FROM transaksi 
            WHERE MONTH(tanggal_booking) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $month);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    echo json_encode(['success' => true, 'bookings' => $bookings]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid month parameter']);
}

$conn->close();
