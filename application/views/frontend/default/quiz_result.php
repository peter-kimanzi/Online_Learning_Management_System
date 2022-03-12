<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-quiz-result-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Review the course materials to expand your learning.</h5>
                <p class="card-text">You got <?php echo $total_correct_answers; ?> out of <?php echo $total_questions; ?> correct.</p>
            </div>
        </div>
    </div>
</div>

<?php foreach ($submitted_quiz_info as $each):
    $question_details = $this->crud_model->get_quiz_question_by_id($each['question_id'])->row_array();
    $options = json_decode($question_details['options']);
    $correct_answers = json_decode($each['correct_answers']);
    $submitted_answers = json_decode($each['submitted_answers']);
?>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card text-left card-with-no-color-no-border">
            <div class="card-body">
                <h6 class="card-title"><img src="<?php echo $each['submitted_answer_status'] == 1 ? base_url('assets/frontend/default/img/green-tick.png') : base_url('assets/frontend/default/img/red-cross.png'); ?>" alt="" height="15px;"> <?php echo $question_details['title']; ?></h6>
                <?php for ($i = 0; $i < count($correct_answers); $i++): ?>
                    <p class="card-text"> -
                        <?php echo $options[($correct_answers[$i] - 1)]; ?>
                        <img src="<?php echo base_url('assets/frontend/default/img/green-circle-tick.png'); ?>" alt="" height="15px;">
                    </p>
                <?php endfor; ?>
                <p class="card-text"> <strong><?php echo get_phrase("submitted_answers"); ?>: </strong> [
                    <?php
                    $submitted_answers_as_csv = "";
                    for ($i = 0; $i < count($submitted_answers); $i++){
                        $submitted_answers_as_csv .= $options[($submitted_answers[$i] - 1)].', ';
                    }
                    echo rtrim($submitted_answers_as_csv, ', ');
                    ?>
                    ]</p>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="text-center">
    <a href="javascript::" name="button" class="btn btn-sign-up mt-2" style="color: #fff;" onclick="location.reload();"><?php echo get_phrase("take_again"); ?></a>
</div>
