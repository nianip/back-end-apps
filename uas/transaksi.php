<?php
// Koneksi ke database
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "uas";

$conn = new mysqli($serverName, $userName, $password, $dbName);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membaca input CLI
function readInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// Fungsi untuk menambahkan transaksi
function addTransaction($conn) {
    echo "=== Tambah Transaksi Baru ===\n";

    $id_user = (int)readInput("Masukkan ID User: ");
    $id_lapangan = (int)readInput("Masukkan ID Lapangan: ");
    $tanggal_booking = readInput("Masukkan Tanggal Booking (YYYY-MM-DD): ");
    $jam_mulai = readInput("Masukkan Jam Mulai (HH:MM:SS): ");
    $jam_selesai = readInput("Masukkan Jam Selesai (HH:MM:SS): ");

    $userExists = $conn->query("SELECT id FROM users WHERE id = $id_user")->num_rows > 0;
    $lapanganExists = $conn->query("SELECT id FROM tempat WHERE id = $id_lapangan")->num_rows > 0;

    if (!$userExists) {
        echo "Error: ID User tidak ditemukan.\n";
        return;
    }
    if (!$lapanganExists) {
        echo "Error: ID Lapangan tidak ditemukan.\n";
        return;
    }

    $sql = "INSERT INTO transaksi (id_user, id_lapangan, tanggal_booking, jam_mulai, jam_selesai)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $id_user, $id_lapangan, $tanggal_booking, $jam_mulai, $jam_selesai);

    if ($stmt->execute()) {
        echo "Transaksi berhasil ditambahkan!\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }

    $stmt->close();
}

// Fungsi untuk menampilkan semua transaksi
function showTransactions($conn) {
    echo "=== Daftar Transaksi ===\n";

    $result = $conn->query("SELECT * FROM transaksi");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID Transaksi: " . $row['id'] . "\n";
            echo "ID User: " . $row['id_user'] . "\n";
            echo "ID Lapangan: " . $row['id_lapangan'] . "\n";
            echo "Tanggal Booking: " . $row['tanggal_booking'] . "\n";
            echo "Jam Mulai: " . $row['jam_mulai'] . "\n";
            echo "Jam Selesai: " . $row['jam_selesai'] . "\n";
            echo "-----------------------------\n";
        }
    } else {
        echo "Tidak ada transaksi.\n";
    }
}

// Menu utama
function main($conn) {
    echo "=== Aplikasi Booking Lapangan ===\n";
    echo "1. Tambah Transaksi\n";
    echo "2. Tampilkan Transaksi\n";
    echo "0. Keluar\n";

    $choice = (int)readInput("Pilih menu: ");
    switch ($choice) {
        case 1:
            addTransaction($conn);
            break;
        case 2:
            showTransactions($conn);
            break;
        case 0:
            echo "Keluar dari aplikasi.\n";
            exit;
        default:
            echo "Pilihan tidak valid.\n";
    }
}

// Jalankan aplikasi
while (true) {
    main($conn);
}

// Tutup koneksi
$conn->close();
?>
