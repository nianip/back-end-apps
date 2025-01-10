<?php
$serverName = "localhost";
$userName= "root";
$password= "";
$dbName= "uas";

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');

// Mendapatkan input JSON dari React Native
$EndCode = file_get_contents('php://input');
$DeCode = json_decode($EndCode, true);

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil username dan password dari input JSON
$idUser = $DeCode['username'] ?? '';
$passw = $DeCode['password'] ?? '';
// $idUser = "admin";
// $passw = "admin";

// Query ke database untuk mengecek user
$sql = "SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $idUser, $passw);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            "token" => $row['username'],
            "role" => $row['role'],
        ];
    }
} else {
    $response[] = [
        "token" => "",
        "role" => ""
    ];
}

// Mengembalikan response dalam format JSON
echo json_encode($response);

$stmt->close();
$conn->close();
?>
