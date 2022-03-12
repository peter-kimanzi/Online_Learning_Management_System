<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
  public function __construct()
  {
    parent::__construct();

    $this->load->database();
    $this->load->library('session');
    /*cache control*/
    $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    $this->output->set_header('Pragma: no-cache');
    if (!$this->session->userdata('cart_items')) {
      $this->session->set_userdata('cart_items', array());
    }
  }

  public function index() {
    if ($this->session->userdata('admin_login') == true) {
      $this->dashboard();
    }else {
      redirect(site_url('login'), 'refresh');
    }
  }
  public function dashboard() {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $page_data['page_name'] = 'dashboard';
    $page_data['page_title'] = get_phrase('dashboard');
    $this->load->view('backend/index.php', $page_data);
  }

  public function blank_template() {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $page_data['page_name'] = 'blank_template';
    $this->load->view('backend/index.php', $page_data);
  }

  public function categories($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'add') {
      $this->crud_model->add_category();
      $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
      redirect(site_url('admin/categories'), 'refresh');
    }
    elseif ($param1 == "edit") {
      $this->crud_model->edit_category($param2);
      $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
      redirect(site_url('admin/categories'), 'refresh');
    }
    elseif ($param1 == "delete") {
      $this->crud_model->delete_category($param2);
      $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
      redirect(site_url('admin/categories'), 'refresh');
    }
    $page_data['page_name'] = 'categories';
    $page_data['page_title'] = get_phrase('categories');
    $page_data['categories'] = $this->crud_model->get_categories($param2);
    $this->load->view('backend/index', $page_data);
  }

  public function category_form($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    if ($param1 == "add_category") {
      $page_data['page_name'] = 'category_add';
      $page_data['categories'] = $this->crud_model->get_categories()->result_array();
      $page_data['page_title'] = get_phrase('add_category');
    }
    if ($param1 == "edit_category") {
      $page_data['page_name'] = 'category_edit';
      $page_data['page_title'] = get_phrase('edit_category');
      $page_data['categories'] = $this->crud_model->get_categories()->result_array();
      $page_data['category_id'] = $param2;
    }

    $this->load->view('backend/index', $page_data);
  }

  public function sub_categories_by_category_id($category_id = 0) {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    $category_id = $this->input->post('category_id');
    redirect(site_url("admin/sub_categories/$category_id"), 'refresh');
  }

  public function sub_category_form($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'add_sub_category') {
      $page_data['page_name'] = 'sub_category_add';
      $page_data['page_title'] = get_phrase('add_sub_category');
    }
    elseif ($param1 == 'edit_sub_category') {
      $page_data['page_name'] = 'sub_category_edit';
      $page_data['page_title'] = get_phrase('edit_sub_category');
      $page_data['sub_category_id'] = $param2;
    }
    $page_data['categories'] = $this->crud_model->get_categories();
    $this->load->view('backend/index', $page_data);
  }

  public function users($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    if ($param1 == "add") {
      $this->user_model->add_user();
      redirect(site_url('admin/users'), 'refresh');
    }
    elseif ($param1 == "edit") {
      $this->user_model->edit_user($param2);
      redirect(site_url('admin/users'), 'refresh');
    }
    elseif ($param1 == "delete") {
      $this->user_model->delete_user($param2);
      redirect(site_url('admin/users'), 'refresh');
    }

    $page_data['page_name'] = 'users';
    $page_data['page_title'] = get_phrase('student');
    $page_data['users'] = $this->user_model->get_user($param2);
    $this->load->view('backend/index', $page_data);
  }

  public function user_form($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'add_user_form') {
      $page_data['page_name'] = 'user_add';
      $page_data['page_title'] = get_phrase('student_add');
      $this->load->view('backend/index', $page_data);
    }
    elseif ($param1 == 'edit_user_form') {
      $page_data['page_name'] = 'user_edit';
      $page_data['user_id'] = $param2;
      $page_data['page_title'] = get_phrase('student_edit');
      $this->load->view('backend/index', $page_data);
    }
  }

  public function enrol_history($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 != "") {
      $date_range                   = $this->input->get('date_range');
      $date_range                   = explode(" - ", $date_range);
      $page_data['timestamp_start'] = strtotime($date_range[0]);
      $page_data['timestamp_end']   = strtotime($date_range[1]);
    }else {
      $page_data['timestamp_start'] = strtotime('-29 days', time());
      $page_data['timestamp_end']   = strtotime(date("m/d/Y"));
    }
    $page_data['page_name'] = 'enrol_history';
    $page_data['enrol_history'] = $this->crud_model->enrol_history_by_date_range($page_data['timestamp_start'], $page_data['timestamp_end']);
    $page_data['page_title'] = get_phrase('enrol_history');
    $this->load->view('backend/index', $page_data);
  }

  public function enrol_student($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    if ($param1 == 'enrol') {
      $this->crud_model->enrol_a_student_manually();
      redirect(site_url('admin/enrol_history'), 'refresh');
    }
    $page_data['page_name'] = 'enrol_student';
    $page_data['page_title'] = get_phrase('enrol_a_student');
    $this->load->view('backend/index', $page_data);
  }
  public function admin_revenue($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 != "") {
      $date_range                   = $this->input->post('date_range');
      $date_range                   = explode(" - ", $date_range);
      $page_data['timestamp_start'] = strtotime($date_range[0]);
      $page_data['timestamp_end']   = strtotime($date_range[1]);
    }else {
      $page_data['timestamp_start'] = strtotime('-29 days', time());
      $page_data['timestamp_end']   = strtotime(date("m/d/Y"));
    }

    $page_data['page_name'] = 'admin_revenue';
    $page_data['payment_history'] = $this->crud_model->get_revenue_by_user_type($page_data['timestamp_start'], $page_data['timestamp_end'], 'admin_revenue');
    $page_data['page_title'] = get_phrase('admin_revenue');
    $this->load->view('backend/index', $page_data);
  }

  public function instructor_revenue($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 != "") {
      $date_range                   = $this->input->post('date_range');
      $date_range                   = explode(" - ", $date_range);
      $page_data['timestamp_start'] = strtotime($date_range[0]);
      $page_data['timestamp_end']   = strtotime($date_range[1]);
    }else {
      $page_data['timestamp_start'] = strtotime('-29 days', time());
      $page_data['timestamp_end']   = strtotime(date("m/d/Y"));
    }
    $page_data['page_name'] = 'instructor_revenue';
    $page_data['payment_history'] = $this->crud_model->get_revenue_by_user_type($page_data['timestamp_start'], $page_data['timestamp_end'], 'instructor_revenue');
    $page_data['page_title'] = get_phrase('instructor_revenue');
    $this->load->view('backend/index', $page_data);
  }

  function invoice($payment_id = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $page_data['page_name'] = 'invoice';
    $page_data['payment_details'] = $this->crud_model->get_payment_details_by_id($payment_id);
    $page_data['page_title'] = get_phrase('invoice');
    $this->load->view('backend/index', $page_data);
  }

  public function payment_history_delete($param1 = "", $redirect_to = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $this->crud_model->delete_payment_history($param1);
    $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
    redirect(site_url('admin/'.$redirect_to), 'refresh');
  }

  public function enrol_history_delete($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $this->crud_model->delete_enrol_history($param1);
    $this->session->set_flashdata('flash_message', get_phrase('data_deleted_successfully'));
    redirect(site_url('admin/enrol_history'), 'refresh');
  }

  public function purchase_history() {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $page_data['page_name'] = 'purchase_history';
    $page_data['purchase_history'] = $this->crud_model->purchase_history();
    $page_data['page_title'] = get_phrase('purchase_history');
    $this->load->view('backend/index', $page_data);
  }

  public function system_settings($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'system_update') {
      $this->crud_model->update_system_settings();
      $this->session->set_flashdata('flash_message', get_phrase('system_settings_updated'));
      redirect(site_url('admin/system_settings'), 'refresh');
    }

    if ($param1 == 'logo_upload') {
      move_uploaded_file($_FILES['logo']['tmp_name'], 'assets/backend/logo.png');
      $this->session->set_flashdata('flash_message', get_phrase('backend_logo_updated'));
      redirect(site_url('admin/system_settings'), 'refresh');
    }

    if ($param1 == 'favicon_upload') {
      move_uploaded_file($_FILES['favicon']['tmp_name'], 'assets/favicon.png');
      $this->session->set_flashdata('flash_message', get_phrase('favicon_updated'));
      redirect(site_url('admin/system_settings'), 'refresh');
    }

    $page_data['languages']	 = $this->get_all_languages();
    $page_data['page_name'] = 'system_settings';
    $page_data['page_title'] = get_phrase('system_settings');
    $this->load->view('backend/index', $page_data);
  }

  public function frontend_settings($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'frontend_update') {
      $this->crud_model->update_frontend_settings();
      $this->session->set_flashdata('flash_message', get_phrase('frontend_settings_updated'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }

    if ($param1 == 'banner_image_update') {
      $this->crud_model->update_frontend_banner();
      $this->session->set_flashdata('flash_message', get_phrase('banner_image_update'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }
    if ($param1 == 'light_logo') {
      $this->crud_model->update_light_logo();
      $this->session->set_flashdata('flash_message', get_phrase('logo_updated'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }
    if ($param1 == 'dark_logo') {
      $this->crud_model->update_dark_logo();
      $this->session->set_flashdata('flash_message', get_phrase('logo_updated'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }
    if ($param1 == 'small_logo') {
      $this->crud_model->update_small_logo();
      $this->session->set_flashdata('flash_message', get_phrase('logo_updated'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }
    if ($param1 == 'favicon') {
      $this->crud_model->update_favicon();
      $this->session->set_flashdata('flash_message', get_phrase('favicon_updated'));
      redirect(site_url('admin/frontend_settings'), 'refresh');
    }

    $page_data['page_name'] = 'frontend_settings';
    $page_data['page_title'] = get_phrase('frontend_settings');
    $this->load->view('backend/index', $page_data);
  }
  public function payment_settings($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'system_currency') {
      $this->crud_model->update_system_currency();
      redirect(site_url('admin/payment_settings'), 'refresh');
    }
    if ($param1 == 'paypal_settings') {
      $this->crud_model->update_paypal_settings();
      redirect(site_url('admin/payment_settings'), 'refresh');
    }
    if ($param1 == 'stripe_settings') {
      $this->crud_model->update_stripe_settings();
      redirect(site_url('admin/payment_settings'), 'refresh');
    }

    $page_data['page_name'] = 'payment_settings';
    $page_data['page_title'] = get_phrase('payment_settings');
    $this->load->view('backend/index', $page_data);
  }

  public function smtp_settings($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'update') {
      $this->crud_model->update_smtp_settings();
      $this->session->set_flashdata('flash_message', get_phrase('smtp_settings_updated_successfully'));
      redirect(site_url('admin/smtp_settings'), 'refresh');
    }

    $page_data['page_name'] = 'smtp_settings';
    $page_data['page_title'] = get_phrase('smtp_settings');
    $this->load->view('backend/index', $page_data);
  }

  public function instructor_settings($param1 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    if ($param1 == 'update') {
      $this->crud_model->update_instructor_settings();
      $this->session->set_flashdata('flash_message', get_phrase('instructor_settings_updated'));
      redirect(site_url('admin/instructor_settings'), 'refresh');
    }

    $page_data['page_name'] = 'instructor_settings';
    $page_data['page_title'] = get_phrase('instructor_settings');
    $this->load->view('backend/index', $page_data);
  }

  public function courses() {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }


    $page_data['selected_category_id']   = isset($_GET['category_id']) ? $_GET['category_id'] : "all";
    $page_data['selected_instructor_id'] = isset($_GET['instructor_id']) ? $_GET['instructor_id'] : "all";
    $page_data['selected_price']         = isset($_GET['price']) ? $_GET['price'] : "all";
    $page_data['selected_status']        = isset($_GET['status']) ? $_GET['status'] : "all";
    $page_data['courses']                = $this->crud_model->filter_course_for_backend($page_data['selected_category_id'], $page_data['selected_instructor_id'], $page_data['selected_price'], $page_data['selected_status']);
    $page_data['status_wise_courses']    = $this->crud_model->get_status_wise_courses();
    $page_data['instructors']            = $this->user_model->get_instructor();
    $page_data['page_name']              = 'courses';
    $page_data['categories']             = $this->crud_model->get_categories();
    $page_data['page_title']             = get_phrase('active_courses');
    $this->load->view('backend/index', $page_data);
  }

  public function pending_courses() {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }


    $page_data['page_name'] = 'pending_courses';
    $page_data['page_title'] = get_phrase('pending_courses');
    $this->load->view('backend/index', $page_data);
  }

  public function course_actions($param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == "add") {
      $this->crud_model->add_course();
      redirect(site_url('admin/courses'), 'refresh');

    }
    elseif ($param1 == "edit") {
      $this->crud_model->update_course($param2);
      redirect(site_url('admin/courses'), 'refresh');

    }
    elseif ($param1 == 'delete') {
      $this->is_drafted_course($param2);
      $this->crud_model->delete_course($param2);
      redirect(site_url('admin/courses'), 'refresh');
    }
  }


  public function course_form($param1 = "", $param2 = "") {

    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param1 == 'add_course') {
      $page_data['languages']	= $this->get_all_languages();
      $page_data['categories'] = $this->crud_model->get_categories();
      $page_data['page_name'] = 'course_add';
      $page_data['page_title'] = get_phrase('add_course');
      $this->load->view('backend/index', $page_data);

    }elseif ($param1 == 'course_edit') {
      $this->is_drafted_course($param2);
      $page_data['page_name'] = 'course_edit';
      $page_data['course_id'] =  $param2;
      $page_data['page_title'] = get_phrase('edit_course');
      $page_data['languages']	= $this->get_all_languages();
      $page_data['categories'] = $this->crud_model->get_categories();
      $this->load->view('backend/index', $page_data);
    }
  }

  private function is_drafted_course($course_id){
    $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
    if ($course_details['status'] == 'draft') {
      $this->session->set_flashdata('error_message', get_phrase('you_do_not_have_right_to_access_this_course'));
      redirect(site_url('admin/courses'), 'refresh');
    }
  }

  public function change_course_status($updated_status = "") {
    $course_id = $this->input->post('course_id');
    $category_id = $this->input->post('category_id');
    $instructor_id = $this->input->post('instructor_id');
    $price = $this->input->post('price');
    $status = $this->input->post('status');
    if (isset($_POST['mail_subject']) && isset($_POST['mail_body'])) {
      $mail_subject = $this->input->post('mail_subject');
      $mail_body = $this->input->post('mail_body');
      $this->email_model->send_mail_on_course_status_changing($course_id, $mail_subject, $mail_body);
    }
    $this->crud_model->change_course_status($updated_status, $course_id);
    $this->session->set_flashdata('flash_message', get_phrase('course_status_updated'));
    redirect(site_url('admin/courses?category_id='.$category_id.'&status='.$status.'&instructor_id='.$instructor_id.'&price='.$price), 'refresh');
  }

  public function change_course_status_for_admin($updated_status = "", $course_id = "", $category_id = "", $status = "", $instructor_id = "", $price = "") {
    $this->crud_model->change_course_status($updated_status, $course_id);
    $this->session->set_flashdata('flash_message', get_phrase('course_status_updated'));
    redirect(site_url('admin/courses?category_id='.$category_id.'&status='.$status.'&instructor_id='.$instructor_id.'&price='.$price), 'refresh');
  }

  public function sections($param1 = "", $param2 = "", $param3 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($param2 == 'add') {
      $this->crud_model->add_section($param1);
      $this->session->set_flashdata('flash_message', get_phrase('section_has_been_added_successfully'));
    }
    elseif ($param2 == 'edit') {
      $this->crud_model->edit_section($param3);
      $this->session->set_flashdata('flash_message', get_phrase('section_has_been_updated_successfully'));
    }
    elseif ($param2 == 'delete') {
      $this->crud_model->delete_section($param1, $param3);
      $this->session->set_flashdata('flash_message', get_phrase('section_has_been_deleted_successfully'));
    }
    redirect(site_url('admin/course_form/course_edit/'.$param1));
  }

  public function lessons($course_id = "", $param1 = "", $param2 = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    if ($param1 == 'add') {
      $this->crud_model->add_lesson();
      $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_added_successfully'));
      redirect('admin/course_form/course_edit/'.$course_id);
    }
    elseif ($param1 == 'edit') {
      $this->crud_model->edit_lesson($param2);
      $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_updated_successfully'));
      redirect('admin/course_form/course_edit/'.$course_id);
    }
    elseif ($param1 == 'delete') {
      $this->crud_model->delete_lesson($param2);
      $this->session->set_flashdata('flash_message', get_phrase('lesson_has_been_deleted_successfully'));
      redirect('admin/course_form/course_edit/'.$course_id);
    }
    elseif ($param1 == 'filter') {
      redirect('admin/lessons/'.$this->input->post('course_id'));
    }
    $page_data['page_name'] = 'lessons';
    $page_data['lessons'] = $this->crud_model->get_lessons('course', $course_id);
    $page_data['course_id'] = $course_id;
    $page_data['page_title'] = get_phrase('lessons');
    $this->load->view('backend/index', $page_data);
  }

  public function watch_video($slugified_title = "", $lesson_id = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $lesson_details          = $this->crud_model->get_lessons('lesson', $lesson_id)->row_array();
    $page_data['provider']   = $lesson_details['video_type'];
    $page_data['video_url']  = $lesson_details['video_url'];
    $page_data['lesson_id']  = $lesson_id;
    $page_data['page_name']  = 'video_player';
    $page_data['page_title'] = get_phrase('video_player');
    $this->load->view('backend/index', $page_data);
  }


  // Language Functions
  public function manage_language($param1 = '', $param2 = '', $param3 = ''){
    if ($param1 == 'add_language') {
      saveDefaultJSONFile($this->input->post('language'));
      $this->session->set_flashdata('flash_message', get_phrase('language_added_successfully'));
      redirect(site_url('admin/manage_language'), 'refresh');
    }
    if ($param1 == 'add_phrase') {
      $new_phrase = get_phrase($this->input->post('phrase'));
      $this->session->set_flashdata('flash_message', $new_phrase.' '.get_phrase('has_been_added_successfully'));
      redirect(site_url('admin/manage_language'), 'refresh');
    }

    if ($param1 == 'edit_phrase') {
      $page_data['edit_profile'] = $param2;
    }

    $page_data['languages']				= $this->get_all_languages();
    $page_data['page_name']				=	'manage_language';
    $page_data['page_title']			=	get_phrase('multi_language_settings');
    $this->load->view('backend/index', $page_data);
  }

  public function update_phrase_with_ajax() {
    $current_editing_language = $this->input->post('currentEditingLanguage');
    $updatedValue = $this->input->post('updatedValue');
    $key = $this->input->post('key');
    saveJSONFile($current_editing_language, $key, $updatedValue);
    echo $current_editing_language.' '.$key.' '.$updatedValue;
  }

  function get_all_languages() {
    $language_files = array();
    $all_files = $this->get_list_of_language_files();
    foreach ($all_files as $file) {
      $info = pathinfo($file);
      if( isset($info['extension']) && strtolower($info['extension']) == 'json') {
        $file_name = explode('.json', $info['basename']);
        array_push($language_files, $file_name[0]);
      }
    }
    return $language_files;
  }

  function get_list_of_language_files($dir = APPPATH.'/language', &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){
      $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
      if(!is_dir($path)) {
        $results[] = $path;
      } else if($value != "." && $value != "..") {
        $this->get_list_of_directories_and_files($path, $results);
        $results[] = $path;
      }
    }
    return $results;
  }

  function get_list_of_directories_and_files($dir = APPPATH, &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){
      $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
      if(!is_dir($path)) {
        $results[] = $path;
      } else if($value != "." && $value != "..") {
        $this->get_list_of_directories_and_files($path, $results);
        $results[] = $path;
      }
    }
    return $results;
  }

  function message($param1 = 'message_home', $param2 = '', $param3 = '')
  {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');
    if ($param1 == 'send_new') {
      $message_thread_code = $this->crud_model->send_new_private_message();
      $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
      redirect(site_url('admin/message/message_read/' . $message_thread_code), 'refresh');
    }

    if ($param1 == 'send_reply') {
      $this->crud_model->send_reply_message($param2); //$param2 = message_thread_code
      $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
      redirect(site_url('admin/message/message_read/' . $param2), 'refresh');
    }

    if ($param1 == 'message_read') {
      $page_data['current_message_thread_code'] = $param2; // $param2 = message_thread_code
      $this->crud_model->mark_thread_messages_read($param2);
    }

    $page_data['message_inner_page_name'] = $param1;
    $page_data['page_name']               = 'message';
    $page_data['page_title']              = get_phrase('private_messaging');
    $this->load->view('backend/index', $page_data);
  }

  /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
  function manage_profile($param1 = '', $param2 = '', $param3 = '')
  {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');
    if ($param1 == 'update_profile_info') {
      $this->user_model->edit_user($param2);
      redirect(site_url('admin/manage_profile'), 'refresh');
    }
    if ($param1 == 'change_password') {
      $this->user_model->change_password($param2);
      redirect(site_url('admin/manage_profile'), 'refresh');
    }
    $page_data['page_name']  = 'manage_profile';
    $page_data['page_title'] = get_phrase('manage_profile');
    $page_data['edit_data']  = $this->db->get_where('users', array(
      'id' => $this->session->userdata('user_id')
    ))->result_array();
    $this->load->view('backend/index', $page_data);
  }

  public function paypal_checkout_for_instructor_revenue() {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $page_data['amount_to_pay']         = $this->input->post('amount_to_pay');
    $page_data['payment_id']            = $this->input->post('payment_id');
    $page_data['course_title']          = $this->input->post('course_title');
    $page_data['instructor_name']       = $this->input->post('instructor_name');
    $page_data['production_client_id']  = $this->input->post('production_client_id');
    $this->load->view('backend/admin/paypal_checkout_for_instructor_revenue', $page_data);
  }

  public function stripe_checkout_for_instructor_revenue() {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $page_data['amount_to_pay']    = $this->input->post('amount_to_pay');
    $page_data['payment_id']       = $this->input->post('payment_id');
    $page_data['course_title']     = $this->input->post('course_title');
    $page_data['instructor_name']  = $this->input->post('instructor_name');
    $page_data['public_live_key']  = $this->input->post('public_live_key');
    $page_data['secret_live_key']  = $this->input->post('secret_live_key');
    $this->load->view('backend/admin/stripe_checkout_for_instructor_revenue', $page_data);
  }

  public function payment_success($payment_type = "", $payment_id = "") {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    if ($payment_type == 'stripe') {
      $token_id = $this->input->post('stripeToken');
      $payment_details = $this->db->get_where('payment', array('id' => $payment_id))->row_array();
      $instructor_id = $payment_details['user_id'];
      $instructor_data = $this->db->get_where('users', array('id' => $instructor_id))->row_array();
      $stripe_keys = json_decode($instructor_data['stripe_keys'], true);
      $this->payment_model->stripe_payment($token_id, $this->session->userdata('user_id'), $payment_details['instructor_revenue'], $stripe_keys[0]['secret_live_key']);
    }
    $this->crud_model->update_instructor_payment_status($payment_id);
    $this->session->set_flashdata('flash_message', get_phrase('instructor_payment_has_been_done'));
    redirect(site_url('admin/instructor_revenue'), 'refresh');
  }


  public function preview($course_id = '') {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $this->is_drafted_course($course_id);
    if ($course_id > 0) {
      $courses = $this->crud_model->get_course_by_id($course_id);
      if ($courses->num_rows() > 0) {
        $course_details = $courses->row_array();
        redirect(site_url('home/lesson/'.slugify($course_details['title']).'/'.$course_details['id']), 'refresh');
      }
    }
    redirect(site_url('admin/courses'), 'refresh');
  }

  // Manage Quizes
  public function quizes($course_id = "", $action = "", $quiz_id = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }

    if ($action == 'add') {
      $this->crud_model->add_quiz($course_id);
      $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_added_successfully'));
    }
    elseif ($action == 'edit') {
      $this->crud_model->edit_quiz($quiz_id);
      $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_updated_successfully'));
    }
    elseif ($action == 'delete') {
      $this->crud_model->delete_section($course_id, $quiz_id);
      $this->session->set_flashdata('flash_message', get_phrase('quiz_has_been_deleted_successfully'));
    }
    redirect(site_url('admin/course_form/course_edit/'.$course_id));
  }

  // Manage Quize Questions
  public function quiz_questions($quiz_id = "", $action = "", $question_id = "") {
    if ($this->session->userdata('admin_login') != true) {
      redirect(site_url('login'), 'refresh');
    }
    $quiz_details = $this->crud_model->get_lessons('lesson', $quiz_id)->row_array();

    if ($action == 'add') {
      $response = $this->crud_model->add_quiz_questions($quiz_id);
      echo $response;
    }

    elseif ($action == 'edit') {
      $response = $this->crud_model->update_quiz_questions($question_id);
      echo $response;
    }

    elseif ($action == 'delete') {
      $response = $this->crud_model->delete_quiz_question($question_id);
      $this->session->set_flashdata('flash_message', get_phrase('question_has_been_deleted'));
      redirect(site_url('admin/course_form/course_edit/'.$quiz_details['course_id']));
    }
  }

  // software about page
  function about() {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $page_data['application_details'] = $this->crud_model->get_application_details();
    $page_data['page_name']  = 'about';
    $page_data['page_title'] = get_phrase('about');
    $this->load->view('backend/index', $page_data);
  }
  // software themes page
  function themes() {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $page_data['page_name']  = 'themes';
    $page_data['page_title'] = get_phrase('themes');
    $this->load->view('backend/index', $page_data);
  }
  // software mobile app page
  function mobile_app() {
    if ($this->session->userdata('admin_login') != 1)
    redirect(site_url('login'), 'refresh');

    $page_data['page_name']  = 'mobile_app';
    $page_data['page_title'] = get_phrase('mobile_app');
    $this->load->view('backend/index', $page_data);
  }

  // AJAX PORTION

  // this function is responsible for managing multiple choice question
  function manage_multiple_choices_options() {
    $page_data['number_of_options'] = $this->input->post('number_of_options');
    $this->load->view('backend/admin/manage_multiple_choices_options', $page_data);
  }

  public function ajax_get_sub_category($category_id) {
    $page_data['sub_categories'] = $this->crud_model->get_sub_categories($category_id);

    return $this->load->view('backend/admin/ajax_get_sub_category', $page_data);
  }

  public function ajax_get_section($course_id){
    $page_data['sections'] = $this->crud_model->get_section('course', $course_id)->result_array();
    return $this->load->view('backend/admin/ajax_get_section', $page_data);
  }

  public function ajax_get_video_details() {
    $video_details = $this->video_model->getVideoDetails($_POST['video_url']);
    echo $video_details['duration'];
  }
  public function ajax_sort_section() {
    $section_json = $this->input->post('itemJSON');
    $this->crud_model->sort_section($section_json);
  }
  public function ajax_sort_lesson() {
    $lesson_json = $this->input->post('itemJSON');
    $this->crud_model->sort_lesson($lesson_json);
  }
  public function ajax_sort_question() {
    $question_json = $this->input->post('itemJSON');
    $this->crud_model->sort_question($question_json);
  }
}
