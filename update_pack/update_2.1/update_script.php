<?php
	$CI = get_instance();
	$CI->load->database();
	$CI->load->dbforge();

	// insert data on settings table
	$settings_data = array( 'key' => 'student_email_verification', 'value' => 'enable' );
	$CI->db->insert('settings', $settings_data);

	// Update version number
	$data = array('value' => '2.1');
	$this->db->where('key', 'version');
	$this->db->update('settings', $data);
?>
