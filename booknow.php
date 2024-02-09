<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "cme";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close connection
// $conn->close();

$response = ''; // Initialize response variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['enquiry'];
    // Retrieve other form field values similarly

    // Initialize error flags
    $errorEmpty = false;
    $errorEmail = false;
    $errorPhone = false;

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $response .= "All fields are required.";
        $errorEmpty = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response .= "Please enter a valid email.";
        $errorEmail = true;
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $response .= "Please enter a valid phone number.";
        $errorPhone = true;
    } else {
        // No errors, save data to database
        // Prepare SQL statement
        $sql = "INSERT INTO cme_data (name, email, phone, message) VALUES (?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssss", $name, $email, $phone, $message);

        // Execute statement
        if ($stmt->execute()) {
            $response .= "Data saved successfully.";
        } else {
            $response .= "Failed to save data. Please try again.";
        }

        // Close statement
        $stmt->close();

    }

    // Print the response
    echo $response;
}

?>