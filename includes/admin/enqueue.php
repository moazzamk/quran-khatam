<?php

function kh_admin_enqueue_scripts ($hook_suffix) {
  // Load shared CSS on all Khatam plugin pages
  $kh_pages = [
    'toplevel_page_quran-khatam-dashboard',
    'quran-khatm_page_kh-plugin-options-alt',
    'quran-khatm_page_kh-edit-khatam',
    'quran-khatm_page_kh-manage-participants',
    'quran-khatm_page_kh-settings',
    'admin_page_kh-edit-khatam',
    'admin_page_kh-manage-participants',
  ];

  if (in_array($hook_suffix, $kh_pages) || strpos($hook_suffix, 'khatam') !== false || strpos($hook_suffix, 'kh-') !== false) {
    wp_enqueue_style(
      'kh-admin-css',
      plugins_url('/assets/admin.css', KH_PLUGIN_FILE),
      [],
      filemtime(KH_PLUGIN_DIR . 'assets/admin.css')
    );
  }

  // Media library + admin scripts for settings page
  if (in_array($hook_suffix, ['quran-khatm_page_kh-settings', 'toplevel_page_quran-khatam-dashboard'])) {
    wp_enqueue_media();
    wp_enqueue_style('kh_admin');
    wp_enqueue_script('kh_admin');
  }

  // Tutorial overlay (only on dashboard, only when flag is set)
  if ($hook_suffix === 'toplevel_page_quran-khatam-dashboard' && get_option('kh_show_tutorial') === '1') {
    wp_enqueue_style(
      'kh-tutorial-css',
      plugins_url('/assets/admin-tutorial.css', KH_PLUGIN_FILE),
      [],
      filemtime(KH_PLUGIN_DIR . 'assets/admin-tutorial.css')
    );
    wp_enqueue_script(
      'kh-tutorial-js',
      plugins_url('/assets/admin-tutorial.js', KH_PLUGIN_FILE),
      [],
      filemtime(KH_PLUGIN_DIR . 'assets/admin-tutorial.js'),
      true
    );
  }
}
