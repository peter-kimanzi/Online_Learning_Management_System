<?php
$quiz_questions = $this->crud_model->get_quiz_questions($lesson_details['id']);
?>
<div id="quiz-body">
    <div class="" id="quiz-header">
        <?php echo get_phrase("quiz_title"); ?> : <strong><?php echo $lesson_details['title']; ?></strong><br>
        <?php echo get_phrase("number_of_questions"); ?> : <strong><?php echo count($quiz_questions->result_array()); ?></strong><br>
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <button type="button" name="button" class="btn btn-sign-up mt-2" style="color: #fff;" onclick="getStarted(1)"><?php echo get_phrase("get_started"); ?></button>
        <?php endif; ?>
    </div>

    <form class="" id = "quiz_form" action="" method="post">
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <?php foreach ($quiz_questions->result_array() as $key => $quiz_question):
                $options = json_decode($quiz_question['options']);
            ?>
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_details['id']; ?>">
                <div class="hidden" id = "question-number-<?php echo $key+1; ?>">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card text-left">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo get_phrase("question").' '.($key+1); ?> : <strong><?php echo $quiz_question['title']; ?></strong></h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php
                                    foreach ($options as $key2 => $option): ?>
                                    <li class="list-group-item quiz-options">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="<?php echo $quiz_question['id']; ?>[]" value="<?php echo $key2+1; ?>" id="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2+1; ?>" onclick="enableNextButton('<?php echo $quiz_question['id'];?>')">
                                            <label class="form-check-label" for="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2+1; ?>">
                                                <?php echo $option; ?>
                                            </label>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button type="button" name="button" class="btn btn-sign-up mt-2 mb-2" id = "next-btn-<?php echo $quiz_question['id'];?>" style="color: #fff;" <?php if(count($quiz_questions->result_array()) == $key+1):?>onclick="submitQuiz()"<?php else: ?>onclick="showNextQuestion('<?php echo $key+2; ?>')"<?php endif; ?> disabled><?php echo count($quiz_questions->result_array()) == $key+1 ? get_phrase("check_result") : get_phrase("submit_&_next"); ?></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
</div>
<div id="quiz-result" class="text-left">

</div>
<script type="text/javascript">
function getStarted(first_quiz_question) {
    $('#quiz-header').hide();
    $('#lesson-summary').hide();
    $('#question-number-'+first_quiz_question).show();
}
function showNextQuestion(next_question) {
    $('#question-number-'+(next_question-1)).hide();
    $('#question-number-'+next_question).show();
}
function submitQuiz() {
    $.ajax({
        url: '<?php echo site_url('home/submit_quiz'); ?>',
        type: 'post',
        data: $('form#quiz_form').serialize(),
        success: function(response) {
            $('#quiz-body').hide();
            $('#quiz-result').html(response);
        }
    });
}
function enableNextButton(quizID) {
    $('#next-btn-'+quizID).prop('disabled', false);
}
</script>
