<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('purchase_history'); ?></a> </li>
</ol>

<div class="page-title"> <i class="icon-custom-left"></i>
 <h3><?php echo $page_title; ?></h3>
</div>

<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-body no-border">
          <div class="row">
              <br>
              <table class="table" id="example3">
                  <thead>
                    <tr>
                      <th><?php echo get_phrase('photo'); ?></th>
                      <th><?php echo get_phrase('user_name'); ?></th>
                      <th><?php echo get_phrase('email'); ?></th>
                      <th><?php echo get_phrase('purchased_course'); ?></th>
                      <th><?php echo get_phrase('paid_amount'); ?></th>
                      <th><?php echo get_phrase('payment_type'); ?></th>
                      <th><?php echo get_phrase('purchased_date'); ?></th>
                      <th><?php echo get_phrase('actions'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($purchase_history->result_array() as $purchase):
                          $user_data = $this->db->get_where('users', array('id' => $purchase['user_id']))->row_array();
                          $course_data = $this->db->get_where('course', array('id' => $purchase['course_id']))->row_array();?>
                          <tr class="gradeU">
                            <td><img src="<?php echo base_url().'assets/user_image/'.$user_data['photo']; ?>" alt="" height="50" width="50"> </td>
                            <td>
                                <?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
                            </td>
                            <td><?php echo $user_data['email']; ?></td>
                            <td><?php echo $course_data['title']; ?></td>
                            <td><?php echo $purchase['amount']; ?></td>
                            <td><?php echo $purchase['payment_type']; ?></td>
                            <td><?php echo date('D, d-M-Y', $purchase['date_added']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-small btn-white btn-demo-space"><?php echo get_phrase('action'); ?></button>
                                    <button class="btn btn-small btn-white dropdown-toggle btn-demo-space" data-toggle="dropdown"> <span class="caret"></span> </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo site_url('admin/purchase_history/delete/'.$purchase['id']); ?>');">
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                          </tr>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
                </table>
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">

</script>
