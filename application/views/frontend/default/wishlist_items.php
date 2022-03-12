<?php
$user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
$cart_items = $this->session->userdata('cart_items');
?>
<div class="icon">
    <a href=""><i class="far fa-heart"></i></a>
    <span class="number"><?php echo sizeof($this->crud_model->getWishLists()); ?></span>
</div>
<div class="dropdown course-list-dropdown corner-triangle top-right">
    <div class="list-wrapper">
        <div class="item-list">
            <ul>
                <?php
                foreach (json_decode($user_details['wishlist']) as $wishlist):
                    $course_details = $this->crud_model->get_course_by_id($wishlist)->row_array();
                    $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
                    ?>
                    <li>
                        <div class="item clearfix">
                            <div class="item-image">
                                <a href="">
                                    <img src="<?php echo $this->crud_model->get_course_thumbnail_url($wishlist);?>" alt="" class="img-fluid">
                                </a>
                            </div>
                            <div class="item-details">
                                <a href="">
                                    <div class="course-name"><?php echo $course_details['title']; ?></div>
                                    <div class="instructor-name">
                                        <?php echo get_phrase('by').' '.$instructor_details['first_name'].' '.$instructor_details['last_name']; ?>
                                    </div>
                                    <div class="item-price">
                                        <?php if ($course_details['discount_flag'] == 1): ?>
                                            <span class="current-price"><?php echo currency($course_details['discounted_price']); ?></span>
                                            <span class="original-price"><?php echo currency($course_details['price']); ?></span>
                                        <?php else: ?>
                                            <span class="current-price"><?php echo currency($course_details['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <button type="button" id = "<?php echo $course_details['id']; ?>" onclick="handleCartItems(this)" class = "<?php if(in_array($course_details['id'], $cart_items)) echo 'addedToCart'; ?>">
                                  <?php
                                    if(in_array($course_details['id'], $cart_items)){
                                      echo get_phrase('added_to_cart');
                                    }else {
                                        echo get_phrase('add_to_cart');
                                    }
                                  ?>
                                </button>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="dropdown-footer">
            <a href = "<?php echo site_url('home/my_wishlist'); ?>"><?php echo get_phrase('go_to_wishlist'); ?></a>
        </div>
    </div>
    <div class="empty-box text-center d-none">
        <p>Your wishlist is empty.</p>
        <a href="">Explore Courses</a>
    </div>
</div>
