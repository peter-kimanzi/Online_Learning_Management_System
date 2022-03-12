<?php
    $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
    $sections = $this->crud_model->get_section('course', $course_id)->result_array();
    $lessons = $this->crud_model->get_lessons('course', $course_id);
?>
<div class="col-lg-5" style="padding: 35px 0px;" id = "add_form_area">
    <div class="content-box" id = "lesson_adding_form_area">
        <form role="form" class="form-horizontal form-groups-bordered" action="<?php echo site_url('home/manage_lessons/add/'.$course_id); ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?php echo get_phrase('title'); ?></label>
                <div class="col-lg-12">
                    <input type="text" name = "title" class="form-control" required>
                </div>
            </div>
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
                <div class="col-sm-12">
                    <select class="form-control" id="section_id" name="section_id" required>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('lesson_type'); ?></label>
                <div class="col-sm-12">
                    <select class="form-control" id="lesson_type" name="lesson_type" required onchange="show_lesson_type_form(this.value)">
                        <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
                        <option value="video-url"><?php echo get_phrase('video_url'); ?></option>
                        <option value="other-txt"><?php echo get_phrase('text_file'); ?></option>
                        <option value="other-pdf"><?php echo get_phrase('pdf_file'); ?></option>
                        <option value="other-doc"><?php echo get_phrase('document_file'); ?></option>
                        <option value="other-img"><?php echo get_phrase('image_file'); ?></option>
                    </select>
                </div>
            </div>

            <div class="" id="video" style="display: none;">
              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo get_phrase('provider'); ?></label>
                  <div class="col-sm-12">
                    <select class="form-control" id="lesson_provider" name="lesson_provider" onchange="check_video_provider(this.value)">
                        <option value=""><?php echo get_phrase('select_lesson_provider'); ?></option>
                        <option value="youtube"><?php echo get_phrase('youtube'); ?></option>
                        <option value="vimeo"><?php echo get_phrase('vimeo'); ?></option>
                        <option value="html5">HTML5</option>
                    </select>
                  </div>
              </div>

              <div class="" id = "youtube_vimeo" style="display: none;">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                    <div class="col-sm-12">
                        <input type="text" id = "video_url" name = "video_url" class="form-control"  onblur="ajax_get_video_details(this.value, 'addLesson')" >
                        <label class="form-label" id = "invalid_url" style ="margin-top: 4px; color: red; display: none;"><?php echo get_phrase('invalid_url').'. '.get_phrase('your_video_source_has_to_be_either_youtube_or_vimeo'); ?></label>
                        <label class="form-label" id = "preloader" style ="margin-top: 4px; color: #000; display: none;"><i class="fas fa-spinner fa-pulse" aria-hidden="true"></i> &nbsp;<?php echo get_phrase('analyzing_given_url').'....'; ?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                    <div class="col-sm-12">
                        <input type="text" name = "duration" id = "duration" class="form-control" >
                    </div>
                </div>
              </div>

              <div class="" id = "html5" style="display: none;">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                    <div class="col-sm-12">
                        <input type="text" id = "html5_video_url" name = "html5_video_url" class="form-control" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                    <div class="col-sm-12">
                        <input type="text" name = "html5_duration" id = "html5_duration" class="form-control" placeholder="Eg. 00:04:36">
                    </div>
                </div>
              </div>
            </div>

            <div class="" id = "other" style="display: none;">
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
                  <textarea class="form-control" name = "summary" id="summary"  rows="6"></textarea>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-12">
                    <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('add_lesson'); ?></button>
                </div>
            </div>
        </form>
    </div>

    <div class="content-box" id = "lesson_editing_form_area" style="display: none;">
        <form role="form" id = "lesson_editing_form" class="form-horizontal form-groups-bordered" action="" method="post" enctype="multipart/form-data">

          <div class="form-group">
              <label class="col-lg-3 control-label"><?php echo get_phrase('title'); ?></label>
              <div class="col-lg-12">
                  <input type="text" name = "title" id = 'title_for_updating' class="form-control" required>
              </div>
          </div>
          <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
          <div class="form-group">
              <label class="col-sm-3 control-label"><?php echo get_phrase('section'); ?></label>
              <div class="col-sm-12">
                  <select class="form-control" id="section_id_for_updating" name="section_id" required>
                    <option value=""><?php echo get_phrase('select_a_section'); ?></option>
                      <?php foreach ($sections as $section): ?>
                          <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label"><?php echo get_phrase('lesson_type'); ?></label>
              <div class="col-sm-12">
                  <select class="form-control" id="lesson_type_for_updating" name="lesson_type" required onchange="show_lesson_type_form_on_lesson_edit(this.value)">
                      <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
                      <option value="video-url"><?php echo get_phrase('video_url'); ?></option>
                      <option value="other-txt"><?php echo get_phrase('text_file'); ?></option>
                      <option value="other-pdf"><?php echo get_phrase('pdf_file'); ?></option>
                      <option value="other-doc"><?php echo get_phrase('document_file'); ?></option>
                      <option value="other-img"><?php echo get_phrase('image_file'); ?></option>
                  </select>
              </div>
          </div>

          <div class="" id="video" style="display: none;">
            <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo get_phrase('provider'); ?></label>
                <div class="col-sm-12">
                  <select class="form-control" id="lesson_provider_for_updating" name="lesson_provider" onchange="check_video_provider_on_lesson_edit(this.value)">
                      <option value=""><?php echo get_phrase('select_lesson_provider'); ?></option>
                      <option value="youtube"><?php echo get_phrase('youtube'); ?></option>
                      <option value="vimeo"><?php echo get_phrase('vimeo'); ?></option>
                      <option value="html5">HTML5</option>
                  </select>
                </div>
            </div>

            <div class="" id = "youtube_vimeo_for_updating" style="display: none;">
              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                  <div class="col-sm-12">
                      <input type="text" id = "video_url_for_updating" name = "video_url" class="form-control"  onblur="ajax_get_video_details(this.value, 'editLesson')" >
                      <label class="form-label" id = "invalid_url_for_updating" style ="margin-top: 4px; color: red; display: none;"><?php echo get_phrase('invalid_url').'. '.get_phrase('your_video_source_has_to_be_either_youtube_or_vimeo'); ?></label>
                      <label class="form-label" id = "preloader_for_updating" style ="margin-top: 4px; color: #000; display: none;"><i class="fas fa-spinner fa-pulse" aria-hidden="true"></i> &nbsp;<?php echo get_phrase('analyzing_given_url').'....'; ?></label>
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                  <div class="col-sm-12">
                      <input type="text" name = "duration" id = "duration_for_updating" class="form-control" >
                  </div>
              </div>
            </div>

            <div class="" id = "html5_for_updating" style="display: none;">
              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo get_phrase('video_url'); ?></label>
                  <div class="col-sm-12">
                      <input type="text" id = "html5_video_url_for_updating" name = "html5_video_url" class="form-control" >
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo get_phrase('duration'); ?></label>
                  <div class="col-sm-12">
                      <!-- <input type="text" name = "html5_duration" id = "html5_duration_for_updating" class="form-control" > -->
                      <!-- <input type="text" class="form-control timepicker" name="html5_duration" id="html5_duration" data-template="dropdown" data-show-seconds="true" data-default-time="00:00:05" data-show-meridian="false" data-minute-step="1" data-second-step="1"/> -->
                  </div>
              </div>
            </div>
          </div>

          <div class="" id = "other_for_updating" style="display: none;">
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
                <textarea class="form-control" name = "summary" id="summary_for_updating"  rows="6"></textarea>
              </div>
          </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-12">
                    <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('update_lesson'); ?></button>
                    <button class = "btn btn-success" type="button" name="button" onclick="resetForm()"><?php echo get_phrase('reset'); ?></button>
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

    function makeUpdateFormVisible(lesson_id, title, videoURL, duration) {
        var action = '<?php echo site_url('home/manage_lessons/edit/'.$course_id.'/'); ?>' + lesson_id;
        $('#lesson_editing_form').attr("action", action);
        $('#lesson_editing_form_area').show();
        $('#lesson_adding_form_area').hide();
        $('#title_for_updating').val(title);
        $('#video_url_for_updating').val(videoURL);
        $('#duration_for_updating').val(duration);
    }

    function resetForm() {
        $('#lesson_editing_form_area').hide();
        $('#lesson_adding_form_area').show();
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

    function show_lesson_type_form_on_lesson_edit(param) {
      var checker = param.split('-');
      var lesson_type = checker[0];
      if (lesson_type === "video") {
          $('#other_for_updating').hide();
          $('#video_for_updating').show();
      }else if (lesson_type === "other") {
          $('#video_for_updating').hide();
          $('#other_for_updating').show();
      }else {
        $('#video_for_updating').hide();
        $('#other_for_updating').hide();
      }
    }

    function check_video_provider_on_lesson_edit(provider) {
      if (provider === 'youtube' || provider === 'vimeo') {
        $('#html5_for_updating').hide();
        $('#youtube_vimeo_for_updating').show();
      }else if(provider === 'html5'){
        $('#youtube_vimeo_for_updating').hide();
        $('#html5_for_updating').show();
      }else {
        $('#youtube_vimeo_for_updating').hide();
        $('#html5_for_updating').hide();
      }
    }
</script>
