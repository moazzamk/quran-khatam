<?php

function kh_admin_menus () {
  add_menu_page(
    __('Quran Khatm', 'khatm'),
    __('Quran Khatm', 'khatm'),
    'edit_theme_options',
    'kh-plugin-options',
    'kh_plugins_options_page',
    plugins_url('kh-logo-16.svg', KH_PLUGIN_FILE)
  );
  add_submenu_page( 
    'kh-plugin-options',
    __('Alt Khatam'),
    __('Alt Khatam'),
    'edit_theme_options',
    'kh-plugin-options-alt',
    'kh_plugin_options_alt_page',
  );
}