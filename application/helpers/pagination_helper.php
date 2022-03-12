<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! function_exists('pagintaion'))
{
    function pagintaion($total_rows, $per_page_item){
        $config['per_page']        = $per_page_item;
        $config['num_links']       = 2;
        $config['total_rows']      = $total_rows;
        $config['full_tag_open']   = '<ul class="pagination justify-content-center">';
        $config['full_tag_close']  = '</ul>';
        $config['prev_link']       = '<i class="fas fa-chevron-left"></i>';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '<i class="fas fa-chevron-right"></i>';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active disabled"> <span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        return $config;
    }
}
