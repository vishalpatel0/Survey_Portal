<?php
include 'db.php';

if (isset($_GET['survey_id']) && !empty($_GET['survey_id'])) {
    session_start();
    // Set session variable
    $_SESSION['survey_id'] = $_GET['survey_id'];


    // Redirect to another page
    header("Location: survey/");
    exit; // Make sure to exit after redirecting

} else {
    echo "<h1>Sorry invalid url</h1>";
}
