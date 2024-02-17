<?php
// print_r($_POST);
include_once '../../db.php';
$answer_id = $_POST['answer_id'] ?? null;
$text_answer = $_POST['text_answer'] ?? null;
$user_id = $_POST['user_id'] ?? '';
$survey_id = $_POST['survey_id'] ?? '';
$question_id = $_POST['question_id'] ?? '';

// Prepare the SQL statement
$sql = "INSERT INTO Responses (survey_id, question_id, answer_id, user_id, text_answer) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param('iiiss', $survey_id, $question_id, $answer_id, $user_id, $text_answer);


if ($stmt->execute()) {
    echo "Record inserted successfully.";
} else {
    echo "Error inserting record: " . $mysqli->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
