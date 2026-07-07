<?php
/**
 * Email Transport Interface
 *
 * All email transports must implement these functions.
 * This allows swapping between wp_mail, SMTP, Google API, etc.
 */

/**
 * Send an email using the given transport configuration.
 *
 * @param string $transport  The transport key (e.g. 'wp_mail', 'smtp').
 * @param string $to         Recipient email address.
 * @param string $subject    Email subject line.
 * @param string $body       Email body (HTML).
 * @param string $from       From email address.
 * @param array  $headers    Additional headers.
 *
 * @return bool True on success, false on failure.
 */
function kh_email_send($transport, $to, $subject, $body, $from = '', $headers = []) {
  switch ($transport) {
    case 'smtp':
      return kh_email_transport_smtp($to, $subject, $body, $from, $headers);
    case 'log':
      return kh_email_transport_log($to, $subject, $body, $from, $headers);
    case 'wp_mail':
    default:
      return kh_email_transport_wp_mail($to, $subject, $body, $from, $headers);
  }
}
