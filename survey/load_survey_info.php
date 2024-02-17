<?php
// Include database connection file
include_once "../db.php";

// Function to get survey name and total number of questions by survey ID
function getSurveyInfo($survey_id)
{
    global $conn;

    // Initialize variables to store survey name and total number of questions
    $survey_info = array(
        'name' => '',
        'total_questions' => 0
    );

    // Query to get survey name
    $sql_survey_name = "SELECT name FROM Surveys WHERE survey_id = ?";
    $stmt_survey_name = $conn->prepare($sql_survey_name);
    $stmt_survey_name->bind_param("i", $survey_id);
    $stmt_survey_name->execute();
    $result_survey_name = $stmt_survey_name->get_result();

    if ($row_survey_name = $result_survey_name->fetch_assoc()) {
        $survey_info['name'] = $row_survey_name['name'];
    }

    $stmt_survey_name->close();

    // Query to get total number of questions
    $sql_total_questions = "SELECT COUNT(*) AS total FROM Questions WHERE survey_id = ?";
    $stmt_total_questions = $conn->prepare($sql_total_questions);
    $stmt_total_questions->bind_param("i", $survey_id);
    $stmt_total_questions->execute();
    $result_total_questions = $stmt_total_questions->get_result();

    if ($row_total_questions = $result_total_questions->fetch_assoc()) {
        $survey_info['total_questions'] = $row_total_questions['total'];
    }

    $stmt_total_questions->close();

    return $survey_info;
}

// Example usage:
if (!isset($_SESSION['survey_id']))
    die(header("Location: ../"));

$survey_id = $_SESSION['survey_id']; // Replace 1 with the desired survey_id
$survey_info = getSurveyInfo($survey_id);

if (!empty($survey_info)) {
    echo "<h4>" . $survey_info['name'] . "</h4>";
    echo "Total Questions: " . $survey_info['total_questions'];
} else {
    echo "Survey not found.";
}

// Close the connection
$conn->close();
