<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function get_categories($param1 = "") {
        if ($param1 != "") {
            $this->db->where('id', $param1);
        }
        $this->db->where('parent', 0);
        return $this->db->get('category');
    }

    public function get_category_details_by_id($id) {
        return $this->db->get_where('category', array('id' => $id));
    }

    public function get_category_id($slug = "") {
        $category_details = $this->db->get_where('category', array('slug' => $slug))->row_array();
        return $category_details['id'];
    }

    public function add_category() {
        $data['code']   = html_escape($this->input->post('code'));
        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            }else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }

            // category thumbnail adding
            if (!file_exists('uploads/thumbnails/category_thumbnails')) {
                mkdir('uploads/thumbnails/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] == "") {
                $data['thumbnail'] = 'category-thumbnail.png';
            }else {
                $data['thumbnail'] = md5(rand(10000000, 20000000)).'.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/'.$data['thumbnail']);
            }
        }
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $this->db->insert('category', $data);
    }

    public function edit_category($param1) {
        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));
        if ($this->input->post('parent') == 0) {
            // Font awesome class adding
            if ($_POST['font_awesome_class'] != "") {
                $data['font_awesome_class'] = html_escape($this->input->post('font_awesome_class'));
            }else {
                $data['font_awesome_class'] = 'fas fa-chess';
            }
            // category thumbnail adding
            if (!file_exists('uploads/category_thumbnails')) {
                mkdir('uploads/category_thumbnails', 0777, true);
            }
            if ($_FILES['category_thumbnail']['name'] != "") {
                $data['thumbnail'] = md5(rand(10000000, 20000000)).'.jpg';
                move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], 'uploads/thumbnails/category_thumbnails/'.$data['thumbnail']);
            }
        }
        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $this->db->where('id', $param1);
        $this->db->update('category', $data);
    }

    public function delete_category($category_id) {
        $this->db->where('id', $category_id);
        $this->db->delete('category');
    }

    public function get_sub_categories($parent_id = "") {
        return $this->db->get_where('category', array('parent' => $parent_id))->result_array();
    }

    public function enrol_history($course_id = "") {
        if ($course_id > 0) {
            return $this->db->get_where('enrol', array('course_id' => $course_id));
        }else {
            return $this->db->get('enrol');
        }
    }

    public function enrol_history_by_user_id($user_id = "") {
        return $this->db->get_where('enrol', array('user_id' => $user_id));
    }

    public function all_enrolled_student() {
        $this->db->select('user_id');
        $this->db->distinct('user_id');
        return $this->db->get('enrol');
    }

    public function enrol_history_by_date_range($timestamp_start = "", $timestamp_end = "") {
        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('enrol');
    }

    public function get_revenue_by_user_type($timestamp_start = "", $timestamp_end = "", $revenue_type = "") {
        $course_ids = array();
        $courses    = array();
        $admin_details = $this->user_model->get_admin_details()->row_array();
        if ($revenue_type == 'admin_revenue') {
            //$this->db->where('user_id', $admin_details['id']);
        }elseif ($revenue_type == 'instructor_revenue') {
            $this->db->where('user_id !=', $admin_details['id']);
            $this->db->select('id');
            $courses = $this->db->get('course')->result_array();
            foreach ($courses as $course) {
                if (!in_array($course['id'], $course_ids)) {
                    array_push( $course_ids, $course['id'] );
                }
            }
            if (sizeof($course_ids)) {
                $this->db->where_in('course_id', $course_ids);
            }else {
                return array();
            }
        }

        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('payment')->result_array();
    }

    public function get_instructor_revenue($timestamp_start = "", $timestamp_end = "") {
        $course_ids = array();
        $courses    = array();

        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->select('id');
        $courses = $this->db->get('course')->result_array();
        foreach ($courses as $course) {
            if (!in_array($course['id'], $course_ids)) {
                array_push( $course_ids, $course['id'] );
            }
        }
        if (sizeof($course_ids)) {
            $this->db->where_in('course_id', $course_ids);
        }else {
            return array();
        }

        $this->db->order_by('date_added' , 'desc');
        $this->db->where('date_added >=' , $timestamp_start);
        $this->db->where('date_added <=' , $timestamp_end);
        return $this->db->get('payment')->result_array();
    }

    public function delete_payment_history($param1) {
        $this->db->where('id', $param1);
        $this->db->delete('payment');
    }
    public function delete_enrol_history($param1) {
        $this->db->where('id', $param1);
        $this->db->delete('enrol');
    }

    public function purchase_history($user_id) {
        if ($user_id > 0) {
            return $this->db->get_where('payment', array('user_id'=> $user_id));
        }else {
            return $this->db->get('payment');
        }
    }

    public function get_payment_details_by_id($payment_id = "") {
        return $this->db->get_where('payment', array('id' => $payment_id))->row_array();
    }

    public function update_instructor_payment_status($payment_id = "") {
        $updater = array(
            'instructor_payment_status' => 1
        );
        $this->db->where('id', $payment_id);
        $this->db->update('payment', $updater);
    }

    public function update_system_settings() {
        $data['value'] = html_escape($this->input->post('system_name'));
        $this->db->where('key', 'system_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_title'));
        $this->db->where('key', 'system_title');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('author'));
        $this->db->where('key', 'author');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('slogan'));
        $this->db->where('key', 'slogan');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('language'));
        $this->db->where('key', 'language');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('text_align'));
        $this->db->where('key', 'text_align');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('system_email'));
        $this->db->where('key', 'system_email');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('address'));
        $this->db->where('key', 'address');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('phone'));
        $this->db->where('key', 'phone');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('youtube_api_key'));
        $this->db->where('key', 'youtube_api_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('vimeo_api_key'));
        $this->db->where('key', 'vimeo_api_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('purchase_code'));
        $this->db->where('key', 'purchase_code');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('footer_text'));
        $this->db->where('key', 'footer_text');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('footer_link'));
        $this->db->where('key', 'footer_link');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_keywords'));
        $this->db->where('key', 'website_keywords');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('website_description'));
        $this->db->where('key', 'website_description');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('student_email_verification'));
        $this->db->where('key', 'student_email_verification');
        $this->db->update('settings', $data);
    }

    public function update_smtp_settings() {
        $data['value'] = html_escape($this->input->post('protocol'));
        $this->db->where('key', 'protocol');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_host'));
        $this->db->where('key', 'smtp_host');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_port'));
        $this->db->where('key', 'smtp_port');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_user'));
        $this->db->where('key', 'smtp_user');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('smtp_pass'));
        $this->db->where('key', 'smtp_pass');
        $this->db->update('settings', $data);
    }

    public function update_paypal_settings() {
        // update paypal keys
        $paypal_info = array();
        $paypal['active'] = $this->input->post('paypal_active');
        $paypal['mode'] = $this->input->post('paypal_mode');
        $paypal['sandbox_client_id'] = $this->input->post('sandbox_client_id');
        $paypal['production_client_id'] = $this->input->post('production_client_id');

        array_push($paypal_info, $paypal);

        $data['value']    =   json_encode($paypal_info);
        $this->db->where('key', 'paypal');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('paypal_currency'));
        $this->db->where('key', 'paypal_currency');
        $this->db->update('settings', $data);
    }

    public function update_stripe_settings() {
        // update stripe keys
        $stripe_info = array();

        $stripe['active'] = $this->input->post('stripe_active');
        $stripe['testmode'] = $this->input->post('testmode');
        $stripe['public_key'] = $this->input->post('public_key');
        $stripe['secret_key'] = $this->input->post('secret_key');
        $stripe['public_live_key'] = $this->input->post('public_live_key');
        $stripe['secret_live_key'] = $this->input->post('secret_live_key');

        array_push($stripe_info, $stripe);

        $data['value']    =   json_encode($stripe_info);
        $this->db->where('key', 'stripe_keys');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('stripe_currency'));
        $this->db->where('key', 'stripe_currency');
        $this->db->update('settings', $data);
    }

    public function update_system_currency() {
        $data['value'] = html_escape($this->input->post('system_currency'));
        $this->db->where('key', 'system_currency');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('currency_position'));
        $this->db->where('key', 'currency_position');
        $this->db->update('settings', $data);
    }

    public function update_instructor_settings() {
        $data['value'] = html_escape($this->input->post('allow_instructor'));
        $this->db->where('key', 'allow_instructor');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('instructor_revenue'));
        $this->db->where('key', 'instructor_revenue');
        $this->db->update('settings', $data);
    }

    public function get_lessons($type = "", $id = "") {
        $this->db->order_by("order", "asc");
        if($type == "course"){
            return $this->db->get_where('lesson', array('course_id' => $id));
        }
        elseif ($type == "section") {
            return $this->db->get_where('lesson', array('section_id' => $id));
        }
        elseif ($type == "lesson") {
            return $this->db->get_where('lesson', array('id' => $id));
        }
        else {
            return $this->db->get('lesson');
        }
    }

    public function add_course($param1 = "") {
        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));

        $data['title'] = html_escape($this->input->post('title'));
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['language'] = $this->input->post('language_made_in');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
        $category_details = $this->get_category_details_by_id($this->input->post('sub_category_id'))->row_array();
        $data['category_id'] = $category_details['parent'];
        $data['requirements'] = $requirements;
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        $data['is_free_course'] = $this->input->post('is_free_course');
        $data['video_url'] = html_escape($this->input->post('course_overview_url'));

        if ($this->input->post('course_overview_url') != "") {
            $data['course_overview_provider'] = html_escape($this->input->post('course_overview_provider'));
        }else {
            $data['course_overview_provider'] = "";
        }

        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['section'] = json_encode(array());
        $data['is_top_course'] = $this->input->post('is_top_course');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
        $admin_details = $this->user_model->get_admin_details()->row_array();
        if ($admin_details['id'] == $data['user_id']) {
            $data['is_admin'] = 1;
        }else {
            $data['is_admin'] = 0;
        }
        if ($param1 == "save_to_draft") {
            $data['status'] = 'draft';
        }else{
            $data['status'] = 'pending';
        }
        $this->db->insert('course', $data);

        $course_id = $this->db->insert_id();
        // Create folder if does not exist
        if (!file_exists('uploads/thumbnails/course_thumbnails')) {
            mkdir('uploads/thumbnails/course_thumbnails', 0777, true);
        }

        if ($_FILES['course_thumbnail']['name'] != "") {
            move_uploaded_file($_FILES['course_thumbnail']['tmp_name'], 'uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg');
        }
        if ($data['status'] == 'approved') {
            $this->session->set_flashdata('flash_message', get_phrase('course_added_successfully'));
        }elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('course_added_successfully').'. '.get_phrase('please_wait_untill_Admin_approves_it'));
        }elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_course_has_been_added_to_draft'));
        }
    }

    function trim_and_return_json($untrimmed_array) {
        $trimmed_array = array();
        if(sizeof($untrimmed_array) > 0){
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    array_push($trimmed_array, $row);
                }
            }
        }
        return json_encode($trimmed_array);
    }

    public function update_course($course_id, $type = "") {
        $outcomes = $this->trim_and_return_json($this->input->post('outcomes'));
        $requirements = $this->trim_and_return_json($this->input->post('requirements'));
        $data['title'] = $this->input->post('title');
        $data['short_description'] = $this->input->post('short_description');
        $data['description'] = $this->input->post('description');
        $data['outcomes'] = $outcomes;
        $data['language'] = $this->input->post('language_made_in');
        $data['category_id'] = $this->input->post('category_id');
        $data['sub_category_id'] = $this->input->post('sub_category_id');
        $data['requirements'] = $requirements;
        $data['is_free_course'] = $this->input->post('is_free_course');
        $data['price'] = $this->input->post('price');
        $data['discount_flag'] = $this->input->post('discount_flag');
        $data['discounted_price'] = $this->input->post('discounted_price');
        $data['level'] = $this->input->post('level');
        $data['video_url'] = $this->input->post('course_overview_url');

        if ($this->input->post('course_overview_url') != "") {
            $data['course_overview_provider'] = html_escape($this->input->post('course_overview_provider'));
        }else {
            $data['course_overview_provider'] = "";
        }

        $data['meta_description'] = $this->input->post('meta_description');
        $data['meta_keywords'] = $this->input->post('meta_keywords');
        $data['last_modified'] = strtotime(date('D, d-M-Y'));

        if ($this->input->post('is_top_course') != 1) {
            $data['is_top_course'] = 0;
        }else {
            $data['is_top_course'] = 1;
        }


        if ($type == "save_to_draft") {
            $data['status'] = 'draft';
        }else{
            $data['status'] = 'pending';
        }
        $this->db->where('id', $course_id);
        $this->db->update('course', $data);

        if ($_FILES['course_thumbnail']['name'] != "") {
            move_uploaded_file($_FILES['course_thumbnail']['tmp_name'], 'uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg');
        }
        if ($data['status'] == 'approved') {
            $this->session->set_flashdata('flash_message', get_phrase('course_updated_successfully'));
        }elseif ($data['status'] == 'pending') {
            $this->session->set_flashdata('flash_message', get_phrase('course_updated_successfully').'. '.get_phrase('please_wait_untill_Admin_approves_it'));
        }elseif ($data['status'] == 'draft') {
            $this->session->set_flashdata('flash_message', get_phrase('your_course_has_been_added_to_draft'));
        }
    }

    public function change_course_status($status = "", $course_id = "") {
        $updater = array(
            'status' => $status
        );
        $this->db->where('id', $course_id);
        $this->db->update('course', $updater);
    }

    public function get_course_thumbnail_url($course_id) {

        if (file_exists('uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg'))
        return base_url().'uploads/thumbnails/course_thumbnails/'.$course_id.'.jpg';
        else
        return base_url().'uploads/thumbnails/course_thumbnails/course-thumbnail.png';
    }
    public function get_lesson_thumbnail_url($lesson_id) {

        if (file_exists('uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg'))
        return base_url().'uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg';
        else
        return base_url().'uploads/thumbnails/thumbnail.png';
    }

    public function get_my_courses_by_category_id($category_id) {
        $this->db->select('course_id');
        $course_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $course_ids = array();
        foreach ($course_lists_by_enrol as $row) {
            if (!in_array($row['course_id'], $course_ids)) {
                array_push($course_ids, $row['course_id']);
            }
        }
        $this->db->where_in('id', $course_ids);
        $this->db->where('category_id', $category_id);
        return $this->db->get('course');
    }

    public function get_my_courses_by_search_string($search_string) {
        $this->db->select('course_id');
        $course_lists_by_enrol = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id')))->result_array();
        $course_ids = array();
        foreach ($course_lists_by_enrol as $row) {
            if (!in_array($row['course_id'], $course_ids)) {
                array_push($course_ids, $row['course_id']);
            }
        }
        $this->db->where_in('id', $course_ids);
        $this->db->like('title', $search_string);
        return $this->db->get('course');
    }

    public function get_courses_by_search_string($search_string) {
        $this->db->like('title', $search_string);
        $this->db->where('status', 'active');
        return $this->db->get('course');
    }


    public function get_course_by_id($course_id = "") {
        return $this->db->get_where('course', array('id' => $course_id));
    }

    public function delete_course($course_id) {
        $this->db->where('id', $course_id);
        $this->db->delete('course');
    }

    public function get_top_courses() {
        return $this->db->get_where('course', array('is_top_course' => 1, 'status' => 'active'));
    }

    public function get_default_category_id() {
        $categories = $this->get_categories()->result_array();
        foreach ($categories as $category) {
            return $category['id'];
        }
    }

    public function get_courses_by_user_id($param1 = "") {
        $courses['draft'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'draft'));
        $courses['pending'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'pending'));
        $courses['active'] = $this->db->get_where('course', array('user_id' => $param1, 'status' => 'active'));
        return $courses;
    }

    public function get_status_wise_courses($status = "") {
        if ($status != "") {
            $courses = $this->db->get_where('course', array('status' => $status));
        }else {
            $courses['draft'] = $this->db->get_where('course', array('status' => 'draft'));
            $courses['pending'] = $this->db->get_where('course', array('status' => 'pending'));
            $courses['active'] = $this->db->get_where('course', array('status' => 'active'));
        }
        return $courses;
    }

    public function get_status_wise_courses_for_instructor($status = "") {
        if ($status != "") {
            $this->db->where('status', $status);
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses = $this->db->get('course');
        }else {
            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses['draft'] = $this->db->get('course');

            $this->db->where('user_id', $this->session->userdata('user_id'));
            $this->db->where('status', 'draft');
            $courses['pending'] = $this->db->get('course');

            $this->db->where('status', 'draft');
            $this->db->where('user_id', $this->session->userdata('user_id'));
            $courses['active'] = $this->db->get_where('course');
        }
        return $courses;
    }

    public function get_default_sub_category_id($default_cateegory_id) {
        $sub_categories = $this->get_sub_categories($default_cateegory_id);
        foreach ($sub_categories as $sub_category) {
            return $sub_category['id'];
        }
    }

    public function get_instructor_wise_courses($instructor_id = "", $return_as = "") {
        $courses = $this->db->get_where('course', array('user_id' => $instructor_id));
        if ($return_as == 'simple_array') {
            $array = array();
            foreach ($courses->result_array() as $course) {
                if (!in_array($course['id'], $array)) {
                    array_push($array, $course['id']);
                }
            }
            return $array;
        }else {
            return $courses;
        }
    }

    public function get_instructor_wise_payment_history($instructor_id = "") {
        $courses = $this->get_instructor_wise_courses($instructor_id, 'simple_array');
        if (sizeof($courses) > 0) {
            $this->db->where_in('course_id', $courses);
            return $this->db->get('payment')->result_array();
        }else {
            return array();
        }
    }

    public function add_section($course_id) {
        $data['title'] = html_escape($this->input->post('title'));
        $data['course_id'] = $course_id;
        $this->db->insert('section', $data);
        $section_id = $this->db->insert_id();

        $course_details = $this->get_course_by_id($course_id)->row_array();
        $previous_sections = json_decode($course_details['section']);

        if (sizeof($previous_sections) > 0) {
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        }else {
            $previous_sections = array();
            array_push($previous_sections, $section_id);
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        }
    }

    public function edit_section($section_id) {
        $data['title'] = $this->input->post('title');
        $this->db->where('id', $section_id);
        $this->db->update('section', $data);
    }

    public function delete_section($course_id, $section_id) {
        $this->db->where('id', $section_id);
        $this->db->delete('section');

        $course_details = $this->get_course_by_id($course_id)->row_array();
        $previous_sections = json_decode($course_details['section']);

        if (sizeof($previous_sections) > 0) {
            $new_section = array();
            for ($i = 0; $i < sizeof($previous_sections); $i++) {
                if ($previous_sections[$i] != $section_id) {
                    array_push($new_section, $previous_sections[$i]);
                }
            }
            $updater['section'] = json_encode($new_section);
            $this->db->where('id', $course_id);
            $this->db->update('course', $updater);
        }
    }

    public function get_section($type_by, $id){
        $this->db->order_by("order", "asc");
        if ($type_by == 'course') {
            return $this->db->get_where('section', array('course_id' => $id));
        }elseif ($type_by == 'section') {
            return $this->db->get_where('section', array('id' => $id));
        }
    }

    public function serialize_section($course_id, $serialization) {
        $updater = array(
            'section' => $serialization
        );
        $this->db->where('id', $course_id);
        $this->db->update('course', $updater);
    }

    public function add_lesson() {
        $data['course_id'] = html_escape($this->input->post('course_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            }elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_video_url') == "" || $this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('html5_video_url'));
                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;
                $data['video_type'] = 'html5';
            }else {
                $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
            }
        }else {
            if ($_FILES['attachment']['name'] == "") {
                $this->session->set_flashdata('error_message',get_phrase('invalid_attachment'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
            }else {
                $fileName           = $_FILES['attachment']['name'];
                $tmp                = explode('.', $fileName);
                $fileExtension      = end($tmp);
                $uploadable_file    =  md5(uniqid(rand(), true)).'.'.$fileExtension;
                $data['attachment'] = $uploadable_file;

                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/'.$uploadable_file);
            }
        }

        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');

        $this->db->insert('lesson', $data);
        $inserted_id = $this->db->insert_id();

        if ($_FILES['thumbnail']['name'] != "") {
            if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
            }
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/'.$inserted_id.'.jpg');
        }
    }

    public function edit_lesson($lesson_id) {

        $previous_data = $this->db->get_where('lesson', array('id' => $lesson_id))->row_array();

        $data['course_id'] = html_escape($this->input->post('course_id'));
        $data['title'] = html_escape($this->input->post('title'));
        $data['section_id'] = html_escape($this->input->post('section_id'));

        $lesson_type_array = explode('-', $this->input->post('lesson_type'));
        $lesson_type = $lesson_type_array[0];

        $data['attachment_type'] = $lesson_type_array[1];
        $data['lesson_type'] = $lesson_type;

        if($lesson_type == 'video') {
            $lesson_provider = $this->input->post('lesson_provider');
            if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
                if ($this->input->post('video_url') == "" || $this->input->post('duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('video_url'));

                $duration_formatter = explode(':', $this->input->post('duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;

                $video_details = $this->video_model->getVideoDetails($data['video_url']);
                $data['video_type'] = $video_details['provider'];
            }elseif ($lesson_provider == 'html5') {
                if ($this->input->post('html5_video_url') == "" || $this->input->post('html5_duration') == "") {
                    $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_url_and_duration'));
                    redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
                }
                $data['video_url'] = html_escape($this->input->post('html5_video_url'));

                $duration_formatter = explode(':', $this->input->post('html5_duration'));
                $hour = sprintf('%02d', $duration_formatter[0]);
                $min = sprintf('%02d', $duration_formatter[1]);
                $sec = sprintf('%02d', $duration_formatter[2]);
                $data['duration'] = $hour.':'.$min.':'.$sec;
                $data['video_type'] = 'html5';

                if ($_FILES['thumbnail']['name'] != "") {
                    if (!file_exists('uploads/thumbnails/lesson_thumbnails')) {
                        mkdir('uploads/thumbnails/lesson_thumbnails', 0777, true);
                    }
                    move_uploaded_file($_FILES['thumbnail']['tmp_name'], 'uploads/thumbnails/lesson_thumbnails/'.$lesson_id.'.jpg');
                }
            }else {
                $this->session->set_flashdata('error_message',get_phrase('invalid_lesson_provider'));
                redirect(site_url(strtolower($this->session->userdata('role')).'/course_form/course_edit/'.$data['course_id']), 'refresh');
            }
            $data['attachment'] = "";
        }else {
            if ($_FILES['attachment']['name'] != "") {
                // unlinking previous attachments
                if ($previous_data['attachment'] != "") {
                    unlink('uploads/lesson_files/'.$previous_data['attachment']);
                }

                $fileName           = $_FILES['attachment']['name'];
                $tmp                = explode('.', $fileName);
                $fileExtension      = end($tmp);
                $uploadable_file    =  md5(uniqid(rand(), true)).'.'.$fileExtension;
                $data['attachment'] = $uploadable_file;
                $data['video_type'] = "";
                $data['duration'] = "";
                $data['video_url'] = "";
                if (!file_exists('uploads/lesson_files')) {
                    mkdir('uploads/lesson_files', 0777, true);
                }
                move_uploaded_file($_FILES['attachment']['tmp_name'], 'uploads/lesson_files/'.$uploadable_file);
            }
        }

        $data['last_modified'] = strtotime(date('D, d-M-Y'));
        $data['summary'] = $this->input->post('summary');

        $this->db->where('id', $lesson_id);
        $this->db->update('lesson', $data);
    }
    public function delete_lesson($lesson_id) {
        $this->db->where('id', $lesson_id);
        $this->db->delete('lesson');
    }

    public function update_frontend_settings() {
        $data['value'] = html_escape($this->input->post('banner_title'));
        $this->db->where('key', 'banner_title');
        $this->db->update('frontend_settings', $data);

        $data['value'] = html_escape($this->input->post('banner_sub_title'));
        $this->db->where('key', 'banner_sub_title');
        $this->db->update('frontend_settings', $data);


        $data['value'] = $this->input->post('about_us');
        $this->db->where('key', 'about_us');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('terms_and_condition');
        $this->db->where('key', 'terms_and_condition');
        $this->db->update('frontend_settings', $data);

        $data['value'] = $this->input->post('privacy_policy');
        $this->db->where('key', 'privacy_policy');
        $this->db->update('frontend_settings', $data);
    }

    public function update_frontend_banner() {
        move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/system/home-banner.jpg');
    }

    public function update_light_logo() {
        move_uploaded_file($_FILES['light_logo']['tmp_name'], 'uploads/system/logo-light.png');
    }

    public function update_dark_logo() {
        move_uploaded_file($_FILES['dark_logo']['tmp_name'], 'uploads/system/logo-dark.png');
    }

    public function update_small_logo() {
        move_uploaded_file($_FILES['small_logo']['tmp_name'], 'uploads/system/logo-light-sm.png');
    }

    public function update_favicon() {
        move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/system/favicon.png');
    }

    public function handleWishList($course_id) {
        $wishlists = array();
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        if ($user_details['wishlist'] == "") {
            array_push($wishlists, $course_id);
        }else {
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($course_id, $wishlists)) {
                $container = array();
                foreach ($wishlists as $key) {
                    if ($key != $course_id) {
                        array_push($container, $key);
                    }
                }
                $wishlists = $container;
                // $key = array_search($course_id, $wishlists);
                // unset($wishlists[$key]);
            }else {
                array_push($wishlists, $course_id);
            }
        }

        $updater['wishlist'] = json_encode($wishlists);
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', $updater);
    }

    public function is_added_to_wishlist($course_id = "") {
        if ($this->session->userdata('user_login') == 1) {
            $wishlists = array();
            $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
            $wishlists = json_decode($user_details['wishlist']);
            if (in_array($course_id, $wishlists)) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function getWishLists() {
        $user_details = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        return json_decode($user_details['wishlist']);
    }

    public function get_latest_10_course() {
        $this->db->order_by("id", "desc");
        $this->db->limit('10');
        $this->db->where('status', 'active');
        return $this->db->get('course')->result_array();
    }

    public function enrol_student($user_id){
        $purchased_courses = $this->session->userdata('cart_items');
        foreach ($purchased_courses as $purchased_course) {
            $data['user_id'] = $user_id;
            $data['course_id'] = $purchased_course;
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('enrol', $data);
        }
    }
    public function enrol_a_student_manually() {
        $data['course_id'] = $this->input->post('course_id');
        $data['user_id']   = $this->input->post('user_id');
        if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
            $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_course'));
        }else {
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('enrol', $data);
            $this->session->set_flashdata('flash_message', get_phrase('student_has_been_enrolled_to_that_course'));
        }
    }

    public function enrol_to_free_course($course_id = "", $user_id = "") {
        $course_details = $this->get_course_by_id($course_id)->row_array();
        if ($course_details['is_free_course'] == 1) {
            $data['course_id'] = $course_id;
            $data['user_id']   = $user_id;
            if ($this->db->get_where('enrol', $data)->num_rows() > 0) {
                $this->session->set_flashdata('error_message', get_phrase('student_has_already_been_enrolled_to_this_course'));
            }else {
                $data['date_added'] = strtotime(date('D, d-M-Y'));
                $this->db->insert('enrol', $data);
                $this->session->set_flashdata('flash_message', get_phrase('successfully_enrolled'));
            }
        }else {
            $this->session->set_flashdata('error_message', get_phrase('this_course_is_not_free_at_all'));
            redirect(site_url('home/course/'.slugify($course_details['title']).'/'.$course_id), 'refresh');
        }

    }
    public function course_purchase($user_id, $method, $amount_paid) {
        $purchased_courses = $this->session->userdata('cart_items');
        foreach ($purchased_courses as $purchased_course) {
            $data['user_id'] = $user_id;
            $data['payment_type'] = $method;
            $data['course_id'] = $purchased_course;
            $course_details = $this->get_course_by_id($purchased_course)->row_array();
            if ($course_details['discount_flag'] == 1) {
                $data['amount'] = $course_details['discounted_price'];
            }else {
                $data['amount'] = $course_details['price'];
            }
            if (get_user_role('role_id', $course_details['user_id']) == 1) {
                $data['admin_revenue'] = $data['amount'];
                $data['instructor_revenue'] = 0;
                $data['instructor_payment_status'] = 1;
            }else {
                if (get_settings('allow_instructor') == 1) {
                    $instructor_revenue_percentage = get_settings('instructor_revenue');
                    $data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
                    $data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
                }else {
                    $data['instructor_revenue'] = 0;
                    $data['admin_revenue'] = $data['amount'];
                }
                $data['instructor_payment_status'] = 0;
            }
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('payment', $data);
        }
    }

    public function get_default_lesson($section_id) {
        $this->db->order_by('order',"asc");
        $this->db->limit(1);
        $this->db->where('section_id', $section_id);
        return $this->db->get('lesson');
    }

    public function get_courses_by_wishlists() {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            return $this->db->get('course')->result_array();
        }else {
            return array();
        }

    }


    public function get_courses_of_wishlists_by_search_string($search_string) {
        $wishlists = $this->getWishLists();
        if (sizeof($wishlists) > 0) {
            $this->db->where_in('id', $wishlists);
            $this->db->like('title', $search_string);
            return $this->db->get('course')->result_array();
        }else {
            return array();
        }
    }

    public function get_total_duration_of_lesson_by_course_id($course_id) {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('course', $course_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != "other") {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    }

    public function get_total_duration_of_lesson_by_section_id($section_id) {
        $total_duration = 0;
        $lessons = $this->crud_model->get_lessons('section', $section_id)->result_array();
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_type'] != 'other') {
                $time_array = explode(':', $lesson['duration']);
                $hour_to_seconds = $time_array[0] * 60 * 60;
                $minute_to_seconds = $time_array[1] * 60;
                $seconds = $time_array[2];
                $total_duration += $hour_to_seconds + $minute_to_seconds + $seconds;
            }
        }
        return gmdate("H:i:s", $total_duration).' '.get_phrase('hours');
    }

    public function rate($data) {
        if ($this->db->get_where('rating', array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']))->num_rows() == 0) {
            $this->db->insert('rating', $data);
        }else {
            $checker = array('user_id' => $data['user_id'], 'ratable_id' => $data['ratable_id'], 'ratable_type' => $data['ratable_type']);
            $this->db->where($checker);
            $this->db->update('rating', $data);
        }
    }

    public function get_user_specific_rating($ratable_type = "", $ratable_id = "") {
        return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'user_id' => $this->session->userdata('user_id'), 'ratable_id' => $ratable_id))->row_array();
    }

    public function get_ratings($ratable_type = "", $ratable_id = "", $is_sum = false) {
        if ($is_sum) {
            $this->db->select_sum('rating');
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));

        }else {
            return $this->db->get_where('rating', array('ratable_type' => $ratable_type, 'ratable_id' => $ratable_id));
        }
    }
    public function get_instructor_wise_course_ratings($instructor_id = "", $ratable_type = "", $is_sum = false) {
        $course_ids = $this->get_instructor_wise_courses($instructor_id, 'simple_array');
        if ($is_sum) {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $course_ids);
            $this->db->select_sum('rating');
            return $this->db->get('rating');

        }else {
            $this->db->where('ratable_type', $ratable_type);
            $this->db->where_in('ratable_id', $course_ids);
            return $this->db->get('rating');
        }
    }
    public function get_percentage_of_specific_rating($rating = "", $ratable_type = "", $ratable_id = "") {
        $number_of_user_rated = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id
        ))->num_rows();

        $number_of_user_rated_the_specific_rating = $this->db->get_where( 'rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id,
            'rating'       => $rating
        ))->num_rows();

        //return $number_of_user_rated.' '.$number_of_user_rated_the_specific_rating;
        if ($number_of_user_rated_the_specific_rating > 0) {
            $percentage = ($number_of_user_rated_the_specific_rating / $number_of_user_rated) * 100;
        }else {
            $percentage = 0;
        }
        return floor($percentage);
    }

    ////////private message//////
    function send_new_private_message() {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $receiver   = $this->input->post('receiver');
        $sender     = $this->session->userdata('user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->num_rows();
        $num2 = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->num_rows();
        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['receiver']            = $receiver;
            $this->db->insert('message_thread', $data_message_thread);
        }
        if ($num1 > 0)
        $message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'receiver' => $receiver))->row()->message_thread_code;
        if ($num2 > 0)
        $message_thread_code = $this->db->get_where('message_thread', array('sender' => $receiver, 'receiver' => $sender))->row()->message_thread_code;


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code) {
        $message    = html_escape($this->input->post('message'));
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('user_id');

        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
            $unread_message_counter++;
        }
        return $unread_message_counter;
    }

    public function get_last_message_by_message_thread_code($message_thread_code) {
        $this->db->order_by('message_id','desc');
        $this->db->limit(1);
        $this->db->where(array('message_thread_code' => $message_thread_code));
        return $this->db->get('message');
    }

    function curl_request($code = '') {

        $product_code = $code;

        $personal_token = "FkA9UyDiQT0YiKwYLK3ghyFNRVV9SeUn";
        $url = "https://api.envato.com/v3/market/author/sale?code=".$product_code;
        $curl = curl_init($url);

        //setting the header for the rest of the api
        $bearer   = 'bearer ' . $personal_token;
        $header   = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Authorization: ' . $bearer;

        $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:'.$product_code.'.json';
            $ch_verify = curl_init( $verify_url . '?code=' . $product_code );

            curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
            curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            $cinit_verify_data = curl_exec( $ch_verify );
            curl_close( $ch_verify );

            $response = json_decode($cinit_verify_data, true);

            if (count($response['verify-purchase']) > 0) {
                return true;
            } else {
                return false;
            }
        }


        // version 1.3
        function get_currencies() {
            return $this->db->get('currency')->result_array();
        }

        function get_paypal_supported_currencies() {
            $this->db->where('paypal_supported', 1);
            return $this->db->get('currency')->result_array();
        }

        function get_stripe_supported_currencies() {
            $this->db->where('stripe_supported', 1);
            return $this->db->get('currency')->result_array();
        }

        // version 1.4
        function filter_course($selected_category_id = "", $selected_price = "", $selected_level = "", $selected_language = "", $selected_rating = ""){
            //echo $selected_category_id.' '.$selected_price.' '.$selected_level.' '.$selected_language.' '.$selected_rating;

            $course_ids = array();
            if ($selected_category_id != "all") {
                $this->db->where('sub_category_id', $selected_category_id);
            }

            if ($selected_price != "all") {
                if ($selected_price == "paid") {
                    $this->db->where('is_free_course', null);
                }elseif ($selected_price == "free") {
                    $this->db->where('is_free_course', 1);
                }
            }

            if ($selected_level != "all") {
                $this->db->where('level', $selected_level);
            }

            if ($selected_language != "all") {
                $this->db->where('language', $selected_language);
            }
            $this->db->where('status', 'active');
            $courses = $this->db->get('course')->result_array();

            foreach ($courses as $course) {
                if ($selected_rating != "all") {
                    $total_rating =  $this->get_ratings('course', $course['id'], true)->row()->rating;
                    $number_of_ratings = $this->get_ratings('course', $course['id'])->num_rows();
                    if ($number_of_ratings > 0) {
                        $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                        if ($average_ceil_rating == $selected_rating) {
                            array_push($course_ids, $course['id']);
                        }
                    }
                }else {
                    array_push($course_ids, $course['id']);
                }
            }

            if (count($course_ids) > 0) {
                $this->db->where_in('id', $course_ids);
                return $this->db->get('course')->result_array();
            }else {
                return array();
            }
        }

        public function get_courses($category_id = "", $sub_category_id = "", $instructor_id = 0) {
            if ($category_id > 0 && $sub_category_id > 0 && $instructor_id > 0) {
                return $this->db->get_where('course', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id, 'user_id' => $instructor_id));
            }elseif ($category_id > 0 && $sub_category_id > 0 && $instructor_id == 0) {
                return $this->db->get_where('course', array('category_id' => $category_id, 'sub_category_id' => $sub_category_id));
            }else {
                return $this->db->get('course');
            }
        }

        public function filter_course_for_backend($category_id, $instructor_id, $price, $status) {
            if ($category_id != "all") {
                $this->db->where('sub_category_id', $category_id);
            }

            if ($price != "all") {
                if ($price == "paid") {
                    $this->db->where('is_free_course', null);
                }elseif ($price == "free") {
                    $this->db->where('is_free_course', 1);
                }
            }

            if ($instructor_id != "all") {
                $this->db->where('user_id', $instructor_id);
            }

            if ($status != "all") {
                $this->db->where('status', $status);
            }
            return $this->db->get('course')->result_array();
        }

        public function sort_section($section_json) {
            $sections = json_decode($section_json);
            foreach ($sections as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('section', $updater);
            }
        }

        public function sort_lesson($lesson_json) {
            $lessons = json_decode($lesson_json);
            foreach ($lessons as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('lesson', $updater);
            }
        }
        public function sort_question($question_json) {
            $questions = json_decode($question_json);
            foreach ($questions as $key => $value) {
                $updater = array(
                    'order' => $key + 1
                );
                $this->db->where('id', $value);
                $this->db->update('question', $updater);
            }
        }

        public function get_free_and_paid_courses($price_status = "", $instructor_id = "") {
            $this->db->where('status', 'active');
            if ($price_status == 'free') {
                $this->db->where('is_free_course', 1);
            }else {
                $this->db->where('is_free_course', null);
            }

            if ($instructor_id > 0) {
                $this->db->where('user_id', $instructor_id);
            }
            return $this->db->get('course');
        }

        // Adding quiz functionalities
        public function add_quiz($course_id = "") {
            $data['course_id'] = $course_id;
            $data['title'] = html_escape($this->input->post('title'));
            $data['section_id'] = html_escape($this->input->post('section_id'));

            $data['lesson_type'] = 'quiz';
            $data['duration'] = '00:00:00';
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $data['summary'] = html_escape($this->input->post('summary'));
            $this->db->insert('lesson', $data);
        }

        // updating quiz functionalities
        public function edit_quiz($lesson_id = "") {
            $data['title'] = html_escape($this->input->post('title'));
            $data['section_id'] = html_escape($this->input->post('section_id'));
            $data['last_modified'] = strtotime(date('D, d-M-Y'));
            $data['summary'] = html_escape($this->input->post('summary'));
            $this->db->where('id', $lesson_id);
            $this->db->update('lesson', $data);
        }

        // Get quiz questions
        public function get_quiz_questions($quiz_id) {
            $this->db->order_by("order", "asc");
            $this->db->where('quiz_id', $quiz_id);
            return $this->db->get('question');
        }

        public function get_quiz_question_by_id($question_id) {
            $this->db->order_by("order", "asc");
            $this->db->where('id', $question_id);
            return $this->db->get('question');
        }

        // Add Quiz Questions
        public function add_quiz_questions($quiz_id) {
            $question_type = $this->input->post('question_type');
            if ($question_type == 'mcq') {
                $response = $this->add_multiple_choice_question($quiz_id);
                return $response;
            }
        }

        public function update_quiz_questions($question_id) {
            $question_type = $this->input->post('question_type');
            if ($question_type == 'mcq') {
                $response = $this->update_multiple_choice_question($question_id);
                return $response;
            }
        }
        // multiple_choice_question crud functions
        function add_multiple_choice_question($quiz_id){
            if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
                return false;
            }
            foreach ($this->input->post('options') as $option) {
                if ($option == "") {
                    return false;
                }
            }
            if (sizeof($this->input->post('correct_answers')) == 0) {
                $correct_answers = [""];
            }
            else{
                $correct_answers = $this->input->post('correct_answers');
            }
            $data['quiz_id']            = $quiz_id;
            $data['title']              = html_escape($this->input->post('title'));
            $data['number_of_options']  = html_escape($this->input->post('number_of_options'));
            $data['type']               = 'multiple_choice';
            $data['options']            = json_encode($this->input->post('options'));
            $data['correct_answers']    = json_encode($correct_answers);
            $this->db->insert('question', $data);
            return true;
        }
        // update multiple choice question
        function update_multiple_choice_question($question_id){
            if (sizeof($this->input->post('options')) != $this->input->post('number_of_options')) {
                return false;
            }
            foreach ($this->input->post('options') as $option) {
                if ($option == "") {
                    return false;
                }
            }

            if (sizeof($this->input->post('correct_answers')) == 0) {
                $correct_answers = [""];
            }
            else{
                $correct_answers = $this->input->post('correct_answers');
            }

            $data['title']              = html_escape($this->input->post('title'));
            $data['number_of_options']  = html_escape($this->input->post('number_of_options'));
            $data['type']               = 'multiple_choice';
            $data['options']            = json_encode($this->input->post('options'));
            $data['correct_answers']    = json_encode($correct_answers);
            $this->db->where('id', $question_id);
            $this->db->update('question', $data);
            return true;
        }

        function delete_quiz_question($question_id) {
            $this->db->where('id', $question_id);
            $this->db->delete('question');
            return true;
        }

        function get_application_details() {
            $purchase_code = get_settings('purchase_code');
            $returnable_array = array(
                'purchase_code_status' => get_phrase('not_found'),
                'support_expiry_date'  => get_phrase('not_found'),
                'customer_name'        => get_phrase('not_found')
            );

            $personal_token = "gC0J1ZpY53kRpynNe4g2rWT5s4MW56Zg";
            $url = "https://api.envato.com/v3/market/author/sale?code=".$purchase_code;
            $curl = curl_init($url);

            //setting the header for the rest of the api
            $bearer   = 'bearer ' . $personal_token;
            $header   = array();
            $header[] = 'Content-length: 0';
            $header[] = 'Content-type: application/json; charset=utf-8';
            $header[] = 'Authorization: ' . $bearer;

            $verify_url = 'https://api.envato.com/v1/market/private/user/verify-purchase:'.$purchase_code.'.json';
                $ch_verify = curl_init( $verify_url . '?code=' . $purchase_code );

                curl_setopt( $ch_verify, CURLOPT_HTTPHEADER, $header );
                curl_setopt( $ch_verify, CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch_verify, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch_verify, CURLOPT_CONNECTTIMEOUT, 5 );
                curl_setopt( $ch_verify, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

                $cinit_verify_data = curl_exec( $ch_verify );
                curl_close( $ch_verify );

                $response = json_decode($cinit_verify_data, true);

                if (count($response['verify-purchase']) > 0) {

                    //print_r($response);
                    $item_name 				= $response['verify-purchase']['item_name'];
                    $purchase_time 			= $response['verify-purchase']['created_at'];
                    $customer 				= $response['verify-purchase']['buyer'];
                    $licence_type 			= $response['verify-purchase']['licence'];
                    $support_until			= $response['verify-purchase']['supported_until'];
                    $customer 				= $response['verify-purchase']['buyer'];

                    $purchase_date			= date("d M, Y", strtotime($purchase_time));

                    $todays_timestamp 		= strtotime(date("d M, Y"));
                    $support_expiry_timestamp = strtotime($support_until);

                    $support_expiry_date	= date("d M, Y", $support_expiry_timestamp);

                    if ($todays_timestamp > $support_expiry_timestamp)
                    $support_status		= get_phrase('expired');
                    else
                    $support_status		= get_phrase('valid');

                    $returnable_array = array(
                        'purchase_code_status' => $support_status,
                        'support_expiry_date'  => $support_expiry_date,
                        'customer_name'        => $customer
                    );
                }
                else {
                    $returnable_array = array(
                        'purchase_code_status' => 'invalid',
                        'support_expiry_date'  => 'invalid',
                        'customer_name'        => 'invalid'
                    );
                }

                return $returnable_array;
            }
        }
