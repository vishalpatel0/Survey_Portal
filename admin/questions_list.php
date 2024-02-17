<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Questions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h3>Survey Questions</h3>

        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <?php
                    // Include database connection file
                    include_once "../db.php";

                    // Check if survey_id is set
                    if (isset($_GET['survey_id'])) {
                        $survey_id = $_GET['survey_id'];

                        // Prepare SQL query to fetch questions and their answers for the given survey_id
                        $sql = "SELECT q.question_id, q.question_Text, q.type, a.answer_id, a.answer_text
                                FROM Questions q
                                LEFT JOIN Answers a ON q.question_id = a.question_id
                                WHERE q.survey_id = ?   ORDER BY q.question_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $survey_id);
                        $stmt->execute();

                        // Get the result set
                        $result = $stmt->get_result();

                        // Initialize an array to store questions and their answers
                        $questionsWithAnswers = array();

                        // Fetch questions and their answers and store them in the array
                        while ($row = $result->fetch_assoc()) {
                            $question_id = $row['question_id'];
                            $question_Text = $row['question_Text'];
                            $type = $row['type'];
                            $answer_id = $row['answer_id'];
                            $answer_text = $row['answer_text'];

                            // Check if the question exists in the array, if not, add it
                            if (!isset($questionsWithAnswers[$question_id])) {
                                $questionsWithAnswers[$question_id] = array(
                                    'question_Text' => $question_Text,
                                    'type' => $type,
                                    'answers' => array()
                                );
                            }

                            // Add the answer to the respective question
                            if ($answer_id !== null && $answer_text !== null) {
                                $questionsWithAnswers[$question_id]['answers'][] = array(
                                    'answer_id' => $answer_id,
                                    'answer_text' => $answer_text
                                );
                            }
                        }

                        // Close the statement
                        $stmt->close();

                        // Display questions and answers
                        $count = 1;
                        echo "<br> Total Questions : " . count($questionsWithAnswers);
                        echo "<br> ";
                        echo "<br> ";
                        foreach ($questionsWithAnswers as $question) {
                            echo "<li class='list-group-item '>";
                            echo "<h3>" . $count++ . "). " . $question['question_Text'] . "</h3>";

                            // Check the question type and generate appropriate UI elements
                            if ($question['type'] === 'radio') {
                                // Radio buttons
                                echo "<div class='form-check'>";
                                foreach ($question['answers'] as $answer) {
                                    echo "<input class='form-check-input' type='radio' name='radio_$question_id' id='radio_$answer[answer_id]' value='$answer[answer_id]'>";
                                    echo "<label class='form-check-label' for='radio_$answer[answer_id]'>$answer[answer_text]</label><br>";
                                }
                                echo "</div>";
                            } elseif ($question['type'] === 'checkbox') {
                                // Checkboxes
                                echo "<div class='form-check'>";
                                foreach ($question['answers'] as $answer) {
                                    echo "<input class='form-check-input' type='checkbox' name='checkbox_$question_id' id='checkbox_$answer[answer_id]' value='$answer[answer_id]'>";
                                    echo "<label class='form-check-label' for='checkbox_$answer[answer_id]'>$answer[answer_text]</label><br>";
                                }
                                echo "</div>";
                            } elseif ($question['type'] === 'text') {
                                // Text input
                                echo "<input type='text' class='form-control' name='text_$question_id' id='text_$question_id' placeholder='Enter your answer'>";
                            } else {
                                // Unsupported type
                                echo "<p>Unsupported question type: " . $question['type'] . "</p>";
                            }

                            echo "</li>";
                        }
                    } else {
                        echo "<li class='list-group-item'>Survey ID is not provided.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>