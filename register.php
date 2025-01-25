<?php
// Konfigurasi koneksi database
$serverName = "localhost";
$username = "root"; // Sesuaikan dengan user database Anda
$password = "";     // Sesuaikan dengan password database Anda
$dbName = "uas"; // Nama database Anda

// Membuat koneksi
$conn = new mysqli($serverName, $username, $password, $dbName);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membaca input dari CLI
function readInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// Membaca input pengguna
echo "=== Registrasi Pengguna ===\n";
$usernameInput = readInput("Masukkan username: ");
$passwordInput = readInput("Masukkan password: ");

// Validasi input
if (empty($usernameInput) || empty($passwordInput)) {
    echo "Username dan password tidak boleh kosong.\n";
    exit;
}

// Hash password untuk keamanan
$passwordHash = hash('sha256', $passwordInput);

// Simpan data ke database
$sql = "INSERT INTO users (username, password, created_at) VALUES (?, ?, now())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usernameInput, $passwordHash);

if ($stmt->execute()) {
    echo "Registrasi berhasil!\n";
} else {
    echo "Error: " . $stmt->error . "\n";
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
