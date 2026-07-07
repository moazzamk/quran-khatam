<?php
/**
 * WP Mail Transport
 *
 * Uses WordPress's built-in wp_mail() function which in turn uses
 * PHP's mail() or whatever is configured via plugins (e.g. WP Mail SMTP).
 *
 * @param string $to       Recipient email.
 * @param string $subject  Subject line.
 * @param string $body     HTML body.
 * @param string $from     From address (optional).
 * @param array  $headers  Additional headers.
 *
 * @return bool
 */
function kh_email_transport_wp_mail($to, $subject, $body, $from = '', $headers = []) {
  $mailHeaders = ['Content-Type: text/html; charset=UTF-8'];

  if (!empty($from)) {
    $mailHeaders[] = 'From: ' . $from;
  }

  $mailHeaders = array_merge($mailHeaders, $headers);

  return wp_mail($to, $subject, $body, $mailHeaders);
}
