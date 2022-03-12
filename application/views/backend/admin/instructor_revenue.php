<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('instructor_revenue'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('instructor_revenue'); ?></h4>
                <div class="row justify-content-md-center">
                    <div class="col-xl-6">
                        <form class="form-inline" action="<?php echo site_url('admin/admin_revenue/filter_by_date_range') ?>" method="get">
                            <div class="col-xl-10">
                                <div class="form-group">
                                    <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">
                                        <i class="mdi mdi-calendar"></i>&nbsp;
                                        <span id="selectedValue"><?php echo date("F d, Y" , $timestamp_start) . " - " . date("F d, Y" , $timestamp_end);?></span> <i class="mdi mdi-menu-down"></i>
                                    </div>
                                    <input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y" , $timestamp_start) . " - " . date("d F, Y" , $timestamp_end);?>">
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter');?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive-sm mt-4">
                    <table class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('enrolled_course'); ?></th>
                                <th><?php echo get_phrase('instructor'); ?></th>
                                <th><?php echo get_phrase('instructor_revenue'); ?></th>
                                <th><?php echo get_phrase('status'); ?></th>
                                <th class="text-center"><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payment_history as $payment):
                                $user_data = $this->db->get_where('users', array('id' => $payment['user_id']))->row_array();
                                $course_data = $this->db->get_where('course', array('id' => $payment['course_id']))->row_array();?>
                                <?php
                                $paypal_keys          = json_decode($user_data['paypal_keys'], true);
                                $stripe_keys          = json_decode($user_data['stripe_keys'], true);
                                ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong><a href="<?php echo site_url('home/course/'.slugify($course_data['title']).'/'.$course_data['id']); ?>" target="_blank"><?php echo ellipsis($course_data['title']); ?></a></strong><br>
                                        <small class="text-muted"><?php echo get_phrase('enrolment_date').': '.date('D, d-M-Y', $payment['date_added']); ?></small>
                                    </td>
                                    <td><?php echo $user_data['first_name'].' '.$user_data['last_name']; ?></td>
                                    <td>
                                        <?php echo currency($payment['instructor_revenue']); ?><br>
                                        <small class="text-muted"><?php echo get_phrase('total_amount').': '.currency($payment['amount']); ?></small>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($payment['instructor_payment_status'] == 0): ?>
                                            <div class="label label-secondary"><?php echo get_phrase('pending'); ?></div>
                                        <?php elseif($payment['instructor_payment_status'] == 1): ?>
                                            <div class="label label-success"><?php echo get_phrase('paid'); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($payment['instructor_payment_status'] == 0): ?>
                                            <?php if ($paypal_keys[0]['production_client_id'] != ""): ?>
                                                <form action="<?php echo site_url('admin/paypal_checkout_for_instructor_revenue'); ?>" method="post">
                                                    <input type="hidden" name="amount_to_pay"        value="<?php echo $payment['instructor_revenue']; ?>">
                                                    <input type="hidden" name="payment_id"           value="<?php echo $payment['id']; ?>">
                                                    <input type="hidden" name="instructor_name"      value="<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>">
                                                    <input type="hidden" name="course_title"         value="<?php echo $course_data['title']; ?>">
                                                    <input type="hidden" name="production_client_id" value="<?php echo $paypal_keys[0]['production_client_id']; ?>">
                                                    <input type="submit" class="btn btn-outline-info btn-sm btn-rounded"        value="<?php echo get_phrase('pay_with_paypal'); ?>">
                                                </form>
                                            <?php else: ?>
                                                <button type="button" class = "btn btn-outline-danger btn-sm btn-rounded" name="button" onclick="alert('<?php echo get_phrase('this_instructor_has_not_provided_valid_paypal_client_id'); ?>')"><?php echo get_phrase('pay_with_paypal'); ?></button>
                                            <?php endif; ?>
                                            <?php if ($stripe_keys[0]['public_live_key'] != "" && $stripe_keys[0]['secret_live_key']): ?>
                                                <form action="<?php echo site_url('admin/stripe_checkout_for_instructor_revenue'); ?>" method="post">
                                                    <input type="hidden" name="amount_to_pay"   value="<?php echo $payment['instructor_revenue']; ?>">
                                                    <input type="hidden" name="payment_id"      value="<?php echo $payment['id']; ?>">
                                                    <input type="hidden" name="instructor_name" value="<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>">
                                                    <input type="hidden" name="course_title"    value="<?php echo $course_data['title']; ?>">
                                                    <input type="hidden" name="public_live_key" value="<?php echo $stripe_keys[0]['public_live_key']; ?>">
                                                    <input type="hidden" name="secret_live_key" value="<?php echo $stripe_keys[0]['secret_live_key']; ?>">
                                                    <input type="submit" class="btn btn-outline-info btn-sm btn-rounded"   value="<?php echo get_phrase('pay_with_stripe'); ?>">
                                                </form>
                                            <?php else: ?>
                                                <button type="button" class = "btn btn-outline-danger btn-sm btn-rounded" name="button" onclick="alert('<?php echo get_phrase('this_instructor_has_not_provided_valid_public_key_or_secret_key'); ?>')"><?php echo get_phrase('pay_with_stripe'); ?></button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('admin/invoice/'.$payment['id']); ?>" class="btn btn-outline-primary btn-rounded btn-sm"><i class="mdi mdi-printer-settings"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function update_date_range()
{
    var x = $("#selectedValue").html();
    $("#date_range").val(x);
}
</script>
