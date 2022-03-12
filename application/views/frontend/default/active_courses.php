<div class="row no-gutters" id = "my_courses_area">
    <?php
     foreach ($my_courses['active']->result_array() as $my_course):
        $course_details = $this->crud_model->get_course_by_id($my_course['id'])->row_array();?>

        <div class="col-lg-3">
            <div class="course-box-wrap">
                    <div class="course-box">
                        <div class="course-image">
                            <img src="<?php echo $this->crud_model->get_course_thumbnail_url($my_course['id']); ?>" alt="" class="img-fluid">
                        </div>
                        <div class="course-details">
                            <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$my_course['id']); ?>"><h5 class="title"><?php echo $course_details['title']; ?></h5></a>
                        </div>
                        <div class="row" style="padding: 5px;">
                            <div class="col-md-12" style="margin-bottom: 5px;">
                                <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$my_course['id']); ?>" class="btn btn-block"><?php echo get_phrase('course_details'); ?></a>
                            </div>

                            <div class="col-md-12" style="margin-bottom: 5px;">
                                 <a href="<?php echo site_url('home/lesson/'.slugify($course_details['title']).'/'.$my_course['id']); ?>" class="btn btn-block"><?php echo get_phrase('view_lessons'); ?></a>
                            </div>

                            <div class="col-md-12" style="margin-bottom: 5px;">
                                 <a href="<?php echo site_url('home/edit_course/'.$my_course['id']); ?>" class="btn btn-block"><?php echo get_phrase('edit_course'); ?></a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
