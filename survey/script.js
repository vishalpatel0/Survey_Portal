$(document).ready(function () {
  // loadSurvey();
  // load_survey_info();
  // console.log("page load done");
  // Event listener for form submission
  $(document).on("submit", "#userForm", function (event) {
    event.preventDefault();
    // Send form data to the server
    var formData = $(this).serialize();
    submitForm(formData);
  });

  // Function to submit form data asynchronously
  function submitForm(formData) {
    $.ajax({
      url: "code/user_login.php", // Get the form action attribute
      method: "post", // Get the form method attribute
      data: formData,
      // dataType: "json",
      success: function (response) {
        if (response.success) {
          localStorage.setItem("user_id", response.user_id);
          localStorage.setItem("name", response.name);
          localStorage.setItem("age", response.age);
          localStorage.setItem("sex", response.sex);
          loadSurvey();
        } else {
          if (response.error) {
            alert(response.error);
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  // Function to load content into main_div
  function loadSurvey() {
    $.ajax({
      url: "load_survey.php",
      method: "get",
      // dataType: "json",
      success: function (data) {
        loadQuestions(currentPage);

        $(".main_div").html(data);
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  var currentPage = 1;
  var totalPages;

  function loadQuestions(page) {
    var surveyId = localStorage.getItem("survey_id"); // Retrieve survey_id from local storage
    var userId = localStorage.getItem("user_id"); // Retrieve user_id from local storage

    if (totalPages !== "" && totalPages < page) {
      $("#survey-container").html("Thank you for participating");
      localStorage.clear();
    } else {
      $.ajax({
        url: "code/get_survey.php",
        type: "GET",
        data: {
          survey_id: surveyId,
          page: page,
        },
        success: function (response) {
          // Parse JSON data
          var data = JSON.parse(response);
          var questions = data.questions;
          totalPages = data.total_pages;

          questions.forEach(function (question) {
            var questionHtml = "";
            $("#survey-q").html(question.question_Text);

            // Generate answer fields based on question type
            if (question.type === "radio" || question.type === "checkbox") {
              question.answers.forEach(function (answer) {
                questionHtml +=
                  '<div class="form-check">' +
                  '<input required class="form-check-input" type="' +
                  question.type +
                  '" name="answer_id" value="' +
                  answer.answer_id +
                  '">' +
                  '<label class="form-check-label">' +
                  answer.answer_text +
                  "</label>" +
                  "</div>";
              });
            } else if (question.type === "text") {
              questionHtml +=
                '<input required class="form-control" type="text" name="text_answer">';
            }

            // Include user_id, survey_id, and question_id in the input field
            questionHtml +=
              '<input type="hidden" name="user_id" value="' + userId + '">';
            questionHtml +=
              '<input type="hidden" name="survey_id" value="' + surveyId + '">';
            questionHtml +=
              '<input type="hidden" name="question_id" value="' +
              question.question_id +
              '">';

            $("#survey-ans").html(questionHtml);
          });

          // Display total number of pages
          // console.log("Total Pages: " + totalPages);
        },
      });
    }
  }

  // Next button click event

  $(document).on("click", "#next-btn", function () {
    // Check if all required fields are filled or checked
    if (validateForm()) {
      let data = $("#survey-container form").serialize();
      save_data(data);
      // If form validation is successful, proceed to load the next set of questions
      currentPage++;
      loadQuestions(currentPage);
    } else {
      // If form validation fails, display an error message or take appropriate action
      alert("Please fill out all required fields.");
    }
  });

  // Function to validate the form data
  // Function to validate the form data
  function validateForm() {
    // Get the form inside the survey-container
    var form = $("#survey-container form");

    // Get all required input fields within the form
    var requiredInputs = form.find("input[required]");

    // Loop through each required input field
    for (var i = 0; i < requiredInputs.length; i++) {
      var input = requiredInputs[i];

      // Check if the input field is empty or unchecked
      if (input.type === "radio") {
        // For radio buttons, check if any radio button with the same name is checked
        var radioGroup = form.find("input[name='" + input.name + "']:checked");
        if (!radioGroup.length) {
          return false; // Return false if no radio button in the group is checked
        }
      } else if (input.type === "checkbox") {
        // For checkboxes, check if at least one checkbox with the same name is checked
        var checkboxGroup = form.find(
          "input[name='" + input.name + "']:checked"
        );
        if (!checkboxGroup.length) {
          return false; // Return false if no checkbox in the group is checked
        }
      } else if (!input.value.trim()) {
        return false; // Return false if any other required field is empty
      }
    }

    return true; // Return true if all required fields are filled or checked
  }

  function save_data(data) {
    $.ajax({
      url: "code/post_survey.php",
      data: data,
      method: "POST",
      success: function (data) {
        console.log(data);
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }
});
