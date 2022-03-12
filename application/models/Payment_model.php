<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function stripe_payment($token_id = "", $user_id = "", $amount_paid = "", $stripe_secret_key = "") {
        $user_details = $this->user_model->get_all_user($user_id)->row_array();

        require_once(APPPATH.'libraries/Stripe/init.php');
        \Stripe\Stripe::setApiKey($stripe_secret_key);

        $customer = \Stripe\Customer::create(array(
            'email' => $user_details['email'], // client email id
            'card'  => $token_id
        ));

        $charge = \Stripe\Charge::create(['customer'  => $customer->id, 'amount' => $amount_paid*100, 'currency' => get_settings('stripe_currency'), 'receipt_email' => $user_details['email']]);

        if($charge->status == 'succeeded'){
            return true;
        }else {
            $this->session->set_flashdata('error_message', get_phrase('an_error_occurred_during_payment'));
            redirect('home', 'refresh');
        }
    }
}
