<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('invoice'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<?php
    $course_details = $this->crud_model->get_course_by_id($payment_details['course_id'])->row_array();
    $instructor_details = $this->user_model->get_all_user($course_details['user_id'])->row_array();
    $admin_details = $this->user_model->get_admin_details()->row_array();
 ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Invoice Logo-->
                <div class="clearfix">
                    <div class="float-left mb-3">
                        <img src="assets/images/logo-light.png" alt="" height="18">
                    </div>
                    <div class="float-right">
                        <h4 class="m-0 d-print-none"><?php echo get_phrase("invoice"); ?></h4>
                    </div>
                </div>

                <!-- Invoice Detail-->
                <div class="row">
                    <div class="col-sm-6">

                    </div><!-- end col -->
                    <div class="col-sm-4 offset-sm-2">
                        <div class="mt-3 float-sm-right">
                            <p class="font-13"><strong><?php echo get_phrase("purchase_date"); ?>: </strong> &nbsp;&nbsp;&nbsp; <?php echo date('D, d-M-Y', $payment_details['date_added']); ?></p>
                            <p class="font-13"><strong><?php echo get_phrase("instructor_payment_status"); ?>: </strong> <?php if ($payment_details['instructor_payment_status'] == 1): ?><span class="badge badge-success float-right"><?php echo get_phrase("paid"); ?></span><?php else: ?><span class="badge badge-danger float-right"><?php echo get_phrase("unpaid"); ?></span><?php endif; ?> </p>
                            <p class="font-13"><strong><?php echo get_phrase("order_id"); ?>: </strong> <span class="float-right"><?php echo sprintf('%04d', $payment_details['id']); ?></span></p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row mt-4">
                    <div class="col-sm-4">
                        <h6><?php echo get_phrase("instructor_details"); ?></h6>
                        <address>
                            <?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?><br>
                            <?php echo $instructor_details['email']; ?><br>
                        </address>
                    </div> <!-- end col-->

                    <div class="col-sm-4">
                        <h6><?php echo get_phrase("admin_details"); ?></h6>
                        <address>
                            <?php echo $admin_details['first_name'].' '.$admin_details['last_name']; ?><br>
                            <?php echo $admin_details['email']; ?><br>
                        </address>
                    </div> <!-- end col-->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-4">
                                <thead>
                                    <tr><th>#</th>
                                        <th><?php echo get_phrase("course_name"); ?></th>
                                        <th><?php echo get_phrase("total_amount"); ?></th>
                                        <th><?php echo get_phrase("instructor_revenue"); ?></th>
                                        <th class="text-right"><?php echo get_phrase("total"); ?></th>
                                    </tr></thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <b><?php echo $course_details['title']; ?></b>
                                            </td>
                                            <td>
                                                <?php if ($course_details['discount_flag'] == 1): ?>
                                                    <?php echo currency($course_details['discounted_price']); ?>
                                                <?php else: ?>
                                                    <?php echo currency($course_details['price']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo currency($payment_details['instructor_revenue']); ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo currency($payment_details['instructor_revenue']); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-sm-6">

                        </div> <!-- end col -->
                        <div class="col-sm-6">
                            <div class="float-right mt-3 mt-sm-0">
                                <p><b><?php echo get_phrase("sub_total"); ?>:</b> <span class="float-right">
                                    <?php echo currency($payment_details['instructor_revenue']); ?>
                                </span></p>
                                <h3>
                                    <?php echo currency($payment_details['instructor_revenue']); ?>
                                </h3>
                            </div>
                            <div class="clearfix"></div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                    <div class="d-print-none mt-4">
                        <div class="text-right">
                            <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a>
                            <a href="javascript: void(0);" class="btn btn-info">Submit</a>
                        </div>
                    </div>
                    <!-- end buttons -->

                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>
