<?php

function kh_admin_menus () {
  add_menu_page(
    __('Quran Khatm', 'khatm'),
    __('Quran Khatm', 'khatm'),
    'edit_theme_options',
    'quran-khatam-dashboard',
    'khatam_dashboard',
    plugins_url('kh-logo-16.svg', KH_PLUGIN_FILE)
  );

  add_submenu_page( 
    'quran-khatam-dashboard',
    __('Add Khatam'),
    __('Add Khatam'),
    'edit_theme_options',
    'kh-plugin-options-alt',
    'kh_plugin_options_alt_page',
  );

  // Hidden page (no menu item) for editing a khatam
  add_submenu_page(
    null,
    __('Edit Khatam', 'khatm'),
    __('Edit Khatam', 'khatm'),
    'edit_theme_options',
    'kh-edit-khatam',
    'kh_edit_khatam_page',
  );

  // Hidden page for managing participants
  add_submenu_page(
    null,
    __('Manage Participants', 'khatm'),
    __('Manage Participants', 'khatm'),
    'edit_theme_options',
    'kh-manage-participants',
    'kh_manage_participants_page',
  );

  add_submenu_page(
    'quran-khatam-dashboard',
    __('Settings', 'khatm'),
    __('Settings', 'khatm'),
    'edit_theme_options',
    'kh-settings',
    'kh_settings_page',
  );
}