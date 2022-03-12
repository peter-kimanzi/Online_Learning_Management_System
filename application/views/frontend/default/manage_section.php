<div class="col-lg-5" style="padding: 35px;">
    <div class="content-box" id = "add_form_area">
        <form action="<?php echo site_url('home/sections/add/'.$course_id); ?>" id = "ajaxFormForAdding" onsubmit="addSection(event)" method="post" enctype="multipart/form-data">
            <div class="basic-group">
                <div class="form-group">
                    <label for="title"><?php echo get_phrase('title'); ?>:</label>
                    <input type="text" class="form-control" name = "title" id="title" placeholder="<?php echo get_phrase('title'); ?>" required>
                </div>
            </div>
            <div class="content-update-box">
                <button type="submit" name = "submit" class="btn col-4"><?php echo get_phrase('add_section'); ?></button>
            </div>
        </form>
    </div>
    <div class="content-box" id = "edit_form_area" style="display: none;">
        <form action="" id = "ajaxFormForUpdating" onsubmit="updateSection(event)" method="post" enctype="multipart/form-data">
            <div class="basic-group">
                <div class="form-group">
                    <label for="title"><?php echo get_phrase('title'); ?>:</label>
                    <input type="text" class="form-control" name = "title" id="section_title_for_editing" placeholder="<?php echo get_phrase('title'); ?>" required>
                </div>
            </div>
            <div class="content-update-box">
                <button type="submit" name = "submit" class="btn col-4"><?php echo get_phrase('update_section'); ?></button>
                <button type="button" name = "reset" class="btn col-4" onclick="resetForm()"><?php echo get_phrase('reset'); ?></button>
            </div>
        </form>
        <input type="hidden" name="section_id" id = "section_id_for_editing" value="">
    </div>
</div>
<div class="col-lg-7" style="padding: 35px;">
    <div class="content-box" id = "reload_section">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo get_phrase('title'); ?></th>
                    <th><?php echo get_phrase('lessons'); ?></th>
                    <th><?php echo get_phrase('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sections = json_decode($course_details['section'], true);
                    if (sizeof($sections) > 0): ?>
                    <?php foreach ($sections as $section):
                        $section_details = $this->crud_model->get_section('section', $section)->row_array();
                        ?>
                        <tr>
                            <td><?php echo $section_details['title']; ?></td>
                            <td>
                                <ul style="list-style-type:square">
                                    <?php
                                    $lessons = $this->crud_model->get_lessons('section', $section_details['id'])->result_array();
                                    foreach ($lessons as $lesson):?>
                                    <a href="<?php echo site_url('admin/watch_video/'.slugify($lesson['title']).'/'.$lesson['id']); ?>"><li><?php echo $lesson['title']; ?></li></a>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <a href="#" onclick="addNameAndIdOnForm('<?php echo $section ?>', '<?php echo $section_details['title'] ?>')"><?php echo get_phrase('edit'); ?></a>
                            <a href="#" onclick="confirm_modal('<?php echo site_url('home/sections/delete/'.$course_id.'/'.$section); ?>');"><?php echo get_phrase('delete'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="dd">
        <ol class="dd-list">
            <?php if (sizeof($sections) > 0): ?>
                <?php foreach ($sections as $section):
                    $section_details = $this->crud_model->get_section('section', $section)->row_array();?>
                    <li class="dd-item" data-id="<?php echo $section; ?>">
                        <div class="dd-handle">
                            <?php echo $section_details['title']; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ol>
    </div>
    <input type="hidden" id = "serialization" name="" value="">
    <input type="hidden" id = "ajax_url_for_serialization" name="" value="<?php echo site_url("home/sections/serialize_section/$course_id"); ?>">
    </div>
    </div>


    <script type="text/javascript">
        function addSection(event) {
            var formData = $('#ajaxFormForAdding').serializeArray();
            event.preventDefault();
            jQuery(formData).each(function(i, item){
                var url = "<?php echo site_url('home/sections/add/'.$course_id); ?>";
                $.post(url, {'title' : item.value, 'course_id' : '<?php echo $course_id; ?>'}, function(response){
                    $('#reload_section').html(response);
                });
            })
        }

        function updateSection(event) {
            var formData = $('#ajaxFormForUpdating').serializeArray();
            event.preventDefault();
            jQuery(formData).each(function(i, item){
                var url = "<?php echo site_url('home/sections/edit/').$course_id.'/'; ?>" + $('#section_id_for_editing').val();
                $.post(url, {'title' : item.value, 'course_id' : '<?php echo $course_id; ?>'}, function(response){
                    $('#reload_section').html(response);
                });
                //console.log(url);
            });
            //console.log(formData);
        }

        function addNameAndIdOnForm(section_id, section_title) {
            $('#edit_form_area').show();
            $('#section_id_for_editing').val(section_id);
            $('#section_title_for_editing').val(section_title);
            $('#add_form_area').hide();
        }

        function resetForm() {
            $('#edit_form_area').hide();
            $('#add_form_area').show();
        }
    </script>
