<?php
echo "<pre>";
print_r($_POST);
// Include database connection file
include_once "../../db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $survey_id = $_POST["survey_id"];
    $questionText = $_POST["questionText"];
    $questionType = $_POST["questionType"];
    $answers = $_POST["answers"] ?? [];

    // Check if the survey exists
    $sql = "SELECT * FROM Surveys WHERE survey_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $survey_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Survey not found.";
        exit();
    }

    // Insert question into Questions table
    $sql = "INSERT INTO Questions (survey_id, question_Text, type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $survey_id, $questionText, $questionType);
    if ($stmt->execute()) {
        $question_id = $conn->insert_id;
        // Insert answers into Answers table
        foreach ($answers as $answer_text) {
            $sql = "INSERT INTO Answers (question_id, answer_text) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $question_id, $answer_text);
            $stmt->execute();
        }
        echo "Question and answers added successfully.";
    } else {
        echo "Failed to add question.";
    }
} else {
    echo "Invalid request.";
}

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
