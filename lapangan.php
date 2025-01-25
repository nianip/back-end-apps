<?php
// Set headers before any output to avoid issues
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
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
            echo json_encode($row);
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
                $lapangan[] = $row;
            }
        }

        echo json_encode($lapangan);
    }
} elseif ($method === 'POST') {
    // Add a new field
    $input = json_decode(file_get_contents("php://input"), true);
    // ... (existing code for adding a field)
} elseif ($method === 'PUT') {
    // Update an existing field
    // ... (existing code for updating a field)
} elseif ($method === 'DELETE') {
    // Delete a field
    if (isset($_GET['id'])) {
        $id = $conn->real_escape_string($_GET['id']);
        $sql = "DELETE FROM tempat WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Field successfully deleted."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete field: " . $conn->error]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID parameter is required."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}

// Close connection
$conn->close();
