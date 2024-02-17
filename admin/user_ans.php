<?php
include_once "../db.php";

// Retrieving user_id and survey_id from the GET parameters
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$survey_id = isset($_GET['survey_id']) ? $_GET['survey_id'] : null;

// SQL query to retrieve data
$query = "SELECT Surveys.name AS survey_name, Users.name AS user_name, Users.age, Users.sex,
          Questions.question_Text, Answers.answer_text, Responses.text_answer
          FROM Responses
          JOIN Surveys ON Responses.survey_id = Surveys.survey_id
          JOIN Questions ON Responses.question_id = Questions.question_id
          LEFT JOIN Answers ON Responses.answer_id = Answers.answer_id
          JOIN Users ON Responses.user_id = Users.user_id
          WHERE Responses.survey_id = ? AND Responses.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $survey_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Responses</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="container">
        <h1>Survey Name:
            <?php
            // Display survey name as h1 tag
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo $row["survey_name"];
            } else {
                echo "Survey Not Found";
            }
            ?>
        </h1>
        <div class="row center main_div">

            <div class="col-md-6 card p-4 shadow">

                <h2>User Info</h2>
                <table>
                    <tr>
                        <th>User Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                    </tr>
                    <?php
                    // Display user details as h2 tags
                    if ($result->num_rows > 0) {
                        echo "<tr>";
                        echo "<td>" . $row["user_name"] . "</td>";
                        echo "<td>" . $row["age"] . "</td>";
                        echo "<td>" . $row["sex"] . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='3'>User Not Found</td></tr>";
                    }
                    ?>
                </table>

                <h2>Survey Questions and Answers</h2>
                <table>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                    </tr>
                    <?php
                    // Display survey questions and answers
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["question_Text"] . "</td>";
                        // Display either answer_text or text_answer based on the type of question
                        echo "<td>";
                        if ($row["answer_text"] !== null) {
                            echo $row["answer_text"];
                        } else {
                            echo $row["text_answer"];
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<?php
// Close connection
$stmt->close();
$conn->close();
?>