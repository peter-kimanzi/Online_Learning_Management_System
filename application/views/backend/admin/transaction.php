<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('transactions'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo $page_title; ?></h2>
<br />

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <table class="table table-bordered datatable" id="table-1">
                  <thead>
                    <tr>
                      <th><?php echo get_phrase('category_title'); ?></th>
                      <th><?php echo get_phrase('sub_categories'); ?></th>
                      <th><?php echo get_phrase('actions'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                     
                  </tbody>
                </table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

</script>
