<?php
/**
 * Email Sender Service
 *
 * Handles placeholder replacement in subject/body templates and dispatches
 * emails via the configured transport.
 *
 * Supported placeholders:
 *   {days_remaining}    — Number of days until the khatam ends
 *   {participant_name}  — Full name of the participant (first + last)
 *   {first_name}        — Participant's first name
 *   {last_name}         — Participant's last name
 *   {juz_number}        — The juz assigned to the participant
 *   {khatam_end_date}   — The end date of the khatam (formatted)
 *   {khatam_name}       — Name of the khatam
 */

/**
 * Get the list of supported placeholders with their descriptions.
 *
 * @return array Associative array of placeholder => description.
 */
function kh_email_get_placeholders() {
  return [
    '{days_remaining}'   => 'Number of days until the khatam ends',
    '{participant_name}' => 'Full name of the participant',
    '{first_name}'       => 'Participant\'s first name',
    '{last_name}'        => 'Participant\'s last name',
    '{juz_number}'       => 'Juz number assigned to the participant',
    '{khatam_end_date}'  => 'End date of the khatam',
    '{khatam_name}'      => 'Name of the khatam',
  ];
}

/**
 * Replace placeholders in a string with actual values.
 *
 * @param string $template  The template string with placeholders.
 * @param array  $data      Associative array of placeholder values (without braces).
 *
 * @return string
 */
function kh_email_render_template($template, $data) {
  $replacements = [];
  foreach ($data as $key => $value) {
    $replacements['{' . $key . '}'] = $value;
  }
  return str_replace(array_keys($replacements), array_values($replacements), $template);
}

/**
 * Send a reminder email to a participant.
 *
 * Reads transport and template settings from kh_options, renders placeholders,
 * and dispatches via the configured transport.
 *
 * @param string $toEmail       Recipient email address.
 * @param array  $placeholderData Associative array of placeholder values.
 *
 * @return bool True on success, false on failure.
 */
function kh_email_send_reminder($toEmail, $placeholderData) {
  $options = get_option('kh_options', []);

  $transport   = isset($options['email_transport']) ? $options['email_transport'] : 'wp_mail';
  $fromAddress = isset($options['email_from']) ? $options['email_from'] : get_option('admin_email');
  $subjectTpl  = isset($options['email_subject']) ? $options['email_subject'] : 'Reminder: {days_remaining} days left to complete your Juz';
  $bodyTpl     = isset($options['email_body']) ? $options['email_body'] : '';

  if (empty($bodyTpl)) {
    $bodyTpl = kh_email_default_body();
  }

  $subject = kh_email_render_template($subjectTpl, $placeholderData);
  $body    = kh_email_render_template($bodyTpl, $placeholderData);

  return kh_email_send($transport, $toEmail, $subject, $body, $fromAddress);
}

/**
 * Returns a default email body template.
 *
 * @return string
 */
function kh_email_default_body() {
  return 'Assalamu Alaikum {participant_name},'
    . "\n\n"
    . 'This is a reminder that there are <b>{days_remaining}</b> day(s) remaining to complete your assigned Juz (<b>Juz {juz_number}</b>) in the current Quran Khatam.'
    . "\n\n"
    . 'The khatam ends on <b>{khatam_end_date}</b>. Please try to complete your reading before then.'
    . "\n\n"
    . 'JazakAllahu Khairan.';
}
