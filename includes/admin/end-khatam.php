<?php

/**
 * Handles the "End Khatam" admin action.
 * Sets the khatam's end_date to today, marking it as completed.
 */
function kh_end_khatam () {
  if (!current_user_can('edit_theme_options')) {
    wp_die(__('You are not allowed to do this.', 'khatm'));
  }

  $id = isset($_GET['khatam_id']) ? absint($_GET['khatam_id']) : 0;

  if (!$id) {
    wp_die(__('Invalid khatam ID.', 'khatm'));
  }

  check_admin_referer('kh_end_khatam_' . $id);

  require_once __DIR__ . '/../../repositories/khatamRepository.php';

  $result = khatamUpdate([
    'end_date' => date('Y-m-d'),
  ], $id);

  $redirect = admin_url('admin.php?page=quran-khatam-dashboard');

  if ($result !== false) {
    $redirect = add_query_arg('ended', '1', $redirect);
  } else {
    $redirect = add_query_arg('end_error', '1', $redirect);
  }

  wp_redirect($redirect);
  exit;
}
