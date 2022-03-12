<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('private_message'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                <!-- compose new email button -->
                <div class="mail-sidebar-row visible-xs">
                    <a href="<?php echo site_url('admin/message/message_new');?>" class="btn btn-success btn-block">
                        <?php echo get_phrase('new_message');?>
                        <i class="mdi mdi-pencil float-right"></i>
                    </a>
                </div>
                <hr>


                <!-- message user inbox list -->
                <ul class="navbar-nav">

                    <?php
                    $current_user = $this->session->userdata('user_id');
                    $this->db->where('sender', $current_user);
                    $this->db->or_where('receiver', $current_user);
                    $message_threads = $this->db->get('message_thread')->result_array();
                    foreach($message_threads as $row):

                        // defining the user to show
                        if ($row['sender'] == $current_user)
                            $user_to_show_id = $row['receiver'];
                        if ($row['receiver'] == $current_user)
                            $user_to_show_id = $row['sender'];

                        $unread_message_number = $this->crud_model->count_unread_message_of_thread($row['message_thread_code']);
                        ?>
                        <li class="nav-item">
                            <a class="text-left mb-1 btn btn-light d-block <?php if (isset($current_message_thread_code) && $current_message_thread_code == $row['message_thread_code'])echo 'active';?>" href="<?php echo site_url('admin/message/message_read/' . $row['message_thread_code']);?>">

                                <?php
                                    $user_details = $this->db->get_where('users' , array('id' => $user_to_show_id))->row_array();
                                    echo $user_details['first_name'].' '.$user_details['last_name'];
                                ?>
                                <!-- <span class="badge badge-light pull-right" style="color:#aaa;"><?php echo $user_details['role_id'] == 1 ? get_phrase('admin') : get_phrase('student') ;?></span> -->

                                <?php if ($unread_message_number > 0):?>
                                    <span class="badge badge-secondary pull-right">
                                        <?php echo $unread_message_number;?>
                                    </span>
                                <?php endif;?>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <?php include $message_inner_page_name.'.php';?>
            </div>
        </div>
    </div>
</div>
