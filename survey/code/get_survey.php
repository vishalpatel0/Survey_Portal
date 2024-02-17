<?php
// Include database connection file
include_once "../../db.php";

// Check if survey_id is set
if (isset($_GET['survey_id'])) {
    // Pagination variables
    $limit = 1; // Number of questions per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number

    // Calculate the offset for the SQL query
    $offset = ($page - 1) * $limit;

    // Prepare SQL query to fetch questions with all related answers and pagination
    $sqlCount = "SELECT COUNT(*) AS total FROM Questions WHERE survey_id = ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param("i", $survey_id);
    $survey_id = $_GET['survey_id']; // Replace with the desired survey ID
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $totalQuestions = $resultCount->fetch_assoc()['total'];
    $totalPages = ceil($totalQuestions / $limit);

    $sql = "SELECT q.*
            FROM Questions q
            WHERE q.survey_id = ?
            ORDER BY q.question_id ASC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $survey_id, $limit, $offset);
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Initialize an array to store questions with answers
    $questionsWithAnswers = array();

    // Fetch questions and their answers and store them in the array
    while ($row = $result->fetch_assoc()) {
        $question_id = $row['question_id'];

        // Prepare SQL query to fetch all answers for the given question_id
        $sql2 = "SELECT * FROM Answers WHERE question_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $question_id);
        $stmt2->execute();
        // Get the result set
        $result2 = $stmt2->get_result();
        $answers = array();
        while ($ans_row = $result2->fetch_assoc()) {
            $answers[] = $ans_row;
        }
        $row['answers'] = $answers;
        $questionsWithAnswers[] = $row;

        // Close the inner statement
        $stmt2->close();
    }

    // Close the outer statement and connection
    $stmt->close();
    $conn->close();

    // Combine questions, answers, and total pages into an array
    $response = array(
        'questions' => $questionsWithAnswers,
        'total_pages' => $totalPages
    );

    // Return questions with their answers and total pages as JSON
    echo json_encode($response);
} else {
    echo "Survey ID is not provided.";
}
