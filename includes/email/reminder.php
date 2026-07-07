<?php
/**
 * Email Reminder Logic
 *
 * Schedules and executes reminder emails to participants who have not
 * completed their juz. Uses WordPress Cron (wp-cron) for scheduling.
 *
 * Admin-configurable options (stored in kh_options):
 *   - email_days_before (X): Start sending reminders X days before end_date.
 *   - email_interval_days (Y): Send a reminder every Y days once started.
 *   - email_enabled: Whether reminders are enabled at all.
 */

define('KH_REMINDER_CRON_HOOK', 'kh_email_reminder_cron');

/**
 * Schedule the reminder cron event.
 * Called on plugin activation and when settings are saved.
 */
function kh_reminder_schedule_cron() {
  if (!wp_next_scheduled(KH_REMINDER_CRON_HOOK)) {
    wp_schedule_event(time(), 'daily', KH_REMINDER_CRON_HOOK);
  }
}

/**
 * Unschedule the reminder cron event.
 * Called on plugin deactivation.
 */
function kh_reminder_unschedule_cron() {
  $timestamp = wp_next_scheduled(KH_REMINDER_CRON_HOOK);
  if ($timestamp) {
    wp_unschedule_event($timestamp, KH_REMINDER_CRON_HOOK);
  }
}

/**
 * Main cron callback: check if reminders should be sent today and send them.
 *
 * Logic:
 * 1. Check if email reminders are enabled.
 * 2. Get the current khatam.
 * 3. Calculate days remaining until khatam end_date.
 * 4. If days_remaining <= X (email_days_before), we're in the reminder window.
 * 5. Check if today is a valid send day based on Y (email_interval_days).
 * 6. Query participants with status = 0 (not completed).
 * 7. Check the email log to avoid sending duplicates today.
 * 8. Send emails and log them.
 */
function kh_reminder_cron_handler() {
  require_once KH_PLUGIN_DIR . 'repositories/khatamRepository.php';
  require_once KH_PLUGIN_DIR . 'repositories/khatamUsersRepository.php';
  $options = get_option('kh_options', []);

  // Check if reminders are enabled
  $enabled = isset($options['email_enabled']) ? absint($options['email_enabled']) : 0;
  if (!$enabled) {
    return;
  }

  $daysBefore   = isset($options['email_days_before']) ? absint($options['email_days_before']) : 3;
  $intervalDays = isset($options['email_interval_days']) ? absint($options['email_interval_days']) : 1;

  // Get current khatam
  $currentKhatam = getCurrentKhatam();
  if (!$currentKhatam) {
    return;
  }

  $khatamId = $currentKhatam->id;
  $endDate  = new DateTime($currentKhatam->endDate);
  $today    = new DateTime(current_time('Y-m-d'));

  // Calculate days remaining
  $diff = $today->diff($endDate);
  $daysRemaining = $diff->invert ? 0 : $diff->days;

  // Are we within the reminder window?
  if ($daysRemaining > $daysBefore) {
    return;
  }

  // Check if today is a valid send day based on interval.
  // We calculate: how many days into the reminder window are we?
  // The window starts at (end_date - X days). Send on day 0, Y, 2Y, etc.
  $windowStart = clone $endDate;
  $windowStart->modify("-{$daysBefore} days");
  $daysIntoWindow = $windowStart->diff($today)->days;

  if ($intervalDays > 0 && ($daysIntoWindow % $intervalDays) !== 0) {
    return;
  }

  // Get incomplete participants
  $participants = kh_get_incomplete_participants($khatamId);
  if (empty($participants)) {
    return;
  }

  // Get khatam details for placeholders
  $khatamDetails = getKhatamById($khatamId);
  $khatamName    = $khatamDetails ? $khatamDetails->name : '';
  $khatamEndDate = $endDate->format('F j, Y');

  $todayStr = $today->format('Y-m-d');

  foreach ($participants as $participant) {
    // Check if we already sent a reminder to this person today
    if (kh_reminder_already_sent($khatamId, $participant->email, $todayStr)) {
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

    $sent = kh_email_send_reminder($participant->email, $placeholderData);

    // Log the attempt
    kh_reminder_log($khatamId, $participant->email, $todayStr, $sent ? 1 : 0);
  }
}

/**
 * Get participants who have not completed their juz for a given khatam.
 *
 * @param int $khatamId
 *
 * @return array
 */
function kh_get_incomplete_participants($khatamId) {
  global $wpdb;

  $sql = $wpdb->prepare(
    "SELECT user_email AS email, first_name AS firstName, last_name AS lastName, juz_num AS juz
     FROM {$wpdb->prefix}khatams_users
     WHERE khatam_id = %d AND status = 0",
    $khatamId
  );

  return $wpdb->get_results($sql);
}

/**
 * Check if a reminder was already sent to a participant today.
 *
 * @param int    $khatamId
 * @param string $email
 * @param string $date  Y-m-d format.
 *
 * @return bool
 */
function kh_reminder_already_sent($khatamId, $email, $date) {
  global $wpdb;
  $table = $wpdb->prefix . 'khatam_email_log';

  $count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$table} WHERE khatam_id = %d AND email = %s AND sent_date = %s",
    $khatamId,
    $email,
    $date
  ));

  return $count > 0;
}

/**
 * Log a sent reminder email.
 *
 * @param int    $khatamId
 * @param string $email
 * @param string $date    Y-m-d format.
 * @param int    $success 1 = sent, 0 = failed.
 */
function kh_reminder_log($khatamId, $email, $date, $success) {
  global $wpdb;
  $table = $wpdb->prefix . 'khatam_email_log';

  $wpdb->insert($table, [
    'khatam_id' => $khatamId,
    'email'     => $email,
    'sent_date' => $date,
    'success'   => $success,
  ]);
}
