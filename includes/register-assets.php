<?php

function kh_register_assets () {
  wp_register_style(
    'kh_admin',
    plugins_url('/build/admin/index.css', KH_PLUGIN_FILE)
  );

  $adminAssets = include(KH_PLUGIN_DIR . 'build/admin/index.asset.php');
  wp_register_script(
    'kh_admin',
    plugins_url('/build/admin/index.js', KH_PLUGIN_FILE),
    $adminAssets['dependencies'],
    $adminAssets['version'],
    true
  );
}