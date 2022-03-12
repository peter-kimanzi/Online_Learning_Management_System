<div class="col-lg-9">

    <div class="in-cart-box">
        <div class="title"><?php echo sizeof($this->session->userdata('cart_items')).' '.get_phrase('courses_in_cart'); ?></div>
        <div class="">
            <ul class="cart-course-list">
                <?php
                    $actual_price = 0;
                    $total_price  = 0;
                    foreach ($this->session->userdata('cart_items') as $cart_item):
                    $course_details = $this->crud_model->get_course_by_id($cart_item)->row_array();
                    $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
                    ?>
                    <li>
                        <div class="cart-course-wrapper">
                            <div class="image">
                                <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']); ?>">
                                    <img src="<?php echo $this->crud_model->get_course_thumbnail_url($cart_item);?>" alt="" class="img-fluid">
                                </a>
                            </div>
                            <div class="details">
                                <a href="<?php echo site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']); ?>">
                                    <div class="name"><?php echo $course_details['title']; ?></div>
                                </a>
                                <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>">
                                    <div class="instructor">
                                        <?php echo get_phrase('by'); ?>
                                        <span class="instructor-name"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></span>,
                                    </div>
                                </a>
                            </div>
                            <div class="move-remove">
                                <div id = "<?php echo $course_details['id']; ?>" onclick="removeFromCartList(this)"><?php echo get_phrase('remove'); ?></div>
                                <!-- <div>Move to Wishlist</div> -->
                            </div>
                            <div class="price">
                                <a href="">
                                    <?php if ($course_details['discount_flag'] == 1): ?>
                                        <div class="current-price">
                                            <?php
                                                $total_price += $course_details['discounted_price'];
                                                echo currency($course_details['discounted_price']);
                                             ?>
                                        </div>
                                        <div class="original-price">
                                            <?php
                                                $actual_price += $course_details['price'];
                                                echo currency($course_details['price']);
                                             ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="current-price">
                                            <?php
                                                $actual_price += $course_details['price'];
                                                $total_price  += $course_details['price'];
                                                echo currency($course_details['price']);
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="coupon-tag">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>
<div class="col-lg-3">
    <div class="cart-sidebar">
        <div class="total"><?php echo get_phrase('total'); ?>:</div>
        <span id = "total_price_of_checking_out" hidden><?php echo $total_price; ?></span>
        <div class="total-price"><?php echo currency($total_price); ?></div>
        <div class="total-original-price">
            <span class="original-price"><?php echo currency($actual_price); ?></span>
            <!-- <span class="discount-rate">95% off</span> -->
        </div>
        <button type="button" class="btn btn-primary btn-block checkout-btn" onclick="handleCheckOut()"><?php echo get_phrase('checkout'); ?></button>
    </div>
</div>
<script type="text/javascript">
function handleCheckOut() {
    $.ajax({
        url: '<?php echo site_url('home/isLoggedIn');?>',
        success: function(response)
        {
            if (!response) {
                window.location.replace("<?php echo site_url('login'); ?>");
            }else {
                $('#paymentModal').modal('show');
                $('.total_price_of_checking_out').val($('#total_price_of_checking_out').text());
            }
        }
    });
}
</script>
