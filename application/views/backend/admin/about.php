<?php
  $curl_enabled = function_exists('curl_version');
?>
  <!-- start page title -->
  <div class="row ">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('about_this_application'); ?></h4>
        </div> <!-- end card body-->
      </div> <!-- end card -->
    </div><!-- end col-->
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8">
      <div class="card cta-box">
        <div class="card-body">
          <div class="media align-items-center">
            <div class="media-body">
              <div class="chart-widget-list">
                <p>
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('software_version'); ?>
                  <span class="float-right"><?php echo get_settings('version'); ?></span>
                </p>
                <p>
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('check_update'); ?>
                  <span class="float-right">
                      <a href="https://codecanyon.net/user/creativeitem/portfolio"
                        target="_blank" style="color: #343a40;">
                          <i class="mdi mdi-telegram"></i>
                            <?php echo get_phrase('check_update'); ?>
                      </a>
                  </span>
                </p>
                <p>
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('php_version'); ?>
                  <span class="float-right"><?php echo phpversion(); ?></span>
                </p>
                <p class="mb-0">
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('curl_enable') ?>
                  <span class="float-right">
                    <?php echo $curl_enabled ? '<span class="badge badge-success-lighten">'.get_phrase('enabled').'</span>' : '<span class="badge badge-danger-lighten">'.get_phrase('disabled').'</span>'; ?>
                  </span>
                </p>

                <p style="margin-top: 8px;">
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('purchase_code'); ?>
                  <span class="float-right"><?php echo get_settings('purchase_code'); ?></span>
                </p>
                <p>
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('purchase_code_status'); ?>
                  <span class="float-right">
                    <?php if (strtolower($application_details['purchase_code_status']) == 'expired'): ?>
                      <span class="badge badge-danger-lighten"><?php echo $application_details['purchase_code_status']; ?></span>
                    <?php elseif (strtolower($application_details['purchase_code_status']) == 'valid'): ?>
                      <span class="badge badge-success-lighten"><?php echo $application_details['purchase_code_status']; ?></span>
                    <?php else: ?>
                      <span class="badge badge-danger-lighten"><?php echo ucfirst($application_details['purchase_code_status']); ?></span>
                    <?php endif; ?>
                  </span>
                </p>
                <p>
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('support_expiry_date'); ?>

                    <?php if ($application_details['support_expiry_date'] != "invalid"): ?>
                        <span class="float-right"><?php echo $application_details['support_expiry_date']; ?></span>
                    <?php else: ?>
                        <span class="float-right"><span class="badge badge-danger-lighten"><?php echo ucfirst($application_details['support_expiry_date']); ?></span></span>
                    <?php endif; ?>
                </p>
                <p class="mb-0">
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('customer_name') ?>
                  <?php if ($application_details['customer_name'] != "invalid"): ?>
                      <span class="float-right"><?php echo $application_details['customer_name']; ?></span>
                  <?php else: ?>
                      <span class="float-right"><span class="badge badge-danger-lighten"><?php echo ucfirst($application_details['customer_name']); ?></span></span>
                  <?php endif; ?>
                </p>
                <p style="margin-top: 8px;">
                  <i class="mdi mdi-square"></i> <?php echo get_phrase('get_customer_support'); ?>
                  <span class="float-right"><a href="http://support.creativeitem.com" target="_blank" style="color: #343a40;"> <i class="mdi mdi-telegram"></i> <?php echo get_phrase('customer_support'); ?> </a> </span>
                </p>
              </div>
            </div>
            <img class="ml-3" src="<?php echo base_url('assets/backend/images/report.svg'); ?>" width="120" alt="Generic placeholder image">
          </div>
        </div>
      </div>
    </div>
  </div>
