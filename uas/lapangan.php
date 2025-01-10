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

// Fungsi untuk menampilkan menu
function showMenu() {
    echo "\n=== Manajemen Lapangan ===\n";
    echo "1. Tambah Lapangan\n";
    echo "2. Edit Lapangan\n";
    echo "3. Hapus Lapangan\n";
    echo "4. Lihat Semua Lapangan\n";
    echo "5. Keluar\n";
    echo "Pilih opsi: ";
}

// Fungsi untuk menampilkan semua lapangan
function showAllFields($conn) {
    $result = $conn->query("SELECT * FROM tempat");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: {$row['id']}\n";
            echo "Nama Lapangan: {$row['nama_lapangan']}\n";
            echo "Alamat: {$row['alamat']}\n";
            echo "Deskripsi: {$row['deskripsi']}\n";
            echo "Ukuran: {$row['ukuran_lapangan']}\n";
            echo "Kapasitas: {$row['kapasitas_lapangan']}\n";
            echo "Harga: Rp" . number_format($row['harga'], 2) . "\n";
            echo "----------------------------------\n";
        }
    } else {
        echo "Tidak ada data lapangan.\n";
    }
}

// Fungsi untuk menambah lapangan
function addField($conn) {
    $nama = readInput("Masukkan nama lapangan: ");
    $alamat = readInput("Masukkan alamat lapangan: ");
    $deskripsi = readInput("Masukkan deskripsi lapangan: ");
    $ukuran = readInput("Masukkan ukuran lapangan (contoh: 20x40 meter): ");
    $kapasitas = (int)readInput("Masukkan kapasitas lapangan: ");
    $harga = (float)readInput("Masukkan harga per jam: ");

    $sql = "INSERT INTO tempat (nama_lapangan, alamat, deskripsi, ukuran_lapangan, kapasitas_lapangan, harga) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssid", $nama, $alamat, $deskripsi, $ukuran, $kapasitas, $harga);

    if ($stmt->execute()) {
        echo "Lapangan berhasil ditambahkan!\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
    $stmt->close();
}

// Fungsi untuk mengedit lapangan
function editField($conn) {
    $id = (int)readInput("Masukkan ID lapangan yang akan diubah: ");
    
    // Periksa apakah ID lapangan ada di database
    $result = $conn->query("SELECT * FROM tempat WHERE id = $id");
    if ($result->num_rows === 0) {
        echo "Lapangan dengan ID $id tidak ditemukan.\n";
        return;
    }
    
    // Ambil data lama
    $oldData = $result->fetch_assoc();
    echo "=== Data Lama ===\n";
    echo "Nama Lapangan: {$oldData['nama_lapangan']}\n";
    echo "Alamat: {$oldData['alamat']}\n";
    echo "Deskripsi: {$oldData['deskripsi']}\n";
    echo "Ukuran: {$oldData['ukuran_lapangan']}\n";
    echo "Kapasitas: {$oldData['kapasitas_lapangan']}\n";
    echo "Harga: Rp" . number_format($oldData['harga'], 2) . "\n";
    
    // Input data baru (jika kosong, gunakan data lama)
    echo "=== Masukkan Data Baru (kosongkan jika tidak ingin mengubah) ===\n";
    $nama = readInput("Masukkan nama lapangan baru: ") ?: $oldData['nama_lapangan'];
    $alamat = readInput("Masukkan alamat lapangan baru: ") ?: $oldData['alamat'];
    $deskripsi = readInput("Masukkan deskripsi lapangan baru: ") ?: $oldData['deskripsi'];
    $ukuran = readInput("Masukkan ukuran lapangan baru (contoh: 20x40 meter): ") ?: $oldData['ukuran_lapangan'];
    $kapasitas = readInput("Masukkan kapasitas lapangan baru: ") ?: $oldData['kapasitas_lapangan'];
    $harga = readInput("Masukkan harga per jam baru: ") ?: $oldData['harga'];

    // Update data
    $sql = "UPDATE tempat SET nama_lapangan = ?, alamat = ?, deskripsi = ?, ukuran_lapangan = ?, harga = ?, kapasitas_lapangan = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdii", $nama, $alamat, $deskripsi, $ukuran, $harga, $kapasitas, $id);

    if ($stmt->execute()) {
        echo "Lapangan berhasil diperbarui!\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
    $stmt->close();
}


// Fungsi untuk menghapus lapangan
function deleteField($conn) {
    $id = (int)readInput("Masukkan ID lapangan yang akan dihapus: ");
    $sql = "DELETE FROM tempat WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Lapangan berhasil dihapus!\n";
    } else {
        echo "Error: " . $stmt->error . "\n";
    }
    $stmt->close();
}

// Main loop
while (true) {
    showMenu();
    $choice = (int)readInput("");

    switch ($choice) {
        case 1:
            addField($conn);
            break;
        case 2:
            editField($conn);
            break;
        case 3:
            deleteField($conn);
            break;
        case 4:
            showAllFields($conn);
            break;
        case 5:
            echo "Keluar dari program.\n";
            $conn->close();
            exit;
        default:
            echo "Pilihan tidak valid.\n";
    }
}
?>
