<?php

function kh_save_options () {
  if (!current_user_can('edit_theme_options')) {
    wp_die(
      __("You are not allowed to be on this page.", 'khatm')
    );
  }

  check_admin_referer('kh_options_verify');

  $options = get_option('kh_options');
  $options['og_title'] = sanitize_text_field($_POST['kh_og_title']);
  $options['og_img'] = sanitize_url($_POST['kh_og_img']);
  $options['og_description'] = sanitize_text_field($_POST['kh_og_description']);
  $options['enable_og'] = absint($_POST['kh_enable_og']);
  update_option('kh_options', $options);

  wp_redirect(admin_url('admin.php?page=kh-plugin-options&status=1'));

}