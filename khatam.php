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

/** Autoloader */
require __DIR__ . '/vendor/autoload.php';


define('KHATAM_URL', plugin_dir_url(__FILE__));


if (!function_exists('add_action')) {
	echo 'Called directly';
	exit;
}

// Hook for when plugin is activated
register_activation_hook(__FILE__, [ '\Khatam\Setup', 'activate' ]);
register_deactivation_hook(__FILE__, ['\Khatam\Setup', 'deactivate' ]);

$khatamRepo = new \Khatam\Repositories\KhatamRepository($wpdb);
$adminController = new \Khatam\Controllers\AdminController($khatamRepo);


if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
    add_action( 'admin_post_khatam-save-response', function () {
        global $adminController;
        $adminController->save($_REQUEST);
    });
    add_action('admin_menu', function () {
        add_menu_page(
            'Quran Khatam',
            'Quran Khatam',
            'manage_options',
            'khatams-dashboard',
            function () {
                global $adminController;
                $adminController->dashboard();
            }
        );
        add_submenu_page(
            'khatams-dashboard',
            'Quran Khatam',
            'Dashboard',
            'manage_options',
            'khatams-dashboard',
            function () {
                global $adminController;
                $adminController->dashboard();
            }
        );
        add_submenu_page(
            'khatams-dashboard',
            'Add Khatam',
            'Add Khatam',
            'manage_options',
            'khatams-add',
            function () {
                global $adminController;
                $adminController->add();
            }
        );
    });
	// admin page
}

