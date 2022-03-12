<select class="form-control" id="sub_category_id" name="sub_category_id" required>
    <option value=""><?php echo get_phrase('select_category'); ?></option>
    <?php foreach ($sub_categories as $sub_category): ?>
        <option value="<?php echo $sub_category['id']; ?>"><?php echo $sub_category['name']; ?></option>
    <?php endforeach; ?>

</select>
