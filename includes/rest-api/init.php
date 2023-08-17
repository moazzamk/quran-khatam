<?php 

// Creating End Points
function kh_rest_api_init () {
  // CURRENT KHATAM SIGNUP
  register_rest_route('kh/v1', '/currentKhatam/signup', [
    'methods' => WP_REST_SERVER::CREATABLE,
    'callback' => 'kh_rest_api_current_khatam_singup_handler',
    'permission_callback' => '__return_true'
  ]);

  // CURRENT KHATAM COMPLETE JUZ
  register_rest_route('kh/v1', '/currentKhatam/completejuz', [
    'methods' => WP_REST_SERVER::CREATABLE,
    'callback' => 'kh_rest_api_current_khatam_complete_juz_handler',
    'permission_callback' => '__return_true'
  ]);

  // CURRENT KHATAM USERS
  register_rest_route('kh/v1', '/currentKhatam', [
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'kh_rest_api_current_khatam_read_handler',
    'permission_callback' => '__return_true'
  ]);
}