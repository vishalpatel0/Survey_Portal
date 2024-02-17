<?php
// Start session
session_start();
include_once '../../db.php';

// Define response array
$response = array();


// Add data to Surveys table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form is submitted
    if (isset($_POST['survey_name'])) {
        $survey_name = $_POST['survey_name'];
        // Add validation if needed

        // Insert data into Surveys table
        $sql = "INSERT INTO Surveys (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $survey_name);
        $stmt->execute();
        $stmt->close();
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;

        // Check for success/failure and display message
    }
}

// change status
// Check the action 
if (isset($_GET['action']) && $_GET['action'] == 'change_status') {
    if (isset($_GET['status'])) {
        $status = ($_GET['status'] == 0) ? 1 : 0; // Toggle the status value

        // Get the survey_id from the query parameters
        $survey_id = $_GET['survey_id'];

        // Prepare and execute SQL query to update the status of the survey
        $sql = "UPDATE Surveys SET status = ? WHERE survey_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $status, $survey_id); // Bind parameters
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // echo "Status changed successfully.";
        } else {
            // echo "Failed to change status.";
        }
        // Close the statement
        $stmt->close();
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
}
