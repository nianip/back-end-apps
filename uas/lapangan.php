<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$serverName = "localhost";
$username = "root";
$password = ""; 
$dbName = "uas"; 

$conn = new mysqli($serverName, $username, $password, $dbName);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}

function showAllFields($conn) {
    $result = $conn->query("SELECT * FROM tempat");
    $lapangan = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lapangan[] = [
                'id' => $row['id'],
                'nama_lapangan' => $row['nama_lapangan'],
                'alamat' => $row['alamat'],
                'deskripsi' => $row['deskripsi'],
                'ukuran' => $row['ukuran_lapangan'],
                'kapasitas' => $row['kapasitas_lapangan'],
                'harga' => $row['harga']
            ];
        }
        echo json_encode($lapangan);
    } else {
        echo json_encode(["message" => "Tidak ada data lapangan."]);
    }
}

function getFieldById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM tempat WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    echo json_encode($data);
    $stmt->close();
}

function addField($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nama_lapangan'], $data['alamat'], $data['deskripsi'], $data['ukuran'], $data['kapasitas'], $data['harga'])) {
        echo json_encode(["error" => "Data tidak lengkap"]);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO tempat (nama_lapangan, alamat, deskripsi, ukuran_lapangan, kapasitas_lapangan, harga) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssid", $data['nama_lapangan'], $data['alamat'], $data['deskripsi'], $data['ukuran'], $data['kapasitas'], $data['harga']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lapangan berhasil ditambahkan"]);
    } else {
        echo json_encode(["error" => "Gagal menambahkan lapangan"]);
    }
    $stmt->close();
}

function editField($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['nama_lapangan'], $data['alamat'], $data['deskripsi'], $data['ukuran'], $data['kapasitas'], $data['harga'])) {
        echo json_encode(["error" => "Data tidak lengkap"]);
        return;
    }

    $stmt = $conn->prepare("UPDATE tempat SET nama_lapangan = ?, alamat = ?, deskripsi = ?, ukuran_lapangan = ?, kapasitas_lapangan = ?, harga = ? WHERE id = ?");
    $stmt->bind_param("ssssidi", $data['nama_lapangan'], $data['alamat'], $data['deskripsi'], $data['ukuran'], $data['kapasitas'], $data['harga'], $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lapangan berhasil diperbarui"]);
    } else {
        echo json_encode(["error" => "Gagal memperbarui lapangan"]);
    }
    $stmt->close();
}

function deleteField($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM tempat WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lapangan berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus lapangan"]);
    }
    $stmt->close();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getFieldById($conn, $_GET['id']);
        } else {
            showAllFields($conn);
        }
        break;
    case 'POST':
        addField($conn);
        break;
    case 'PUT':
        editField($conn);
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteField($conn, $_GET['id']);
        } else {
            echo json_encode(["error" => "ID diperlukan untuk menghapus lapangan"]);
        }
        break;
    default:
        echo json_encode(["error" => "Metode tidak didukung"]);
        break;
}

$conn->close();
?>
