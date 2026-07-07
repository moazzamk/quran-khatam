<?php
/**
 * Handle dismissing the tutorial overlay.
 *
 * Called via admin-post.php when the user clicks "Skip Tour" or finishes
 * the tutorial. Clears the kh_show_tutorial option so it won't show again.
 */
function kh_dismiss_tutorial() {
  if (!current_user_can('edit_theme_options')) {
    wp_die(__('Not allowed.', 'khatm'));
  }

  check_admin_referer('kh_dismiss_tutorial_nonce');

  delete_option('kh_show_tutorial');

  // If this was a fetch call, just return 200
  if (wp_doing_ajax() || !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    wp_send_json_success();
  }

  // Otherwise redirect back to dashboard
  wp_redirect(admin_url('admin.php?page=quran-khatam-dashboard'));
  exit;
}

/**
 * Conditionally render the tutorial overlay on the dashboard page.
 * Hooked into admin_footer so it renders after the page content.
 */
function kh_maybe_render_tutorial() {
  $screen = get_current_screen();
  if (!$screen || $screen->id !== 'toplevel_page_quran-khatam-dashboard') {
    return;
  }

  if (get_option('kh_show_tutorial') !== '1') {
    return;
  }

  kh_render_tutorial_overlay();
}
