<?php
$user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
?>
<section class="menu-area">
    <div class="container-xl">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">

                    <ul class="mobile-header-buttons">
                        <li><a class="mobile-nav-trigger" href="#mobile-primary-nav">Menu<span></span></a></li>
                        <li><a class="mobile-search-trigger" href="#mobile-search">Search<span></span></a></li>
                    </ul>

                    <a href="<?php echo site_url(''); ?>" class="navbar-brand" href="#">
                        <img src="<?php echo base_url().'uploads/system/logo-dark.png'; ?>" alt="" height="35">
                    </a>

                    <?php include 'menu.php'; ?>


                    <form class="inline-form" action="<?php echo site_url('home/search'); ?>" method="get" style="width: 100%;">
                        <div class="input-group search-box mobile-search">
                            <input type="text" name = 'query' class="form-control" placeholder="<?php echo get_phrase('search_for_courses'); ?>">
                            <div class="input-group-append">
                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <?php if (get_settings('allow_instructor') == 1): ?>
                        <div class="instructor-box menu-icon-box">
                            <div class="icon">
                                <a href="<?php echo site_url('user'); ?>" style="border: 1px solid transparent; margin: 10px 10px; font-size: 14px; width: 100%; border-radius: 0;"><?php echo get_phrase('instructor'); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="instructor-box menu-icon-box">
                        <div class="icon">
                            <a href="<?php echo site_url('home/my_courses'); ?>" style="border: 1px solid transparent; margin: 10px 10px; font-size: 14px; width: 100%; border-radius: 0; min-width: 100px;"><?php echo get_phrase('my_courses'); ?></a>
                        </div>
                    </div>

                    <div class="wishlist-box menu-icon-box" id = "wishlist_items">
                        <?php include 'wishlist_items.php'; ?>
                    </div>

                    <div class="cart-box menu-icon-box" id = "cart_items">
                        <?php include 'cart_items.php'; ?>
                    </div>

                    <?php //include 'notifications.php'; ?>


                    <div class="user-box menu-icon-box">
                        <div class="icon">
                            <a href="javascript::">
                                <?php
                                if (file_exists('uploads/user_image/'.$user_details['id'].'.jpg')): ?>
                                <img src="<?php echo base_url().'uploads/user_image/'.$user_details['id'].'.jpg';?>" alt="" class="img-fluid">
                            <?php else: ?>
                                <img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="" class="img-fluid">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="dropdown user-dropdown corner-triangle top-right">
                        <ul class="user-dropdown-menu">

                            <li class="dropdown-user-info">
                                <a href="">
                                    <div class="clearfix">
                                        <div class="user-image float-left">
                                            <?php if (file_exists('uploads/user_image/'.$user_details['id'].'.jpg')): ?>
                                                <img src="<?php echo base_url().'uploads/user_image/'.$user_details['id'].'.jpg';?>" alt="" class="img-fluid">
                                            <?php else: ?>
                                                <img src="<?php echo base_url().'uploads/user_image/placeholder.png';?>" alt="" class="img-fluid">
                                            <?php endif; ?>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">
                                                <span class="hi"><?php echo get_phrase('hi'); ?>,</span>
                                                <?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>
                                            </div>
                                            <div class="user-email">
                                                <span class="email"><?php echo $user_details['email']; ?></span>
                                                <span class="welcome"><?php echo get_phrase("welcome_back"); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li class="user-dropdown-menu-item"><a href="<?php echo site_url('home/my_courses'); ?>"><i class="far fa-gem"></i><?php echo get_phrase('my_courses'); ?></a></li>
                            <li class="user-dropdown-menu-item"><a href="<?php echo site_url('home/my_wishlist'); ?>"><i class="far fa-heart"></i><?php echo get_phrase('my_wishlist'); ?></a></li>
                            <li class="user-dropdown-menu-item"><a href="<?php echo site_url('home/my_messages'); ?>"><i class="far fa-envelope"></i><?php echo get_phrase('my_messages'); ?></a></li>
                            <li class="user-dropdown-menu-item"><a href="<?php echo site_url('home/purchase_history'); ?>"><i class="fas fa-shopping-cart"></i><?php echo get_phrase('purchase_history'); ?></a></li>
                            <li class="user-dropdown-menu-item"><a href="<?php echo site_url('home/profile/user_profile'); ?>"><i class="fas fa-user"></i><?php echo get_phrase('user_profile'); ?></a></li>
                            <li class="dropdown-user-logout user-dropdown-menu-item"><a href="<?php echo site_url('login/logout/user'); ?>"><?php echo get_phrase('log_out'); ?></a></li>
                        </ul>
                    </div>
                </div>



                <span class="signin-box-move-desktop-helper"></span>
                <div class="sign-in-box btn-group d-none">

                    <button type="button" class="btn btn-sign-in" data-toggle="modal" data-target="#signInModal">Log In</button>

                    <button type="button" class="btn btn-sign-up" data-toggle="modal" data-target="#signUpModal">Sign Up</button>

                </div> <!--  sign-in-box end -->


            </nav>
        </div>
    </div>
</div>
</section>
