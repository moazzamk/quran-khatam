<?php
/**
 * @package Khatam
 */
/*
Plugin Name: Quran Khatam
Plugin URI: https://moazzam-khan.com
Version: 1.0.0
Author: Moazzam Khan
License: GPLv2 or later
*/

use Khatam\Main;

/** Autoloader */
require __DIR__ . '/vendor/autoload.php';

// This is needed for wpdb to exist here
global $wpdb;

define('KHATAM_URL', plugin_dir_url(__FILE__));
define('KHATAM_USER_LIMIT', 30);


if (!function_exists('add_action')) {
	echo 'You done goofed, son!';
	exit;
}

// Include common styles and scripts
wp_enqueue_style('jquery-wp-cs', KHATAM_URL);
wp_enqueue_style('material', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css');
wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('material', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js');


// Hook for when plugin is activated
register_activation_hook(__FILE__, [ '\Khatam\Setup', 'activate' ]);
register_deactivation_hook(__FILE__, ['\Khatam\Setup', 'deactivate' ]);

$khatamRepo = new \Khatam\Repositories\KhatamRepository($wpdb);
$khatamUsersRepo = new \Khatam\Repositories\KhatamUsersRepository($wpdb);
$adminController = new \Khatam\Controllers\AdminController($khatamRepo);
$frontController = new \Khatam\Controllers\FrontController($khatamUsersRepo, $khatamRepo);

// The 2 things we will need on the front end
add_shortcode('khatam_registration_form', [$frontController, 'registration']);
add_shortcode('khatam_registration_status', [$frontController, 'getKhatamStatus']);


Main::main();

