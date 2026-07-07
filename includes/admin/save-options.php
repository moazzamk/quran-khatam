<?php

function kh_save_options () {
  if (!current_user_can('edit_theme_options')) {
    wp_die(
      __("You are not allowed to be on this page.", 'khatm')
    );
  }

  check_admin_referer('kh_options_verify');

  $options = get_option('kh_options', []);

  // Open Graph settings
  $options['og_title'] = sanitize_text_field($_POST['kh_og_title']);
  $options['og_img'] = sanitize_url($_POST['kh_og_img']);
  $options['og_description'] = sanitize_text_field($_POST['kh_og_description']);
  $options['enable_og'] = isset($_POST['kh_enable_og']) ? absint($_POST['kh_enable_og']) : 0;

  // Email reminder settings
  $options['email_enabled']       = isset($_POST['kh_email_enabled']) ? absint($_POST['kh_email_enabled']) : 0;
  $options['email_days_before']   = isset($_POST['kh_email_days_before']) ? absint($_POST['kh_email_days_before']) : 3;
  $options['email_interval_days'] = isset($_POST['kh_email_interval_days']) ? absint($_POST['kh_email_interval_days']) : 1;
  $options['email_transport']     = isset($_POST['kh_email_transport']) ? sanitize_text_field($_POST['kh_email_transport']) : 'wp_mail';
  $options['email_from']          = isset($_POST['kh_email_from']) ? sanitize_email($_POST['kh_email_from']) : '';
  $options['email_subject']       = isset($_POST['kh_email_subject']) ? sanitize_text_field($_POST['kh_email_subject']) : '';
  $options['email_body']          = isset($_POST['kh_email_body']) ? wp_kses_post($_POST['kh_email_body']) : '';

  // SMTP settings
  $options['email_smtp_host']       = isset($_POST['kh_email_smtp_host']) ? sanitize_text_field($_POST['kh_email_smtp_host']) : '';
  $options['email_smtp_port']       = isset($_POST['kh_email_smtp_port']) ? absint($_POST['kh_email_smtp_port']) : 587;
  $options['email_smtp_encryption'] = isset($_POST['kh_email_smtp_encryption']) ? sanitize_text_field($_POST['kh_email_smtp_encryption']) : 'tls';
  $options['email_smtp_username']   = isset($_POST['kh_email_smtp_username']) ? sanitize_text_field($_POST['kh_email_smtp_username']) : '';
  $options['email_smtp_password']   = isset($_POST['kh_email_smtp_password']) ? sanitize_text_field($_POST['kh_email_smtp_password']) : '';

  update_option('kh_options', $options);

  wp_redirect(admin_url('admin.php?page=kh-settings&status=1'));
}