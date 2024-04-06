<?php

function kh_admin_enqueue_scripts ($hook_suffix) {
  if ($hook_suffix === "toplevel_page_kh-plugin-options") {
    wp_enqueue_media();
    wp_enqueue_style('kh_admin');
    wp_enqueue_script('kh_admin');
  }
  wp_register_style(
    'kh_fonts', 
    "https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Raleway:wght@200;400;600;700&family=Roboto:wght@300;400;500;700&display=swap",
    [],
    null
  );
}