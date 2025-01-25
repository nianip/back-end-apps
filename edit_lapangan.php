<?php
// Set headers before any output to avoid issues
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Database configuration
$serverName = "localhost";
$username = "root"; // Adjust to your database user
$password = "";     // Adjust to your database password
$dbName = "uas";    // Your database name

// Create database connection
$conn = new mysqli($serverName, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Check if 'id' parameter is set for fetching specific field
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = "SELECT * FROM tempat WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode([
                'id' => $row['id'],
                'nama_lapangan' => $row['nama_lapangan'],
                'alamat' => $row['alamat'],
                'deskripsi' => $row['deskripsi'],
                'ukuran' => $row['ukuran_lapangan'],
                'kapasitas' => $row['kapasitas_lapangan'],
                'harga' => $row['harga']
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Field not found."]);
        }
    } else {
        // Fetch all fields
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
        }

        echo json_encode($lapangan);
    }
} elseif ($method === 'PUT') {
    // Update an existing field
    $input = json_decode(file_get_contents("php://input"), true);

    if (!empty($input['id']) && !empty($input['nama_lapangan']) && !empty($input['alamat']) && !empty($input['deskripsi']) && !empty($input['ukuran']) && !empty($input['kapasitas']) && !empty($input['harga'])) {
        $id = (int)$input['id'];
        $nama = $conn->real_escape_string($input['nama_lapangan']);
        $alamat = $conn->real_escape_string($input['alamat']);
        $deskripsi = $conn->real_escape_string($input['deskripsi']);
        $ukuran = $conn->real_escape_string($input['ukuran']);
        $kapasitas = (int)$input['kapasitas'];
        $harga = (float)$input['harga'];

        $sql = "UPDATE tempat 
                SET nama_lapangan = '$nama', 
                    alamat = '$alamat', 
                    deskripsi = '$deskripsi', 
                    ukuran_lapangan = '$ukuran', 
                    kapasitas_lapangan = $kapasitas, 
                    harga = $harga 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Field successfully updated."]);
        } else {
            // Log detailed SQL error
            http_response_code(500);
            echo json_encode(["error" => "Failed to update field: " . $conn->error]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Incomplete data. Ensure all fields are filled."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}

// Close connection
$conn->close();
