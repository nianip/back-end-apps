<?php
header("Content-Type: application/json");

$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "uas";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_lapangan = $_GET['id_lapangan'];
    $tanggal_booking = $_GET['tanggal_booking'];

    $query = "SELECT jam_booking FROM transaksi 
              WHERE id_lapangan = ? AND tanggal_booking = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $id_lapangan, $tanggal_booking);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookedSlots = [];
    while ($row = $result->fetch_assoc()) {
        $slots = explode(', ', $row['jam_booking']);
        $bookedSlots = array_merge($bookedSlots, $slots);
    }

    echo json_encode(['success' => true, 'bookedSlots' => $bookedSlots]);
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_lapangan = $data['id_lapangan'];
    $tanggal_booking = $data['tanggal_booking'];
    $jam_booking = explode(', ', $data['jam_booking']);

    foreach ($jam_booking as $slot) {
        $checkQuery = "SELECT * FROM transaksi WHERE id_lapangan = ? AND tanggal_booking = ? AND FIND_IN_SET(?, jam_booking)";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("iss", $id_lapangan, $tanggal_booking, $slot);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Slot waktu $slot sudah dipesan."]);
            $stmt->close();
            $conn->close();
            exit;
        }
        $stmt->close();
    }

    echo json_encode(["success" => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
