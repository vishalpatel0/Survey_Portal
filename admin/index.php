<?php
// PHP code to handle form submission and update status
// Include your database connection file here
include_once '../db.php';

// Fetch data from Surveys table
$sql = "SELECT * FROM Surveys order by survey_id DESC ";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Survey </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    .Active {
        background-color: #d1ffdd;
    }

    .Inactive {
        background-color: #ffd1d6;

    }
</style>

<body>
    <div class="container">
        <h2>Add new Survey </h2>
        <form method="post" action="code/add_survey.php">
            <label for="survey_name">Survey Name:</label>
            <input type="text" id="survey_name" name="survey_name" required>
            <button type="submit">Add Survey</button>
        </form>
        <br>
        <br>
        <h2>List of Survey Fields</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?> <div class="col-sm-4 ">
                        <div class="card m-2 shadow <?php echo $row["status"] ? "Active" : "Inactive"; ?>">
                            <div class="card-body">
                                <h5 class="card-title"> Name: <?php echo $row["name"]; ?></h5>
                                <!-- <p class="card-text">Name: <?php echo $row["name"]; ?></p> -->
                                <a href="questions.php?survey_id=<?php echo $row["survey_id"]; ?>" class="btn btn-sm btn-light">Questions</a>
                                <a href="analysis.php?survey_id=<?php echo $row["survey_id"]; ?>" class="btn btn-sm btn-info">analysis</a>
                                <br>
                                <br>
                                <a href="code/add_survey.php?action=change_status&survey_id=<?php echo $row["survey_id"]; ?>&status=<?php echo $row["status"]; ?>" class="btn btn-primary btn-sm">Change Status</a>

                                <a href="#" class="copy-link btn btn-sm btn-secondary" data-surveyid="<?php echo $row["survey_id"]; ?>" data-status="<?php echo $row["status"]; ?>">Copy Link</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No survey data available</td></tr>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Event listener for copy link button
            $(document).on("click", ".copy-link", function(event) {
                event.preventDefault();

                // Get survey ID and status from data attributes
                var surveyId = $(this).data("surveyid");

                // Construct URL with parameters encoded into a hash
                var url = 'http://localhost/test/?survey_id=' + surveyId;

                // Copy URL to clipboard
                navigator.clipboard.writeText(url)
                    .then(function() {
                        alert("Link copied to clipboard: " + url);
                    })
                    .catch(function(error) {
                        console.error("Error copying link: ", error);
                    });
            });
        });
    </script>
</body>

</html>