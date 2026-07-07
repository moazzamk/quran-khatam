<?php
/**
 * Manual trigger handler: sends reminder emails immediately to all
 * incomplete participants in the current khatam.
 *
 * Ignores the X/Y schedule — just sends to everyone with status = 0
 * who hasn't already been emailed today.
 */
function kh_send_reminders_now() {
  if (!current_user_can('edit_theme_options')) {
    wp_die(__("You are not allowed to perform this action.", 'khatm'));
  }

  check_admin_referer('kh_send_reminders_now_verify');

  require_once KH_PLUGIN_DIR . 'repositories/khatamRepository.php';
  require_once KH_PLUGIN_DIR . 'repositories/khatamUsersRepository.php';

  $currentKhatam = getCurrentKhatam();
  if (!$currentKhatam) {
    wp_redirect(admin_url('admin.php?page=kh-settings&reminders_sent=0&reminders_skipped=0'));
    exit;
  }

  $khatamId = $currentKhatam->id;
  $endDate  = new DateTime($currentKhatam->endDate);
  $today    = new DateTime(current_time('Y-m-d'));

  $diff = $today->diff($endDate);
  $daysRemaining = $diff->invert ? 0 : $diff->days;

  $khatamDetails = getKhatamById($khatamId);
  $khatamName    = $khatamDetails ? $khatamDetails->name : '';
  $khatamEndDate = $endDate->format('F j, Y');

  $participants = kh_get_incomplete_participants($khatamId);
  $todayStr     = $today->format('Y-m-d');

  $sent    = 0;
  $skipped = 0;

  foreach ($participants as $participant) {
    if (kh_reminder_already_sent($khatamId, $participant->email, $todayStr)) {
      $skipped++;
      continue;
    }

    $fullName = trim($participant->firstName . ' ' . $participant->lastName);

    $placeholderData = [
      'days_remaining'   => $daysRemaining,
      'participant_name' => $fullName,
      'first_name'       => $participant->firstName,
      'last_name'        => $participant->lastName,
      'juz_number'       => $participant->juz,
      'khatam_end_date'  => $khatamEndDate,
      'khatam_name'      => $khatamName,
    ];

    $result = kh_email_send_reminder($participant->email, $placeholderData);
    kh_reminder_log($khatamId, $participant->email, $todayStr, $result ? 1 : 0);

    if ($result) {
      $sent++;
    } else {
      $skipped++;
    }
  }

  wp_redirect(admin_url("admin.php?page=kh-settings&reminders_sent={$sent}&reminders_skipped={$skipped}"));
  exit;
}
