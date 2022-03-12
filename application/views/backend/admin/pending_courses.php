<?php
    $courses = $this->crud_model->get_courses();
?>

<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('courses'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo $page_title; ?></h2>
<br />

<div class="row">
	<div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
              <div class="row">
                  <div class="col-md-4 col-md-offset-4">
                      <a href = "<?php echo site_url('admin/course_form/add_course'); ?>" class="btn btn-block btn-info btn-lg" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('add_course'); ?></a>
                  </div>
              </div>
              <hr>
              <table class="table table-bordered" id="table-2">
                  <thead>
                      <tr>
                          <th><?php echo get_phrase('title'); ?></th>
                          <th><?php echo get_phrase('category'); ?></th>
                          <th><?php echo get_phrase('sub_category'); ?></th>
                          <th><?php echo get_phrase('instructor'); ?></th>
                          <th><?php echo get_phrase('number_of_sections'); ?></th>
                          <th><?php echo get_phrase('number_of_lessons'); ?></th>
                          <th><?php echo get_phrase('number_of_enrolled_users'); ?></th>
                          <th><?php echo get_phrase('action'); ?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                          $pending_courses = 0;
                          foreach ($courses->result_array() as $course):
                          if ($course['status'] != 'pending')
                              continue;
                          else
                              $pending_courses++;
                          ?>
                          <tr>
                              <td><?php echo $course['title']; ?></td>
                              <td>
                                  <?php
                                  $category_details = $this->crud_model->get_categories($course['category_id'])->row_array();
                                  echo $category_details['name'];
                                  ?>
                              </td>
                              <td>
                                  <?php
                                  $subcategory_details = $this->crud_model->get_category_details_by_id($course['sub_category_id'])->row_array();
                                  echo $subcategory_details['name'];
                                  ?>
                              </td>
                              <td>
                                  <?php
                                     if ($course['user_id'] > 0) {
                                         $instructor_details = $this->user_model->get_all_user($course['user_id'])->row_array();
                                         echo $instructor_details['first_name'].' '.$instructor_details['last_name'];
                                     }else {
                                         $admin_details = $this->user_model->get_admin_details();
                                         echo $admin_details['first_name'].' '.$admin_details['last_name'];
                                     }
                                  ?>
                              </td>
                              <td hidden>
                                  <ul style="list-style-type:square">
                                      <?php
                                      $lessons = $this->crud_model->get_lessons('course', $course['id'])->result_array();
                                      foreach ($lessons as $lesson):?>
                                      <a href="<?php echo site_url('admin/watch_video/'.slugify($lesson['title']).'/'.$lesson['id']); ?>"><li><?php echo $lesson['title']; ?></li></a>
                                  <?php endforeach; ?>
                              </ul>
                          </td>
                          <td>
                              <?php
                              $sections = $this->crud_model->get_section('course', $course['id']);
                              echo $sections->num_rows();
                              ?>
                          </td>
                          <td>
                              <?php
                              $lessons = $this->crud_model->get_lessons('course', $course['id']);
                              echo $lessons->num_rows();
                              ?>
                          </td>
                          <td>
                              <?php
                              $enrol_history = $this->crud_model->enrol_history($course['id']);
                              echo $enrol_history->num_rows();
                              ?>
                          </td>
                          <td>
                              <div class="btn-group">
                                  <button class="btn btn-small btn-default btn-demo-space" data-toggle="dropdown"> <i class = "fa fa-ellipsis-v"></i> </button>
                                  <ul class="dropdown-menu">
                                      <li>
                                          <a href="<?php echo site_url('home/course/'.slugify($course['title']).'/'.$course['id']); ?>" target="_blank">
                                              <?php echo get_phrase('view_course_on_frontend');?>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="<?php echo site_url('admin/sections/'.$course['id']); ?>">
                                              <?php echo get_phrase('manage_section');?>
                                          </a>
                                      </li>

                                      <li>
                                          <a href="<?php echo site_url('admin/lessons/'.$course['id']); ?>">
                                              <?php echo get_phrase('manage_lesson');?>
                                          </a>
                                      </li>

                                      <li>
                                          <?php if ($course['user_id'] != $this->session->userdata('user_id')): ?>
                                              <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/mail_on_course_status_changing_modal/active/<?php echo $course['id'];?>');">
                                                  <?php echo get_phrase('mark_as_active');?>
                                              </a>
                                          <?php else: ?>
                                              <a href="#" onclick="confirm_modal('<?php echo site_url();?>admin/change_course_status_for_admin/active/<?php echo $course['id'];?>', 'generic_confirmation');">
                                                  <?php echo get_phrase('mark_as_active');?>
                                              </a>
                                          <?php endif; ?>
                                      </li>

                                      <li>
                                          <a href="<?php echo site_url('admin/course_form/course_edit/'.$course['id']) ?>">
                                              <?php echo get_phrase('edit');?>
                                          </a>
                                      </li>

                                      <li class="divider"></li>
                                      <li>
                                          <a href="#" onclick="confirm_modal('<?php echo site_url('admin/course_actions/delete/'.$course['id']); ?>');">
                                              <?php echo get_phrase('delete');?>
                                          </a>
                                      </li>
                                  </ul>
                              </div>
                          </td>
                      </tr>
                  <?php endforeach; ?>
                  <?php if ($pending_courses == 0): ?>
                      <tr>
                          <td colspan="8"><?php echo get_phrase('no_data_found'); ?></td>
                      </tr>
                  <?php endif; ?>
              </tbody>
              </table>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
    function ajax_get_sub_category(category_id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax_get_sub_category/');?>' + category_id ,
            success: function(response)
            {
                jQuery('#sub_category_id').html(response);
                console.log(response);
            }
        });
    }
</script>

<!-- <script type="text/javascript">
var responsiveHelper2;
var breakpointDefinition2 = {
    tablet: 1024,
    phone : 480
};
var tableContainer2;

jQuery(document).ready(function($)
{
    tableContainer2 = $("#table-2");

    tableContainer2.dataTable({
        "sPaginationType": "bootstrap",
        "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "bStateSave": true,


        // Responsive Settings
        bAutoWidth     : false,
        fnPreDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper2) {
                responsiveHelper2 = new ResponsiveDatatablesHelper(tableContainer2, breakpointDefinition2);
            }
        },
        fnRowCallback  : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            responsiveHelper2.createExpandIcon(nRow);
        },
        fnDrawCallback : function (oSettings) {
            responsiveHelper2.respond();
        }
    });

    $(".dataTables_wrapper select").select2({
        minimumResultsForSearch: -1
    });
});
</script> -->
