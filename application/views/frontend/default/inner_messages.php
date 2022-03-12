<?php if (!isset($message_thread_code)): ?>
    <div class="text-center empty-box"><?php echo get_phrase('select_a_message_thread_to_read_it_here'); ?>.</div>
<?php endif; ?>
<?php
    if (isset($message_thread_code)):
    $message_thread_details = $this->db->get_where('message_thread', array('message_thread_code' => $message_thread_code))->row_array();
    if ($this->session->userdata('user_id') == $message_thread_details['sender']){
        $user_to_show_id = $message_thread_details['receiver'];
    }
    else{
        $user_to_show_id = $message_thread_details['sender'];
    }
    $user_to_show_details = $this->user_model->get_all_user($user_to_show_id)->row_array();
    $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();?>
    <div class="message-details d-show">
        <div class="message-header">
            <a href="<?php echo site_url('home/instructor_page'); ?>">
                <span class="sender-info">
                    <span class="d-inline-block">
                        <img src="<?php echo $this->user_model->get_user_image_url($user_to_show_id);?>" alt="">
                    </span>
                    <span class="d-inline-block">
                    <?php echo $user_to_show_details['first_name'].' '.$user_to_show_details['last_name']; ?>
                </span>
                </span>
            </a>
        </div>
        <div class="message-content">
            <?php foreach ($messages as $message): ?>
                <?php if ($message['sender'] == $this->session->userdata('user_id')): ?>
                    <div class="message-box-wrap me">
                        <div class="message-box">
                            <div class="time"><?php echo date('D, d-M-Y', $message['timestamp']); ?></div>
                            <div class="message"><?php echo $message['message']; ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="message-box-wrap">
                        <div class="message-box">
                            <div class="time"><?php echo date('D, d-M-Y', $message['timestamp']); ?></div>
                            <div class="message"><?php echo $message['message']; ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="message-footer">
            <form class="" action="<?php echo site_url('home/my_messages/send_reply/'.$message_thread_code); ?>" method="post">
                <textarea class="form-control" name="message" placeholder="<?php echo get_phrase('type_your_message'); ?>..."></textarea>
                <button class="btn send-btn" type="submit"><?php echo get_phrase('send'); ?></button>
            </form>
        </div>
    </div>
<?php endif; ?>
