<?php
header("Content-Type: application/json");

$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "uas";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['id_user']) ||
    empty($data['id_lapangan']) ||
    empty($data['tanggal_booking']) ||
    empty($data['jam_booking']) ||
    empty($data['nama_pembooking'])
) {
    echo json_encode(["success" => false, "message" => "Semua field wajib diisi."]);
    exit;
}

$id_user = $data['id_user'];
$id_lapangan = $data['id_lapangan'];
$tanggal_booking = $data['tanggal_booking'];
$jam_booking = $data['jam_booking'];
$nama_pembooking = $data['nama_pembooking'];
$catatan = isset($data['catatan']) ? $data['catatan'] : null;

$sql = "INSERT INTO transaksi (id_user, id_lapangan, tanggal_booking, jam_booking, nama_pembooking, catatan) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $id_user, $id_lapangan, $tanggal_booking, $jam_booking, $nama_pembooking, $catatan);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Booking berhasil disimpan."]);
} else {
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan saat menyimpan booking: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
