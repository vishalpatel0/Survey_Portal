<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center the form vertically */
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>

<body>


    <div class="container">
        <div class="row center main_div">

            <div class="col-md-6 card p-4 shadow">

                <?php
                include "load_survey_info.php";
                ?>
                <br>Take a quick survey, help us enhance service quality together!

                <?php if (isset($_SESSION["error"])) : ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <?php echo $_SESSION["error"]; ?>
                    </div>
                    <?php unset($_SESSION["error"]); // Remove error message from session 
                    ?>
                <?php endif; ?>

                <form id="userForm" class="mt-4" action="code/user_login.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" autofocus name="name" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" name="age" placeholder="Enter your age">
                    </div>
                    <div class="mb-3">
                        <label for="sex" class="form-label">Sex</label>
                        <select class="form-select" name="sex">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg d-block mx-auto">Start Survey</button>
                </form>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Retrieve survey_id from PHP session
        var surveyId = "<?php echo isset($_SESSION['survey_id']) ? $_SESSION['survey_id'] : ''; ?>";

        // Store survey_id in local storage
        if (surveyId) {
            localStorage.setItem('survey_id', surveyId);
        }

        // // Retrieve user data from PHP session
        // var userId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
        // var name = "<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>";
        // var age = "<?php echo isset($_SESSION['age']) ? $_SESSION['age'] : ''; ?>";
        // var sex = "<?php echo isset($_SESSION['sex']) ? $_SESSION['sex'] : ''; ?>";

        // // Store user data in local storage
        // if (userId && name && age && sex) {
        //     localStorage.setItem('user_id', userId);
        //     localStorage.setItem('name', name);
        //     localStorage.setItem('age', age);
        //     localStorage.setItem('sex', sex);
        // }
    });
</script>


</html>