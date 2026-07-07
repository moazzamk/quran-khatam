<?php

function kh_enqueue_scripts () {
  wp_register_style(
    'kh_fonts', 
    "https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Raleway:wght@200;400;600;700&family=Roboto:wght@300;400;500;700&display=swap",
    [],
    null
  );
  wp_register_style(
    'kh_material_icons', 
    "https://fonts.googleapis.com/icon?family=Material+Icons",
    [],
    null
  );

  wp_enqueue_style('kh_fonts');
  wp_enqueue_style('kh_material_icons');
}

function kh_inject_rest_urls () {
  $authUrls = json_encode([
    'currentKhatam' => esc_url_raw(rest_url('/kh/v1/currentKhatam')),
    'signup' => esc_url_raw(rest_url('/kh/v1/currentKhatam/signup')),
    'completejuz' => esc_url_raw(rest_url('/kh/v1/currentKhatam/completejuz'))
  ]);

  echo "<script>var kh_auth_rest = {$authUrls};</script>\n";
}
