<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('manage_language'); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<ul class="nav nav-tabs nav-bordered mb-3">
					<?php if(isset($edit_profile)):?>
						<li class="nav-item">
							<a href="#edit" data-toggle="tab" aria-expanded="true" class="nav-link active">
								<?php echo get_phrase('edit_phrase');?>
							</a>
						</li>
					<?php endif;?>
					<li class="nav-item">
						<a href="#list" data-toggle="tab" aria-expanded="false" class="nav-link <?php if(!isset($edit_profile))echo 'active';?>">
							<i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
							<span class="d-none d-lg-block"><?php echo get_phrase('language_list');?></span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#add" data-toggle="tab" aria-expanded="true" class="nav-link">
							<i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
							<span class="d-none d-lg-block"><?php echo get_phrase('add_phrase');?></span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#add_lang" data-toggle="tab" aria-expanded="false" class="nav-link">
							<i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
							<span class="d-none d-lg-block"><?php echo get_phrase('add_language');?></span>
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<!----PHRASE EDITING TAB STARTS-->
					<?php if (isset($edit_profile)):
						$current_editing_language	=	$edit_profile;
					?>
						<div class="tab-pane show active" id="edit" style="padding: 30px">
							<div class="row">
								<?php foreach (openJSONFile($edit_profile) as $key => $value): ?>
								<div class="col-xl-3 col-lg-6">
									<div class="card">
										<div class="card-header">
											<?php echo $key; ?>
										</div>
										<div class="card-body">
											<p>
												<input type="text" class="form-control" name="updated_phrase" value="<?php echo $value; ?>" id = "phrase-<?php echo $key; ?>">
											</p>
											<button type="button" class="btn btn-icon btn-primary" style="float: right;" id = "btn-<?php echo $key; ?>" onclick="updatePhrase('<?php echo $key; ?>')"> <i class = "mdi mdi-check-circle"></i> </button>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif;?>
					<!----PHRASE EDITING TAB ENDS-->

					<!----TABLE LISTING STARTS-->
					<div class="tab-pane <?php if(!isset($edit_profile))echo 'show active';?>" id="list">

						<div class="table-responsive-sm">
							<table class="table table-bordered table-centered mb-0">
								<thead>
									<tr>
										<th><?php echo get_phrase('language');?></th>
										<th><?php echo get_phrase('option');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($languages as $language):?>
									<tr>
										<td><?php echo ucwords($language);?></td>
										<td>
											<a href="<?php echo site_url('admin/manage_language/edit_phrase/'.$language);?>"
												class="btn btn-info">
												<?php echo get_phrase('edit_phrase');?>
											</a>
											<a href="<?php echo site_url('index.php?admin/manage_language/delete_language/'.$language);?>"
												class="btn btn-danger">
												<?php echo get_phrase('delete_language');?>
											</a>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>

						</div>
					</div>
					<!----TABLE LISTING ENDS--->

					<!----PHRASE CREATION FORM STARTS---->
					<div class="tab-pane" id="add" style="padding: 30px">
						<div class="row">
							<div class="col-xl-6">
								<div class="card">
									<div class="card-body">
										<form class="" action="<?php echo site_url('admin/manage_language/add_phrase') ?>" method="post">
											<div class="form-group mb-3">
                          <label for="simpleinput"><?php echo get_phrase('add_new_phrase'); ?></label>
                          <input type="text" id="phrase" name="phrase" class="form-control" placeholder="Eg. Contamination">
                      </div>
											<button type="submit" class="btn btn-primary" name="button"><?php echo get_phrase('save'); ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!----PHRASE CREATION FORM ENDS--->

					<!----ADD NEW LANGUAGE---->
					<div class="tab-pane" id="add_lang" style="padding: 30px">
						<div class="row">
							<div class="col-xl-6">
								<div class="card">
									<div class="card-body">
										<form class="" action="<?php echo site_url('admin/manage_language/add_language'); ?>" method="post">
											<div class="form-group mb-3">
                          <label for="language"><?php echo get_phrase('add_new_language'); ?></label>
                          <input type="text" id="language" name="language" class="form-control" placeholder="Eg. Bengali">
                      </div>
											<button type="submit" class="btn btn-primary" name="button"><?php echo get_phrase('save'); ?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!----LANGUAGE ADDING FORM ENDS-->

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

function updatePhrase(key) {


	$('#btn-'+key).text('...');
	var updatedValue = $('#phrase-'+key).val();
	var currentEditingLanguage = '<?php echo $current_editing_language; ?>';
	console.log(key);
	console.log(updatedValue);
	console.log(currentEditingLanguage);
	console.log(key);
	$.ajax({
		type : "POST",
		url  : "<?php echo site_url('admin/update_phrase_with_ajax'); ?>",
		data : {updatedValue : updatedValue, currentEditingLanguage : currentEditingLanguage, key : key},
		success : function(response) {
			$('#btn-'+key).html('<i class = "mdi mdi-check-circle"></i>');
			success_notify('<?php echo get_phrase('phrase_updated');?>');
		}
	});
}
</script>
