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


<script src="<?php echo base_url().'assets/frontend/js/custom.js'; ?>"></script>
