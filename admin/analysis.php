<?php
// Database connection
include_once "../db.php";

// Retrieving the survey ID from the GET parameter
$survey_id = isset($_GET['survey_id']) ? $_GET['survey_id'] : null;

if ($survey_id === null || !is_numeric($survey_id)) {
    // Handle invalid or missing survey ID
    echo "Invalid survey ID.";
    exit();
}


// SQL query to retrieve unique user information who participated in the specified survey
$query_list = "SELECT Users.user_id, Users.name AS user_name, Users.age, Users.sex, MAX(Responses.created_at) AS participation_time
               FROM Users
               JOIN Responses ON Users.user_id = Responses.user_id
               WHERE Responses.survey_id = ?
               GROUP BY Users.user_id
               ORDER BY participation_time DESC";



$stmt_list = $conn->prepare($query_list);
$stmt_list->bind_param('i', $survey_id);
$stmt_list->execute();
$result_list = $stmt_list->get_result();


// SQL query to count the number of participants for each date
$query_chart = "SELECT DATE(Responses.created_at) AS participation_date, COUNT(DISTINCT Responses.user_id) AS participant_count
                FROM Responses
                WHERE Responses.survey_id = ?
                GROUP BY participation_date";

$stmt_chart = $conn->prepare($query_chart);
$stmt_chart->bind_param('i', $survey_id);
$stmt_chart->execute();
$result_chart = $stmt_chart->get_result();

$data_chart = array();

$data_chart = array();
while ($row_chart = $result_chart->fetch_assoc()) {
    // Format the date without the time component
    $formatted_date = date("Y-m-d", strtotime($row_chart['participation_date']));
    $data_chart[] = array($formatted_date, intval($row_chart['participant_count']));
}

// Close the list statement
$stmt_list->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Participation</title>
    <!-- Load Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Load Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Survey Participation</h1>
        <div class="row">
            <!-- Chart container -->
            <div class="col-md-6">
                <h2>Participation Chart</h2>
                <div id="chart_div" style="width: 100%; height: 400px;"></div>


            </div>
            <!-- List of participants -->
            <div class="col-md-6">
                <h2>List of Participants</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Participation Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // print_r($result_list->fetch_assoc());
                        // Display user information who participated in the survey

                        while ($row_list = $result_list->fetch_assoc()) {

                            echo "<tr>";
                            echo "<td>" . $row_list["user_name"] . "</td>";
                            echo "<td>" . $row_list["age"] . "</td>";
                            echo "<td>" . $row_list["sex"] . "</td>";
                            echo "<td>" .  date("M j, g:i A", strtotime($row_list["participation_time"]))
                                . "</td>";
                            echo "<td><a href='user_ans.php?survey_id=" . $survey_id . "&user_id=" . $row_list["user_id"] . "' class='btn btn-primary'>View</a></td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript to draw the chart -->
    <script type="text/javascript">
        // Log the data received from PHP
        console.log(<?php echo json_encode($data_chart); ?>);

        // Load Google Charts
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // Create the data table for chart
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Participants');

            // Convert string dates to JavaScript Date objects
            var formattedData = <?php echo json_encode($data_chart); ?>;
            formattedData.forEach(function(row) {
                row[0] = new Date(row[0]); // Convert date string to Date object
                row[0].setHours(0, 0, 0, 0); // Set time to midnight
            });

            // Add rows to the data table
            data.addRows(formattedData);

            // Set chart options
            var options = {
                title: 'Survey Participation by Date',
                legend: {
                    position: 'none'
                },
                hAxis: {
                    title: 'Date'
                },
                vAxis: {
                    title: 'Participants'
                }
            };


            // Instantiate date formatter
            var dateFormatter = new google.visualization.DateFormat({
                pattern: 'MMM dd, yyyy'
            });
            dateFormatter.format(data, 0); // Apply formatting to the first column (date)

            // Instantiate and draw the chart as a bar chart
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</body>

</html>