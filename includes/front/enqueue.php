<?php

function kh_enqueue_scripts () {
  // font-family: 'Open Sans', sans-serif;
  // font-family: 'Raleway', sans-serif;
  // font-family: 'Roboto', sans-serif;
  wp_register_style(
    'kh_fonts', 
    "https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Raleway:wght@200;400;600;700&family=Roboto:wght@300;400;500;700&display=swap",
    [],
    null
  );
  // wp_register_style(
  //   'kh_material_components', 
  //   "https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css",
  //   [],
  //   null
  // );
  wp_register_style(
    'kh_material_icons', 
    "https://fonts.googleapis.com/icon?family=Material+Icons",
    [],
    null
  );

  wp_register_style(
    'kh_material_symbols', 
    "https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200",
    [],
    null
  );

  // wp_register_script(
  //   'kh_material_script',
  //   "https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js",
  //   [],
  //   null,
  //   true
  // );

  wp_enqueue_style('kh_fonts');
  // wp_enqueue_style('kh_material_components');
  wp_enqueue_script('kh_material_icons');
  wp_enqueue_script('kh_material_symbols');
  // wp_enqueue_script('kh_material_script');


  // REST END POINTS
  $authUrls = json_encode([
    'users' => esc_url_raw(rest_url('/kh/v1/currentKharam/users')),
    'signin' => esc_url_raw(rest_url('kh/v1/currentKharam/complete'))
  ]);

  wp_add_inline_script(
    'khatam-khatam-form-view-script',
    "const kh_auth_rest = {$authUrls};",
    'before'
  );
}
