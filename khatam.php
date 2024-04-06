<?php
/*
* Plugin Name:       Quran Khatm
* Plugin URI:        https://example.com/plugins/the-basics/
* Description:       A plugin for Quran Khatm recitations.
* Version:           1.0.0
* Requires at least: 5.9
* Requires PHP:      7.2
* Author:            Quaid Mehr dil, Moazzam Khan, & Hasham Khan
* Author URI:        https://author.example.com/
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Update URI:        https://example.com/my-plugin/
* Text Domain:       khatam
* Domain Path:       /languages
*/

if (!function_exists('add_action')) {
  echo 'Seems like you stumbled here by accident. 😛';
  exit;
}

// Setup
define('KH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KH_PLUGIN_FILE',__FILE__);

// Includes
$rootFiles = glob(KH_PLUGIN_DIR . 'includes/*.php');
$subdirFiles = glob(KH_PLUGIN_DIR . 'includes/**/*.php');
$allFiles = array_merge($rootFiles, $subdirFiles);

forEach($allFiles as $file) {
  include_once($file);
}

// Hooks
register_activation_hook(__FILE__, 'kh_activate_plugin');
add_action('init', 'kh_register_blocks');
add_action('rest_api_init', 'kh_rest_api_init');
add_action('wp_enqueue_scripts', 'kh_enqueue_scripts', 5);
// add_action('wp_head', 'kh_head', 5);
add_action('admin_menu', 'kh_admin_menus');
add_action('admin_enqueue_scripts', 'kh_admin_enqueue_scripts');
add_action('admin_post_kh_save_options', 'kh_save_options');
add_action('init', 'kh_register_assets');

// Shortcode
add_shortcode( 'kh-form', 'kh_form_shortcode' );
