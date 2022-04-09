<?php

namespace Khatam;

class Main
{
    public static function main()
    {
        self::adminHooks();
    }

    public static function adminHooks()
    {
        if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
            // Admin hooks
            add_action( 'admin_post_khatam-save-response', function () {
                global $adminController;
                $adminController->save($_REQUEST);
            });
            add_action( 'admin_post_khatam-delete-khatam', function () {
                global $adminController;
                $adminController->delete($_REQUEST);
            });

            // Admin menu
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
                    'Dashboard',
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
                    'Add/Edit Khatam',
                    'Add/Edit Khatam',
                    'manage_options',
                    'khatams-add',
                    function () {
                        global $adminController;
                        $adminController->add();
                    }
                );
            });
        }
    }

}