<?php
    $user_data   = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
    $paypal_keys = json_decode($user_data['paypal_keys'], true);
    $stripe_keys = json_decode($user_data['stripe_keys'], true);
 ?>
<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('setup_payment_informations'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-md-7" style="padding: 0;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title"><p><?php echo get_phrase('setup_paypal_settings'); ?></p></h4>
                <form class="" action="<?php echo site_url('user/payment_settings/paypal_settings'); ?>" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label><?php echo get_phrase('client_id').' ('.get_phrase('production').')'; ?></label>
                    <input type="text" name="paypal_client_id" class="form-control" value="<?php echo $paypal_keys[0]['production_client_id']; ?>" required />
                </div>

                <div class="row justify-content-md-center">
                    <div class="form-group col-md-6">
                        <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_paypal_keys'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title"><p><?php echo get_phrase('setup_stripe_settings'); ?></p></h4>
            <form class="" action="<?php echo site_url('user/payment_settings/stripe_settings'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label><?php echo get_phrase('live_secret_key'); ?></label>
                <input type="text" name="stripe_secret_key" class="form-control" value="<?php echo $stripe_keys[0]['secret_live_key']; ?>" required />
            </div>

            <div class="form-group">
                <label><?php echo get_phrase('live_public_key'); ?></label>
                <input type="text" name="stripe_public_key" class="form-control" value="<?php echo $stripe_keys[0]['public_live_key']; ?>" required />
            </div>

            <div class="row justify-content-md-center">
                <div class="form-group col-md-6">
                    <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_stripe_keys'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</div>
