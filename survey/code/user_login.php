<?php
// Start session
session_start();
include_once '../../db.php';

// Define response array
$response = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];

    if (empty($name) || empty($age) || empty($sex)) {
        // Set error message in response
        $response["success"] = false;
        $response["error"] = "All fields are required.";
    } else {
        // Prepare and execute SQL query to insert user data into Users table
        $sql = "INSERT INTO Users (name, age, sex) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $name, $age, $sex);
        $stmt->execute();
        $stmt->close();
        $user_id = $conn->insert_id;

        // // Store user data in session
        // $_SESSION["user_id"] = $conn->insert_id;
        // $_SESSION["name"] = $name;
        // $_SESSION["age"] = $age;
        // $_SESSION["sex"] = $sex;

        $response["name"] = $name;
        $response["user_id"] = $user_id;
        $response["age"] = $age;
        $response["sex"] = $sex;
        $response["success"] = true;

        // Set success flag in response
        $response["success"] = true;
    }

    // Return response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
