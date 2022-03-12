<?php
    // $student_list = $this->crud_model->all_enrolled_student()->result_array();
    $student_list = $this->user_model->get_user()->result_array();
?>
<div class="card">
	<h3>
		<span class="p-3"><?php echo get_phrase('write_new_messages');?></span>
	</h3>
	<div class="card-body">
		<form method="post" class="mt-2" action="<?php echo site_url('admin/message/send_new'); ?>" enctype="multipart/form-data">

			<div class="form-group">
		        <div class="row">
		            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		            	<label><?php echo get_phrase('Recipient'); ?></label>
		            	<i class="float-right mdi mdi-reply"></i>
                        <select class="form-control select2" data-toggle="select2" name="receiver" id="receiver" required>
							<option value=""><?php echo get_phrase('select_a_user');?></option>
                            <optgroup label="<?php echo get_phrase('students'); ?>">
                                <?php foreach($student_list as $student):?>
                                    <option value="<?php echo $student['id']; ?>">
                                        - <?php echo $student['first_name'].' '.$student['last_name']; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
						</select>
		            </div>
		        </div>
		    </div>

		    <div class="form-group">
		        <div class="row">
		            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <textarea class="form-control" rows="5" name="message" id="message" placeholder="<?php echo get_phrase('type_your_message'); ?>" required></textarea>
		            </div>
		        </div>
		    </div>

		    <div class="form-group mt-4">
		        <div class="row">
		            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-13 text-center">
		                <button type="submit" class="btn btn-success float-right"><?php echo get_phrase('sent_message'); ?></button>
		            </div>
		        </div>
		    </div>
		</form>
	</div>
</div>

<script type="text/javascript">
	function check_receiver() {
		var check_receiver = $('#receiver').val();
		if (check_receiver == '' || check_receiver == 0) {
			toastr.error("Please select a receiver", "Error");
            return false;
		}
	}
</script>
