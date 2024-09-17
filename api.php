<?php
// API to receive and insert data into the database

// Database credentials
$host = 'localhost';  // Your database host
$dbname = 'api_db_test';    // Your database name
$username = 'root';    // Your database username
$password = '';        // Your database password

// Create a connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data from the request body (JSON format expected)
    $input = json_decode(file_get_contents('php://input'), true);

    // Ensure all required fields are available
    if (isset($input['name']) && isset($input['email']) && isset($input['message'])) {
        // Prepare the SQL query to insert data into the table
        $name = $conn->real_escape_string($input['name']);
        $email = $conn->real_escape_string($input['email']);
        $message = $conn->real_escape_string($input['message']);

        $sql = "INSERT INTO data_table (name, email, message) VALUES ('$name', '$email', '$message')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Return success response
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Data inserted successfully']);
        } else {
            // Return error response
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert data: ' . $conn->error]);
        }
    } else {
        // Return error response for missing fields
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
} else {
    // Return error response for wrong HTTP method
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
