<?php
    $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
    $sections = $this->crud_model->get_section('course', $course_id)->result_array();
    $lessons = $this->crud_model->get_lessons('course', $course_id);
    $lesson_details = $this->crud_model->get_lessons('lesson', $lesson_id)->row_array();
?>

<section class="page-header-area my-course-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home/dashboard'); ?>"><?php echo get_phrase('dashboard'); ?></a></li>
                        <li class="breadcrumb-item active"><a href="#"><?php echo get_phrase('edit_course_detail'); ?></a></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo $page_title .' - '.$course_details['title'];; ?></h1>
                <ul>
                    <li class="<?php if($type == 'edit_course') echo "active"; ?>"><a href="<?php echo site_url('home/edit_course/'.$course_id.'/edit_course'); ?>"><?php echo get_phrase('edit_basic_informations'); ?></a></li>
                    <li class="<?php if($type == 'manage_section') echo "active"; ?>"><a href="<?php echo site_url('home/edit_course/'.$course_id.'/manage_section'); ?>"><?php echo get_phrase('manage_section'); ?></a></li>
                    <li class="<?php if($type == 'manage_lesson') echo "active"; ?>"><a href="<?php echo site_url('home/edit_course/'.$course_id.'/manage_lesson'); ?>"><?php echo get_phrase('manage_lesson'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="category-course-list-area">
    <div class="container">
        <div class="row">
          <div class="col-lg-5" style="padding: 35px 0px;" id = "add_form_area">
              <div class="content-box" id = "lesson_adding_form_area">
                  <form role="form" class="form-horizontal form-groups-bordered" action="<?php echo site_url('home/manage_lessons/edit/'.$course_id.'/'.$lesson_id); ?>" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                          <label class="col-lg-3 control-label"><?php echo get_phrase('title'); ?></label>
                          <div class="col-lg-12">
                              <input type="text" name = "title" class="form-control" required value="<?php echo $lesson_details['title']; ?>">
                          </div>
                      </div>
                      <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                      <div class="form-group">
                          <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
                          <div class="col-sm-12">
                              <select class="form-control" id="section_id" name="section_id" required>
                                <option value=""><?php echo get_phrase('select_a_section'); ?></option>
                                  <?php foreach ($sections as $section): ?>
                                      <option value="<?php echo $section['id']; ?>" <?php if($section['id'] == $lesson_details['section_id']) echo 'selected'; ?>><?php echo $section['title']; ?></option>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-sm-3 control-label"><?php echo get_phrase('lesson_type'); ?></label>
                          <div class="col-sm-12">
                              <select class="form-control" id="lesson_type" name="lesson_type" required onchange="show_lesson_type_form(this.value)">
                                <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
                                <option value="video-url" <?php if($lesson_details['attachment_type'] == 'url') echo 'selected'; ?>><?php echo get_phrase('video_url'); ?></option>
                                <option value="other-txt" <?php if($lesson_details['attachment_type'] == 'txt') echo 'selected'; ?>><?php echo get_phrase('text_file'); ?></option>
                                <option value="other-pdf" <?php if($lesson_details['attachment_type'] == 'pdf') echo 'selected'; ?>><?php echo get_phrase('pdf_file'); ?></option>
                                <option value="other-doc" <?php if($lesson_details['attachment_type'] == 'doc') echo 'selected'; ?>><?php echo get_phrase('document_file'); ?></option>
                                <option value="other-img" <?php if($lesson_details['attachment_type'] == 'img') echo 'selected'; ?>><?php echo get_phrase('image_file'); ?></option>
                              </select>
                          </div>
                      </div>

                      <div class="" id="video" <?php if($lesson_details['lesson_type'] != 'video'):?> style="display: none;" <?php endif; ?>>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('provider'); ?></label>
                            <div class="col-sm-12">
                              <select class="form-control" id="lesson_provider" name="lesson_provider" onchange="check_video_provider(this.value)">
                                <option value=""><?php echo get_phrase('select_lesson_provider'); ?></option>
                                <option value="youtube" <?php if(strtolower($lesson_details['video_type']) == 'youtube') echo 'selected'; ?>><?php echo get_phrase('youtube'); ?></option>
                                <option value="vimeo" <?php if(strtolower($lesson_details['video_type']) == 'vimeo') echo 'selected'; ?>><?php echo get_phrase('vimeo'); ?></option>
                                <option value="html5" <?php if(strtolower($lesson_details['video_type']) == 'html5') echo 'selected'; ?>>HTML5</option>
                              </select>
                            </div>
                        </div>

                        <div class="" id = "youtube_vimeo" <?php if(strtolower($lesson_details['video_type']) == 'vimeo' || strtolower($lesson_details['video_type']) == 'youtube'):?>  <?php else: ?> style="display: none;" <?php endif; ?>>
                          <div class="form-group">
                              <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                              <div class="col-sm-12">
                                  <input type="text" id = "video_url" name = "video_url" class="form-control"  onblur="ajax_get_video_details(this.value, 'addLesson')" value="<?php echo $lesson_details['video_url']; ?>">
                                  <label class="form-label" id = "invalid_url" style ="margin-top: 4px; color: red; display: none;"><?php echo get_phrase('invalid_url').'. '.get_phrase('your_video_source_has_to_be_either_youtube_or_vimeo'); ?></label>
                                  <label class="form-label" id = "preloader" style ="margin-top: 4px; color: #000; display: none;"><i class="fas fa-spinner fa-pulse" aria-hidden="true"></i> &nbsp;<?php echo get_phrase('analyzing_given_url').'....'; ?></label>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                              <div class="col-sm-12">
                                  <input type="text" name = "duration" id = "duration" class="form-control" value="<?php echo $lesson_details['duration']; ?>">
                              </div>
                          </div>
                        </div>

                        <div class="" id = "html5" <?php if($lesson_details['video_type'] != 'html5'): ?> style="display: none;" <?php endif; ?>>
                          <div class="form-group">
                              <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                              <div class="col-sm-12">
                                  <input type="text" id = "html5_video_url" name = "html5_video_url" class="form-control" value="<?php echo $lesson_details['video_url']; ?>">
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                              <div class="col-sm-12">
                                  <input type="text" name = "html5_duration" id = "html5_duration" class="form-control" value="<?php echo $lesson_details['duration']; ?>" placeholder="Eg. 00:04:36">
                              </div>
                          </div>
                        </div>
                      </div>

                      <div class="" id = "other" <?php if($lesson_details['lesson_type'] != 'other'):?> style="display: none;" <?php endif; ?>>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo get_phrase('attachment'); ?></label>
                            <div class="col-sm-12">
                                <input type="file" name = "attachment" class="form-control">
                            </div>
                        </div>
                      </div>


                      <div class="form-group">
                          <label class="col-sm-3 control-label"><?php echo get_phrase('thumbnail'); ?></label>
                          <div class="col-sm-12">
                              <input type="file" name = "thumbnail" class="form-control">
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-sm-3 control-label"><?php echo get_phrase('note'); ?>:</label>
                          <div class="col-sm-12">
                            <textarea class="form-control" name = "summary" id="summary"  rows="6"><?php echo $lesson_details['summary']; ?></textarea>
                          </div>
                      </div>


                      <div class="form-group">
                          <div class="col-sm-offset-3 col-sm-12">
                              <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('update_lesson'); ?></button>
                              <a href="<?php echo site_url('home/edit_course/'.$course_id.'/manage_lesson') ?>" class = "btn btn-success" name="button" style="color: white;"><?php echo get_phrase('cancel'); ?></a>
                          </div>
                      </div>
                  </form>
              </div>
          </div>

          <div class="col-lg-7" style="padding: 35px 0px;">
              <div class="content-box">
                  <table class="table">
                      <thead>
                        <tr>
                          <th><?php echo get_phrase('title'); ?></th>
                          <th><?php echo get_phrase('section'); ?></th>
                          <th><?php echo get_phrase('lesson_type'); ?></th>
                          <th><?php echo get_phrase('action'); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($lessons->result_array() as $lesson):?>
                              <tr>
                                  <td>
                                      <?php echo $lesson['title']; ?>
                                  </td>
                                  <td>
                                      <?php
                                          $section_of_lesson = $this->crud_model->get_section('section', $lesson['section_id'])->row_array();
                                          echo $section_of_lesson['title'];
                                       ?>
                                  </td>
                                  <td>
                                    <?php
                                      if ($lesson['attachment_type'] == 'url' || $lesson['attachment_type'] == '') {
                                        echo get_phrase('video');
                                      }else {
                                        echo ucfirst($lesson['attachment_type']);
                                      }
                                    ?>
                                  </td>

                                  <td>
                                      <a href="<?php echo site_url('home/lesson_editing_form/'.$lesson['id'].'/'.$course_id); ?>">
                                          <?php echo get_phrase('edit');?>
                                      </a>
                                      <a href="#" onclick="confirm_modal('<?php echo site_url('home/manage_lessons/delete/'.$course_id.'/'.$lesson['id']); ?>');">
                                          <?php echo get_phrase('delete');?>
                                      </a>
                                  </td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>
                    </table>
              </div>
          </div>
        </div>
    </div>
</section>
<script type="text/javascript">

    function ajax_get_video_details(video_url, type) {

        if (type == 'addLesson')
            $('#preloader').show();
        else
            $('#preloader_for_updating').show();

        if(checkURLValidity(video_url)){
            $.ajax({
                url: '<?php echo site_url('admin/ajax_get_video_details');?>',
                type : 'POST',
                data : {video_url : video_url},
                success: function(response)
                {
                    if (type == 'addLesson') {
                        jQuery('#duration').val(response);
                        $('#preloader').hide();
                        $('#invalid_url').hide();
                    }else {
                        jQuery('#duration_for_updating').val(response);
                        $('#preloader_for_updating').hide();
                        $('#invalid_url_for_updating').hide();
                    }
                }
            });
        }else {
            if (type == 'addLesson') {
                $('#preloader').hide();
                $('#invalid_url').show();
                jQuery('#duration').val('');
            }else {
                $('#preloader_for_updating').hide();
                $('#invalid_url_for_updating').show();
                jQuery('#duration_for_updating').val('');
            }
        }
    }

    function checkURLValidity(video_url) {
        var youtubePregMatch = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
        var vimeoPregMatch = /^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/;
        if (video_url.match(youtubePregMatch)) {
            return true;
        }
        else if (vimeoPregMatch.test(video_url)) {
            return true;
        }
        else {
            return false;
        }
    }


    function show_lesson_type_form(param) {
      var checker = param.split('-');
      var lesson_type = checker[0];
      if (lesson_type === "video") {
          $('#other').hide();
          $('#video').show();
      }else if (lesson_type === "other") {
          $('#video').hide();
          $('#other').show();
      }else {
        $('#video').hide();
        $('#other').hide();
      }
    }

    function check_video_provider(provider) {
      if (provider === 'youtube' || provider === 'vimeo') {
        $('#html5').hide();
        $('#youtube_vimeo').show();
      }else if(provider === 'html5'){
        $('#youtube_vimeo').hide();
        $('#html5').show();
      }else {
        $('#youtube_vimeo').hide();
        $('#html5').hide();
      }
    }
</script>
