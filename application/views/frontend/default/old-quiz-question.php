<?php
// $param2 is Quiz id
$quiz_details = $this->crud_model->get_lessons('lesson', $param2)->row_array();
$questions = $this->crud_model->get_quiz_questions($param2)->result_array();
?>
<?php if (count($quiz_details)): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" id = "parent-div" data-plugin="dragula" data-containers='["question-list"]'>
                    <div class="col-md-12">
                        <div class="bg-dragula p-2 p-lg-4">
                            <h5 class="mt-0"><?php echo get_phrase('questions_of').': '.$quiz_details['title']; ?>
                                <button type="button" class="btn btn-outline-primary btn-sm btn-rounded alignToTitle" onclick="showAjaxModal('<?php echo site_url('modal/popup/question_add/'.$param2) ?>', '<?php echo get_phrase('add_new_question'); ?>')" name="button" data-dismiss="modal"><?php echo get_phrase('add_new_question'); ?></button>
                            </h5>
                            <div id="section-list" class="py-2">
                                <?php foreach ($questions as $question): ?>
                                    <!-- Item -->
                                    <div class="card mb-0 mt-2 draggable-item on-hover-action" id = "<?php echo 'quiz-question-'.$question['id']; ?>">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <h5 class="mb-1 mt-0"><?php echo $question['title']; ?>
                                                        <span id = "<?php echo 'widgets-of-quiz-question-'.$question['id']; ?>" class="widgets-of-quiz-question">
                                                            <a href="javascript::" class="alignToTitle float-right ml-1 text-secondary" onclick="deleteQuizQuestionAndReloadModal('<?php echo $param2; ?>', '<?php echo $question['id']; ?>')" data-dismiss="modal"><i class="dripicons-cross"></i></a>
                                                            <a href="javascript::" class="alignToTitle float-right text-secondary" onclick="showAjaxModal('<?php echo site_url('modal/popup/question_edit/'.$question['id'].'/'.$param2); ?>', '<?php echo get_phrase('update_quiz_question'); ?>')" data-dismiss="modal"><i class="dripicons-document-edit"></i></a>
                                                        </span>
                                                    </h5>
                                                </div> <!-- end media-body -->
                                            </div> <!-- end media -->
                                        </div> <!-- end card-body -->
                                    </div> <!-- end col -->
                                <?php endforeach; ?>
                            </div> <!-- end company-list-1-->
                        </div> <!-- end div.bg-light-->
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<?php endif; ?>

<script type="text/javascript">
jQuery(document).ready(function() {
    $('.widgets-of-quiz-question').hide();
});

$('.on-hover-action').mouseenter(function() {
    var id = this.id;
    $('#widgets-of-'+id).show();
});
$('.on-hover-action').mouseleave(function() {
    var id = this.id;
    $('#widgets-of-'+id).hide();
});

function deleteQuizQuestionAndReloadModal(quizID, questionID) {
    var deletionURL = '<?php echo site_url(); ?>'+'admin/quiz_questions/'+quizID+'/delete/'+questionID;
    var reloadURL = '<?php echo site_url(); ?>'+'modal/popup/quiz_questions/'+quizID;
    confirm_modal(deletionURL);
}
</script>
