<?php

function kh_edit_khatam_page () {
  require_once __DIR__ . '/../../repositories/khatamRepository.php';

  $id = isset($_GET['khatam_id']) ? absint($_GET['khatam_id']) : 0;

  if (!$id) {
    echo '<div class="wrap"><p>' . esc_html__('Invalid khatam ID.', 'khatm') . '</p></div>';
    return;
  }

  $khatam = getKhatamById($id);

  if (!$khatam) {
    echo '<div class="wrap"><p>' . esc_html__('Khatam not found.', 'khatm') . '</p></div>';
    return;
  }

  $error = false;

  // Handle form submission
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kh_edit_khatam_nonce'])) {
    if (!wp_verify_nonce($_POST['kh_edit_khatam_nonce'], 'kh_edit_khatam_' . $id)) {
      wp_die(__('Security check failed.', 'khatm'));
    }

    $data = [
      'name' => sanitize_text_field($_POST['kh_khatam_name']),
      'start_date' => sanitize_text_field($_POST['kh_start_date']),
      'end_date' => sanitize_text_field($_POST['kh_end_date']),
      'meeting_link' => sanitize_url($_POST['kh_meeting_link']),
      'meeting_ts' => !empty($_POST['kh_meeting_ts']) ? sanitize_text_field($_POST['kh_meeting_ts']) : null,
    ];

    $result = khatamUpdate($data, $id);
    if ($result !== false) {
      echo '<script>window.location.href="' . esc_url(admin_url('admin.php?page=quran-khatam-dashboard&updated=1')) . '";</script>';
      return;
    } else {
      $error = true;
    }
  }

  $dashboardUrl = admin_url('admin.php?page=quran-khatam-dashboard');
  $khatamName = $khatam->name ?: 'Khatam #' . $id;
?>
<div class="wrap kh-wrap">

  <!-- Breadcrumb -->
  <div class="kh-breadcrumb">
    <a href="<?php echo esc_url($dashboardUrl); ?>"><?php esc_html_e('Quran Khatm', 'khatm'); ?></a>
    <span class="kh-sep">&rsaquo;</span>
    <span><?php echo esc_html($khatamName); ?></span>
    <span class="kh-sep">&rsaquo;</span>
    <span><?php esc_html_e('Edit', 'khatm'); ?></span>
  </div>

  <h1><?php printf(esc_html__('Edit — %s', 'khatm'), esc_html($khatamName)); ?></h1>
  <p class="kh-subtitle"><?php esc_html_e('Update the details for this khatam.', 'khatm'); ?></p>

  <?php if ($error): ?>
    <div class="notice notice-error is-dismissible">
      <p><?php esc_html_e('Error updating khatam. Please try again.', 'khatm'); ?></p>
    </div>
  <?php endif; ?>

  <form method="POST">
    <?php wp_nonce_field('kh_edit_khatam_' . $id, 'kh_edit_khatam_nonce'); ?>

    <!-- Details Section -->
    <div class="kh-form-section">
      <h2><?php esc_html_e('Details', 'khatm'); ?></h2>

      <div class="kh-form-field">
        <label for="kh_khatam_name"><?php esc_html_e('Name', 'khatm'); ?></label>
        <input name="kh_khatam_name" type="text" id="kh_khatam_name" class="regular-text"
          value="<?php echo esc_attr($khatam->name ?? ''); ?>" required />
      </div>

      <div class="kh-form-row">
        <div class="kh-form-field">
          <label for="kh_start_date"><?php esc_html_e('Start Date', 'khatm'); ?></label>
          <input name="kh_start_date" type="date" id="kh_start_date" class="regular-text"
            value="<?php echo esc_attr($khatam->startDate); ?>" required />
        </div>
        <div class="kh-form-field">
          <label for="kh_end_date"><?php esc_html_e('End Date', 'khatm'); ?></label>
          <input name="kh_end_date" type="date" id="kh_end_date" class="regular-text"
            value="<?php echo esc_attr($khatam->endDate); ?>" required />
        </div>
      </div>
    </div>

    <!-- Meeting Section -->
    <div class="kh-form-section">
      <h2><?php esc_html_e('Meeting (Optional)', 'khatm'); ?></h2>

      <div class="kh-form-field">
        <label for="kh_meeting_link"><?php esc_html_e('Meeting Link', 'khatm'); ?></label>
        <input name="kh_meeting_link" type="url" id="kh_meeting_link" class="regular-text"
          value="<?php echo esc_attr($khatam->meetingLink ?? ''); ?>" placeholder="https://" />
      </div>

      <div class="kh-form-field">
        <label for="kh_meeting_ts"><?php esc_html_e('Meeting Date & Time', 'khatm'); ?></label>
        <input name="kh_meeting_ts" type="datetime-local" id="kh_meeting_ts" class="regular-text"
          value="<?php echo esc_attr($khatam->meetingTs ? date('Y-m-d\TH:i', strtotime($khatam->meetingTs)) : ''); ?>" />
      </div>
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
      <button type="submit" class="kh-btn-primary"><?php esc_html_e('Update Khatam', 'khatm'); ?></button>
      <a href="<?php echo esc_url($dashboardUrl); ?>" class="kh-btn-secondary"><?php esc_html_e('Cancel', 'khatm'); ?></a>
    </div>
  </form>
</div>
<?php
}
