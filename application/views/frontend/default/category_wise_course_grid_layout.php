<div class="row">
  <?php foreach($courses as $course):
   $instructor_details = $this->user_model->get_all_user($course['user_id'])->row_array();?>
   <div class="col-md-4 col-lg-4">
     <div class="course-box-wrap">
         <a href="<?php echo site_url('home/course/'.slugify($course['title']).'/'.$course['id']); ?>">
             <div class="course-box">
                 <div class="course-image">
                     <img src="<?php echo $this->crud_model->get_course_thumbnail_url($course['id']); ?>" alt="" class="img-fluid">
                 </div>
                 <div class="course-details">
                     <h5 class="title"><?php echo $course['title']; ?></h5>
                     <p class="instructors">
                         <?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?>
                     </p>
                     <div class="rating">
                         <?php
                         $total_rating =  $this->crud_model->get_ratings('course', $course['id'], true)->row()->rating;
                         $number_of_ratings = $this->crud_model->get_ratings('course', $course['id'])->num_rows();
                         if ($number_of_ratings > 0) {
                             $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                         }else {
                             $average_ceil_rating = 0;
                         }

                         for($i = 1; $i < 6; $i++):?>
                         <?php if ($i <= $average_ceil_rating): ?>
                             <i class="fas fa-star filled"></i>
                         <?php else: ?>
                             <i class="fas fa-star"></i>
                         <?php endif; ?>
                     <?php endfor; ?>
                     <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span>
                 </div>
                 <?php if ($course['is_free_course'] == 1): ?>
                     <p class="price text-right"><?php echo get_phrase('free'); ?></p>
                 <?php else: ?>
                     <?php if ($course['discount_flag'] == 1): ?>
                         <p class="price text-right"><small><?php echo currency($course['price']); ?></small><?php echo currency($course['discounted_price']); ?></p>
                     <?php else: ?>
                         <p class="price text-right"><?php echo currency($course['price']); ?></p>
                     <?php endif; ?>
                 <?php endif; ?>
             </div>
         </div>
     </a>
     </div>
   </div>
 <?php endforeach; ?>
</div>
