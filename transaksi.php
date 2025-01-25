<?php
header('Content-Type: application/json');

// Koneksi ke database
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "uas";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (
        isset(
            $data['id_user'],
            $data['id_lapangan'],
            $data['tanggal_booking'],
            $data['jam_booking'],
            $data['nama_pembooking']
        )
    ) {
        // Sanitize inputs
        $id_user = (int)$data['id_user'];
        $id_lapangan = (int)$data['id_lapangan'];
        $tanggal_booking = $conn->real_escape_string($data['tanggal_booking']);
        $jam_booking = $conn->real_escape_string($data['jam_booking']);
        $nama_pembooking = $conn->real_escape_string($data['nama_pembooking']);
        $catatan = isset($data['catatan']) ? $conn->real_escape_string($data['catatan']) : '';

        // Check if the user and lapangan exist
        $userExists = $conn->query("SELECT id FROM users WHERE id = $id_user")->num_rows > 0;
        $lapanganExists = $conn->query("SELECT id FROM tempat WHERE id = $id_lapangan")->num_rows > 0;

        if (!$userExists) {
            echo json_encode(['success' => false, 'message' => 'ID User tidak ditemukan.']);
            exit;
        }
        if (!$lapanganExists) {
            echo json_encode(['success' => false, 'message' => 'ID Lapangan tidak ditemukan.']);
            exit;
        }

        // Insert data into the transaksi table
        $sql = "INSERT INTO transaksi (id_user, id_lapangan, tanggal_booking, jam_booking, nama_pembooking, catatan) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissss", $id_user, $id_lapangan, $tanggal_booking, $jam_booking, $nama_pembooking, $catatan);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan booking: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Field yang dibutuhkan tidak lengkap.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid.']);
}

$conn->close();
