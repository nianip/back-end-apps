<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "localhost";
$username = "root"; // Replace with your DB username
$password = "";     // Replace with your DB password
$dbname = "uas";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Fetch bookings for the current date
$currentDate = date('Y-m-d'); // Get current date in YYYY-MM-DD format
$query = "
    SELECT 
        t.id, 
        t.tanggal_booking AS date, 
        t.jam_booking AS timeSlots, 
        t.nama_pembooking AS name, 
        t.catatan AS notes, 
        p.nama_lapangan AS fieldName,
        p.alamat AS address,
        p.deskripsi AS description,
        p.harga AS price 
    FROM transaksi t
    JOIN tempat p ON t.id_lapangan = p.id
    WHERE t.tanggal_booking = '$currentDate'
    ORDER BY t.jam_booking";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(["message" => "No bookings found"]);
}

$conn->close();
