<?php
if (sizeof($my_courses) > 0):
  foreach ($my_courses as $my_course):
          $instructor_details = $this->user_model->get_all_user($my_course['user_id'])->row_array();?>
      <div class="col-lg-3">
          <div class="course-box-wrap">
                <div class="course-box">
                    <div class="course-image">
                        <img src="<?php echo $this->crud_model->get_course_thumbnail_url($my_course['id']); ?>" alt="" class="img-fluid">
                        <div class="instructor-img-hover">
                            <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>"><img src="<?php echo $this->user_model->get_user_image_url($instructor_details['id']);?>" alt=""></a>
                            <span>
                                <?php
                                    $lessons = $this->crud_model->get_lessons('course', $my_course['id'])->num_rows();
                                    echo $lessons.' '.get_phrase('lessons');
                                ?>
                            </span>
                            <span>
                                <?php
                                    echo $this->crud_model->get_total_duration_of_lesson_by_course_id($my_course['id']);
                                ?>
                            </span>
                        </div>
                        <div class="wishlist-add wishlisted">
                            <button type="button" data-toggle="tooltip" data-placement="left" title="" style="cursor : auto;" onclick="handleWishList(this)" id = "<?php echo $my_course['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="course-details">
                        <h5 class="title"><?php echo $my_course['title']; ?></h5>
                        <p class="instructors"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></p>
                        <!-- <div class="rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star half-filled"></i>
                            <i class="fas fa-star"></i>
                            <p class="d-inline-block rating-number">4.5<span>(23,990)</span></p>
                        </div> -->
                        <?php if ($my_course['discount_flag'] == 1): ?>
                            <p class="price text-right"><small><?php echo currency($my_course['price']); ?></small><?php echo currency($my_course['discounted_price']); ?></p>
                        <?php else: ?>
                            <p class="price text-right"><?php echo currency($my_course['price']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
          </div>
      </div>
  <?php endforeach; ?>
<?php endif; ?>
