<select class="form-control select2" id="section_id" name="section_id" data-init-plugin="select2" required>
    <option value=""><?php echo get_phrase('select_section'); ?></option>
    <?php foreach ($sections as $section): ?>
        <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
    <?php endforeach; ?>
    
</select>
