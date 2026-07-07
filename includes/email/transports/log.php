<?php
/**
 * Log Transport (for testing)
 *
 * Instead of sending an email, writes the full email content to log files.
 * Useful for local development and testing without any SMTP setup.
 *
 * Produces two files:
 *   - wp-content/khatam-emails.log  (plain text, all emails appended)
 *   - wp-content/khatam-emails.html (rendered HTML, viewable in browser)
 *
 * @param string $to       Recipient email.
 * @param string $subject  Subject line.
 * @param string $body     HTML body.
 * @param string $from     From address.
 * @param array  $headers  Additional headers.
 *
 * @return bool Always returns true.
 */
function kh_email_transport_log($to, $subject, $body, $from = '', $headers = []) {
  $logFile  = WP_CONTENT_DIR . '/khatam-emails.log';
  $htmlFile = WP_CONTENT_DIR . '/khatam-emails.html';

  $date = current_time('Y-m-d H:i:s');

  // Plain text log
  $entry  = str_repeat('=', 60) . "\n";
  $entry .= 'Date:    ' . $date . "\n";
  $entry .= 'To:      ' . $to . "\n";
  $entry .= 'From:    ' . $from . "\n";
  $entry .= 'Subject: ' . $subject . "\n";
  if (!empty($headers)) {
    $entry .= 'Headers: ' . implode(' | ', $headers) . "\n";
  }
  $entry .= str_repeat('-', 60) . "\n";
  $entry .= $body . "\n";
  $entry .= str_repeat('=', 60) . "\n\n";

  file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

  // HTML log (viewable in browser)
  $htmlEntry = '<div style="border:1px solid #ddd; border-radius:8px; padding:16px; margin:16px 0; font-family:sans-serif;">'
    . '<div style="font-size:12px; color:#666; margin-bottom:8px;">'
    . '<strong>Date:</strong> ' . esc_html($date)
    . ' &nbsp;|&nbsp; <strong>To:</strong> ' . esc_html($to)
    . ' &nbsp;|&nbsp; <strong>From:</strong> ' . esc_html($from)
    . '</div>'
    . '<div style="font-size:14px; font-weight:bold; margin-bottom:12px; padding-bottom:8px; border-bottom:1px solid #eee;">'
    . esc_html($subject)
    . '</div>'
    . '<div style="font-size:14px; line-height:1.6;">'
    . $body
    . '</div>'
    . '</div>';

  // If file doesn't exist, write the HTML wrapper
  if (!file_exists($htmlFile)) {
    $htmlHeader = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Khatam Email Log</title>'
      . '<style>body{max-width:700px;margin:20px auto;padding:0 16px;background:#f9f9f9;}</style>'
      . '</head><body>'
      . '<h1 style="font-family:sans-serif;font-size:20px;color:#333;">Khatam Email Log</h1>' . "\n";
    file_put_contents($htmlFile, $htmlHeader, LOCK_EX);
  }

  file_put_contents($htmlFile, $htmlEntry . "\n", FILE_APPEND | LOCK_EX);

  return true;
}
