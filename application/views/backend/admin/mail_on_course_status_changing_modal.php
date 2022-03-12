<form  class="form-horizontal form-groups-bordered" action="<?php echo site_url('admin/change_course_status/'.$param2); ?>" method="post">
  <div class="form-group">
     <label><?php echo get_phrase('mail_subject'); ?></label>
     <input type="text" name = "mail_subject" class="form-control" placeholder="<?php echo get_phrase('mail_subject'); ?>" required>
  </div>

  <div class="form-group">
    <label><?php echo get_phrase('mail_body'); ?></label>
      <textarea name = "mail_body" class="form-control" required rows="6" placeholder="<?php echo get_phrase('mail_subject'); ?>"></textarea>
  </div>

  <input type="hidden" name="course_id" value="<?php echo $param3; ?>">
  <input type="hidden" name="category_id" value="<?php echo $param4; ?>">
  <input type="hidden" name="instructor_id" value="<?php echo $param5; ?>">
  <input type="hidden" name="price" value="<?php echo $param6; ?>">
  <input type="hidden" name="status" value="<?php echo $param7; ?>">

  <div class="text-right">
      <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('send_mail'); ?></button>
  </div>
</form>
