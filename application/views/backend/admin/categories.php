<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('categories'); ?>
                  <a href="<?php echo site_url('admin/category_form/add_category'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_category'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <?php foreach ($categories->result_array() as $category):
        if($category['parent'] > 0)
         continue;
         $sub_categories = $this->crud_model->get_sub_categories($category['id']); ?>
         <div class="col-md-6 col-lg-6 col-xl-4 on-hover-action" id = "<?php echo $category['id']; ?>">
             <div class="card d-block">
                 <img class="card-img-top" src="<?php echo base_url('uploads/thumbnails/category_thumbnails/'.$category['thumbnail']); ?>" alt="Card image cap">
                 <div class="card-body">
                     <h4 class="card-title mb-0"><i class="<?php echo $category['font_awesome_class']; ?>"></i> <?php echo $category['name']; ?></h4>
                     <small style="font-style: italic;"><p class="card-text"><?php echo count($sub_categories).' '.get_phrase('sub_categories'); ?></p></small>
                 </div>

                 <ul class="list-group list-group-flush">
                     <?php foreach ($sub_categories as $sub_category): ?>
                        <li class="list-group-item on-hover-action" id = "<?php echo $sub_category['id']; ?>">
                            <span><i class="<?php echo $sub_category['font_awesome_class']; ?>"></i> <?php echo $sub_category['name']; ?></span>
                            <span class="category-action" id = 'category-delete-btn-<?php echo $sub_category['id']; ?>' style="float: right; margin-left: 5px; display: none; height: 20px;">
                                <a href="javascript::" class="action-icon" onclick="confirm_modal('<?php echo site_url('admin/categories/delete/'.$sub_category['id']); ?>');"> <i class="mdi mdi-delete" style="font-size: 18px;"></i></a>
                            </span>
                            <span class="category-action" id = 'category-edit-btn-<?php echo $sub_category['id']; ?>' style="float: right; display: none; height: 20px;">
                                <a href="<?php echo site_url('admin/category_form/edit_category/'.$sub_category['id']); ?>" class="action-icon"> <i class="mdi mdi-pencil" style="font-size: 18px;"></i></a>
                            </span>
                        </li>
                     <?php endforeach; ?>
                 </ul>
                 <div class="card-body">
                     <a href = "<?php echo site_url('admin/category_form/edit_category/'.$category['id']); ?>" class="btn btn-icon btn-outline-info btn-sm" id = "category-edit-btn-<?php echo $category['id']; ?>" style="display: none;" style="margin-right:5px;">
                         <i class="mdi mdi-wrench"></i> <?php echo get_phrase('edit'); ?>
                     </a>
                     <a href = "#" class="btn btn-icon btn-outline-danger btn-sm" id = "category-delete-btn-<?php echo $category['id']; ?>"style="float: right; display: none;" onclick="confirm_modal('<?php echo site_url('admin/categories/delete/'.$category['id']); ?>');" style="margin-right:5px;">
                         <i class="mdi mdi-delete"></i> <?php echo get_phrase('delete'); ?>
                     </a>
                 </div> <!-- end card-body-->
             </div> <!-- end card-->
         </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    $('.on-hover-action').mouseenter(function() {
        var id = this.id;
        $('#category-delete-btn-'+id).show();
        $('#category-edit-btn-'+id).show();
    });
    $('.on-hover-action').mouseleave(function() {
        var id = this.id;
        $('#category-delete-btn-'+id).hide();
        $('#category-edit-btn-'+id).hide();
    });
</script>
