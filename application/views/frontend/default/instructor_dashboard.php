<?php
    $my_courses = $this->crud_model->get_courses_by_user_id($this->session->userdata('user_id'));
?>
<section class="page-header-area my-course-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="page-title"><?php echo $page_title; ?></h1>
                <ul>
                    <li class="<?php if($type == 'active') echo "active"; ?>"><a href="<?php echo site_url('home/dashboard/active'); ?>"><?php echo get_phrase('active_courses'); ?> ( <?php echo $my_courses['active']->num_rows(); ?> )</a></li>
                    <li class="<?php if($type == 'pending') echo "active"; ?>"><a href="<?php echo site_url('home/dashboard/pending'); ?>"><?php echo get_phrase('pending_courses'); ?> ( <?php echo $my_courses['pending']->num_rows(); ?> )</a></li>
                    <li class="<?php if($type == 'draft') echo "active"; ?>"><a href="<?php echo site_url('home/dashboard/draft'); ?>"><?php echo get_phrase('draft'); ?> ( <?php echo $my_courses['draft']->num_rows(); ?> )</a></li>
                    <li class="<?php if($type == 'payment_report') echo "active"; ?>"><a href="<?php echo site_url('home/dashboard/payment_report'); ?>"><?php echo get_phrase('payment_report'); ?></a></li>
                    <li class="<?php if($type == 'payment_settings') echo "active"; ?>"><a href="<?php echo site_url('home/dashboard/payment_settings'); ?>"><?php echo get_phrase('payment_settings'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="category-course-list-area">
    <div class="container">
        <div class="row">
            <div class="col" style="padding: 35px;">
                <?php if ($type == 'active'): ?>
                    <?php include 'active_courses.php'; ?>
                <?php elseif ($type == 'pending'): ?>
                    <?php include 'pending_courses.php'; ?>
                <?php elseif ($type == 'draft'): ?>
                    <?php include 'draft_courses.php'; ?>
                <?php elseif ($type == 'payment_report'): ?>
                    <?php include 'payment_report.php'; ?>
                <?php elseif ($type == 'payment_settings'): ?>
                    <?php include 'payment_settings.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
