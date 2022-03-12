<?php
    $message_thread_details = $this->db->get_where('message_thread', array('message_thread_code' => $current_message_thread_code))->row_array();
    $first_sender = $message_thread_details['sender'];
?>
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
	    <!-- Chat-->
	    <div class="card">
	        <div class="card-body">
	            <h4 class="header-title mb-3">Chat</h4>

	            <div class="chat-conversation">
	                <ul class="conversation-list slimscroll" style="max-height: 250px;">
	                	<?php
						$messages =	$this->db->get_where('message' , array('message_thread_code' => $current_message_thread_code))->result_array();
						foreach ($messages as $row):
                            $sender_details = $this->user_model->get_all_user($row['sender'])->row_array();
							$sender_id   =	$row['sender'];

							$data['message_thread_code'] = $current_message_thread_code;
						?>

		                    <li class="clearfix <?php if($first_sender != $sender_id )echo 'odd'; ?>">
		                        <div class="chat-avatar">
		                            <a href="#">
							            <img src="<?php echo $this->user_model->get_user_image_url($sender_id);?>" alt="image" class="mr-3 d-none d-sm-block avatar-sm rounded-circle" style="height: 40px; width: 40px;">
							        </a>
		                        </div>
		                        <div class="conversation-text">
		                            <div class="ctext-wrap">
		                                <i>
				                    		<?php echo $this->db->get_where('users' , array('id' => $sender_id))->row()->first_name.' '.$this->db->get_where('users' , array('id' => $sender_id))->row()->last_name;?>
				                		</i>
		                                <p> <?php echo $row['message'];?> </p>
		                            </div>
                                    <small class="message_sending_time"><?php echo date("d M, Y" , $row['timestamp']);?></small>
		                        </div>
		                    </li>
	                    <?php endforeach;?>
	                </ul>
	                <form method="post" action="<?php echo site_url('admin/message/send_reply/'.$current_message_thread_code); ?>" class="needs-validation" novalidate name="chat-form" id="chat-form">
	                    <div class="row">
	                        <div class="col">
	                            <input type="text" name="message" class="form-control chat-input" placeholder="Enter your text" required>
	                            <div class="invalid-feedback">
	                                <?php echo get_phrase('Please enter your messsage'); ?>
	                            </div>
	                        </div>
	                        <div class="col-auto">
	                            <button type="submit" class="btn btn-danger chat-send btn-block waves-effect waves-light"><?php echo get_phrase('sent_message'); ?></button>
	                        </div>
	                    </div>
	                </form>

	            </div> <!-- end .chat-conversation-->
	        </div>
	    </div> <!-- end card-->
	</div> <!-- end col-->
</div>
