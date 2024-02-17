<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container p-3">

        <a href="index.php" class="btn btn-light">All Survey</a>
        <div class="row">
            <div class="col-sm-6  ">
                <div class="card shadow p-4 mt-3">
                    <h2>Add Question</h2>
                    <form id="questionForm" action="code/add_question.php" method="post">
                        <input type="hidden" value="<?= $_GET['survey_id'] ?>" name="survey_id">
                        <div class="mb-3">
                            <label for="questionText" class="form-label">Question Text</label>
                            <input type="text" class="form-control" id="questionText" name="questionText" required>
                        </div>
                        <div class="mb-3">
                            <label for="questionType" class="form-label">Question Type</label>
                            <select class="form-select" id="questionType" name="questionType" required>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="text">Text</option>
                            </select>
                        </div>
                        <div id="answerFields">
                            <div class="mb-3 answer">
                                <label class="form-label">Answer </label>
                                <div class="row">
                                    <div class="col-9">
                                        <input type="text" class="form-control" name="answers[]" required>
                                    </div>
                                    <div class="col-3">
                                        <button type="button" class="btn btn-primary" id="addAnswer"> +Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>

            <div class="col-sm-6  p-4 mt-3">
                <?php include_once 'questions_list.php' ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const answerFields = document.getElementById("answerFields");
            const addAnswerBtn = document.getElementById("addAnswer");

            function createAnswerField(answerCount) {
                const newAnswerField = document.createElement("div");
                newAnswerField.classList.add("mb-3", "answer");
                newAnswerField.innerHTML = `
                   
                <label for="answer${answerCount}" class="form-label">Answer ${answerCount}</label>
                  <div class="row">
                     <div class="col-9">
                    <input type="text" class="form-control" id="answer${answerCount}" name="answers[]" required>
                    </div>
                
                    <div class="col-3">
                    <button type="button" class="btn btn-danger btn-sm remove-answer">Remove</button>
                </div>
               </div>
               </div>
                    `;
                return newAnswerField;
            }

            function addAnswerField() {
                const answerCount = answerFields.querySelectorAll(".answer").length + 1;
                if (answerCount <= 5) {
                    const newAnswerField = createAnswerField(answerCount);
                    answerFields.appendChild(newAnswerField);
                } else {
                    alert("Maximum 5 answers allowed.");
                }
            }

            function removeAnswerField(event) {
                const answerField = event.target.closest(".answer");
                if (answerField) {
                    answerField.remove();
                }
            }

            addAnswerBtn.addEventListener("click", addAnswerField);
            answerFields.addEventListener("click", function(event) {
                if (event.target.classList.contains("remove-answer")) {
                    removeAnswerField(event);
                }
            });
        });

        document.getElementById('questionType').addEventListener('change', function() {
            var questionType = this.value;
            var answerFields = document.getElementById('answerFields');
            if (questionType == 'text') {
                answerFields.style.display = 'none';
                var inputField = answerFields.querySelector('input');
                inputField.removeAttribute('required');
            } else {
                answerFields.style.display = 'block';
                var inputField = answerFields.querySelector('input');
                inputField.setAttribute('required', 'required');
            }
        });
    </script>

</body>

</html>