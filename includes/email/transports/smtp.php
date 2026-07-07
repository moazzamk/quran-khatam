<?php
/**
 * SMTP Transport
 *
 * Sends email via SMTP using WordPress's PHPMailer instance.
 * Supports Google/Gmail SMTP or any custom SMTP server.
 *
 * SMTP settings are stored in kh_options under the 'email_smtp_*' keys:
 *   - email_smtp_host
 *   - email_smtp_port
 *   - email_smtp_username
 *   - email_smtp_password
 *   - email_smtp_encryption (tls or ssl)
 *
 * @param string $to       Recipient email.
 * @param string $subject  Subject line.
 * @param string $body     HTML body.
 * @param string $from     From address (optional).
 * @param array  $headers  Additional headers.
 *
 * @return bool
 */
function kh_email_transport_smtp($to, $subject, $body, $from = '', $headers = []) {
  $options = get_option('kh_options', []);

  $host       = isset($options['email_smtp_host']) ? $options['email_smtp_host'] : '';
  $port       = isset($options['email_smtp_port']) ? absint($options['email_smtp_port']) : 587;
  $username   = isset($options['email_smtp_username']) ? $options['email_smtp_username'] : '';
  $password   = isset($options['email_smtp_password']) ? $options['email_smtp_password'] : '';
  $encryption = isset($options['email_smtp_encryption']) ? $options['email_smtp_encryption'] : 'tls';

  if (empty($host) || empty($username) || empty($password)) {
    error_log('[Khatam Email] SMTP transport misconfigured: missing host, username, or password.');
    return false;
  }

  // Override the from address early (before PHPMailer validation)
  $fromAddr = !empty($from) ? $from : $username;
  $overrideFrom = function() use ($fromAddr) { return $fromAddr; };
  add_filter('wp_mail_from', $overrideFrom, 99);

  // Use a temporary hook to configure PHPMailer before wp_mail sends
  $configureSMTP = function ($phpmailer) use ($host, $port, $username, $password, $encryption, $fromAddr) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->Port       = $port;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = $username;
    $phpmailer->Password   = $password;
    $phpmailer->SMTPSecure = $encryption;

    // Override the default 'wordpress@localhost' from address
    $phpmailer->setFrom($fromAddr, '', true);
    $phpmailer->Sender = $fromAddr;
  };

  add_action('phpmailer_init', $configureSMTP, 99);

  $mailHeaders = ['Content-Type: text/html; charset=UTF-8'];
  if (!empty($fromAddr)) {
    $mailHeaders[] = 'From: ' . $fromAddr;
  }
  $mailHeaders = array_merge($mailHeaders, $headers);

  $result = wp_mail($to, $subject, $body, $mailHeaders);

  remove_filter('wp_mail_from', $overrideFrom, 99);
  remove_action('phpmailer_init', $configureSMTP, 99);

  return $result;
}
