<?php 

// Creating End Points
function kh_rest_api_init () {
  // example.com/wp-json/wd/v1/signup
  register_rest_route('kh/v1', '/currentKharam/users', [
    'methods' => WP_REST_SERVER::CREATABLE,
    'callback' => 'kh_rest_api_users_create_handler',
    'permission_callback' => '__return_true'
  ]);
  register_rest_route('kh/v1', '/currentKharam/users', [
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'kh_rest_api_users_read_handler',
    'permission_callback' => '__return_true'
  ]);


  register_rest_route('kh/v1', '/complete', [
    'methods' => WP_REST_SERVER::EDITABLE,
    'callback' => 'kh_rest_api_complete_handler',
    'permission_callback' => '__return_true'
  ]);
}