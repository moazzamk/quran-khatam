<?php

function kh_settings_page () {
  $options = get_option('kh_options', [
    'og_title' => '',
    'og_img' => '',
    'og_description' => '',
    'enable_og' => '0',
  ]);

  $dashboardUrl = admin_url('admin.php?page=quran-khatam-dashboard');
  ?>
<div class="wrap kh-wrap">

  <!-- Breadcrumb -->
  <div class="kh-breadcrumb">
    <a href="<?php echo esc_url($dashboardUrl); ?>"><?php esc_html_e('Quran Khatm', 'khatm'); ?></a>
    <span class="kh-sep">&rsaquo;</span>
    <span><?php esc_html_e('Settings', 'khatm'); ?></span>
  </div>

  <h1><?php esc_html_e('Settings', 'khatm'); ?></h1>
  <p class="kh-subtitle"><?php esc_html_e('Configure how your Khatam pages appear when shared on social media.', 'khatm'); ?></p>

  <?php if (isset($_GET['status']) && $_GET['status'] == '1'): ?>
    <div class="notice notice-success is-dismissible">
      <p><?php esc_html_e('Settings saved successfully!', 'khatm'); ?></p>
    </div>
  <?php endif; ?>

  <form novalidate="novalidate" method="POST" action="admin-post.php">
    <input type="hidden" name="action" value="kh_save_options" />
    <?php wp_nonce_field('kh_options_verify'); ?>

    <!-- Open Graph Section -->
    <div class="kh-form-section">
      <h2><?php esc_html_e('Open Graph (Social Sharing)', 'khatm'); ?></h2>

      <div class="kh-form-field">
        <label for="kh_enable_og">
          <input name="kh_enable_og" type="checkbox" id="kh_enable_og"
            value="1" <?php checked('1', $options['enable_og']); ?> />
          <?php esc_html_e('Enable Open Graph meta tags', 'khatm'); ?>
        </label>
        <p class="description">
          <?php esc_html_e('When enabled, the title, image, and description below will be injected as meta tags for social media link previews.', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label for="kh_og_title"><?php esc_html_e('Title', 'khatm'); ?></label>
        <input name="kh_og_title" type="text" id="kh_og_title" class="regular-text"
          value="<?php echo esc_attr($options['og_title']); ?>"
          placeholder="<?php esc_attr_e('e.g. Join Our Quran Khatam', 'khatm'); ?>" />
        <p class="description"><?php esc_html_e('The title shown in social media link previews.', 'khatm'); ?></p>
      </div>

      <div class="kh-form-field">
        <label for="kh_og_img"><?php esc_html_e('Image', 'khatm'); ?></label>
        <input type="hidden" name="kh_og_img" id="kh_og_img"
          value="<?php echo esc_attr($options['og_img']); ?>" />
        <?php if (!empty($options['og_img'])): ?>
          <img id="og-img-preview" src="<?php echo esc_url($options['og_img']); ?>"
            style="max-width: 300px; display: block; margin-bottom: 10px; border-radius: 6px; border: 1px solid var(--kh-gray-200);" />
        <?php else: ?>
          <img id="og-img-preview" src=""
            style="max-width: 300px; display: none; margin-bottom: 10px; border-radius: 6px; border: 1px solid var(--kh-gray-200);" />
        <?php endif; ?>
        <a href="#" class="kh-btn-secondary" id="og-img-btn"><?php esc_html_e('Select Image', 'khatm'); ?></a>
        <p class="description"><?php esc_html_e('The thumbnail shown in link previews. Recommended: 1200x630px.', 'khatm'); ?></p>
      </div>

      <div class="kh-form-field">
        <label for="kh_og_description"><?php esc_html_e('Description', 'khatm'); ?></label>
        <textarea id="kh_og_description" name="kh_og_description" class="large-text" rows="3"
          placeholder="<?php esc_attr_e('A short summary of what this khatam is about...', 'khatm'); ?>"
        ><?php echo esc_textarea($options['og_description']); ?></textarea>
        <p class="description"><?php esc_html_e('A short summary shown below the title. Keep under 160 characters.', 'khatm'); ?></p>
      </div>
    </div>

    <!-- Email Reminders Section -->
    <div class="kh-form-section" style="margin-top: 32px;">
      <h2><?php esc_html_e('Email Reminders', 'khatm'); ?></h2>
      <p class="description" style="margin-bottom: 16px;">
        <?php esc_html_e('Send automatic email reminders to participants who have not completed their juz before the khatam ends.', 'khatm'); ?>
      </p>

      <div class="kh-form-field">
        <label for="kh_email_enabled">
          <input name="kh_email_enabled" type="checkbox" id="kh_email_enabled"
            value="1" <?php checked('1', isset($options['email_enabled']) ? $options['email_enabled'] : '0'); ?> />
          <?php esc_html_e('Enable email reminders', 'khatm'); ?>
        </label>
        <p class="description">
          <?php esc_html_e('When enabled, reminder emails will be sent automatically based on the schedule below.', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label for="kh_email_days_before"><?php esc_html_e('Days before end (X)', 'khatm'); ?></label>
        <input name="kh_email_days_before" type="number" id="kh_email_days_before" class="small-text"
          min="1" max="30"
          value="<?php echo esc_attr(isset($options['email_days_before']) ? $options['email_days_before'] : '3'); ?>" />
        <p class="description">
          <?php esc_html_e('Start sending reminders this many days before the khatam end date. For example, "2" means reminders begin 2 days before the end.', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label for="kh_email_interval_days"><?php esc_html_e('Send every (Y) days', 'khatm'); ?></label>
        <input name="kh_email_interval_days" type="number" id="kh_email_interval_days" class="small-text"
          min="1" max="30"
          value="<?php echo esc_attr(isset($options['email_interval_days']) ? $options['email_interval_days'] : '1'); ?>" />
        <p class="description">
          <?php esc_html_e('How often to repeat the reminder once the window starts. "1" means every day.', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label for="kh_email_transport"><?php esc_html_e('Email Transport', 'khatm'); ?></label>
        <select name="kh_email_transport" id="kh_email_transport" class="regular-text">
          <option value="wp_mail" <?php selected('wp_mail', isset($options['email_transport']) ? $options['email_transport'] : 'wp_mail'); ?>>
            <?php esc_html_e('WordPress Default (wp_mail / PHP mail)', 'khatm'); ?>
          </option>
          <option value="smtp" <?php selected('smtp', isset($options['email_transport']) ? $options['email_transport'] : ''); ?>>
            <?php esc_html_e('SMTP (Gmail, custom SMTP server)', 'khatm'); ?>
          </option>
          <option value="log" <?php selected('log', isset($options['email_transport']) ? $options['email_transport'] : ''); ?>>
            <?php esc_html_e('Log to file (testing only)', 'khatm'); ?>
          </option>
        </select>
        <p class="description">
          <?php esc_html_e('Choose how emails are sent. SMTP is recommended for reliable delivery.', 'khatm'); ?>
        </p>
      </div>

      <!-- SMTP Settings (shown when SMTP transport is selected) -->
      <div id="kh-smtp-settings" style="<?php echo (isset($options['email_transport']) && $options['email_transport'] === 'smtp') ? '' : 'display:none;'; ?> margin-left: 16px; padding-left: 16px; border-left: 3px solid #ddd;">
        <h3 style="margin-top: 0;"><?php esc_html_e('SMTP Configuration', 'khatm'); ?></h3>

        <div class="kh-form-field">
          <label for="kh_email_smtp_host"><?php esc_html_e('SMTP Host', 'khatm'); ?></label>
          <input name="kh_email_smtp_host" type="text" id="kh_email_smtp_host" class="regular-text"
            value="<?php echo esc_attr(isset($options['email_smtp_host']) ? $options['email_smtp_host'] : ''); ?>"
            placeholder="smtp.gmail.com" />
        </div>

        <div class="kh-form-field">
          <label for="kh_email_smtp_port"><?php esc_html_e('SMTP Port', 'khatm'); ?></label>
          <input name="kh_email_smtp_port" type="number" id="kh_email_smtp_port" class="small-text"
            value="<?php echo esc_attr(isset($options['email_smtp_port']) ? $options['email_smtp_port'] : '587'); ?>"
            placeholder="587" />
        </div>

        <div class="kh-form-field">
          <label for="kh_email_smtp_encryption"><?php esc_html_e('Encryption', 'khatm'); ?></label>
          <select name="kh_email_smtp_encryption" id="kh_email_smtp_encryption">
            <option value="tls" <?php selected('tls', isset($options['email_smtp_encryption']) ? $options['email_smtp_encryption'] : 'tls'); ?>>TLS</option>
            <option value="ssl" <?php selected('ssl', isset($options['email_smtp_encryption']) ? $options['email_smtp_encryption'] : ''); ?>>SSL</option>
          </select>
        </div>

        <div class="kh-form-field">
          <label for="kh_email_smtp_username"><?php esc_html_e('Username', 'khatm'); ?></label>
          <input name="kh_email_smtp_username" type="text" id="kh_email_smtp_username" class="regular-text"
            value="<?php echo esc_attr(isset($options['email_smtp_username']) ? $options['email_smtp_username'] : ''); ?>"
            placeholder="your-email@gmail.com" />
        </div>

        <div class="kh-form-field">
          <label for="kh_email_smtp_password"><?php esc_html_e('Password / App Password', 'khatm'); ?></label>
          <input name="kh_email_smtp_password" type="password" id="kh_email_smtp_password" class="regular-text"
            value="<?php echo esc_attr(isset($options['email_smtp_password']) ? $options['email_smtp_password'] : ''); ?>"
            placeholder="<?php esc_attr_e('App password or SMTP password', 'khatm'); ?>" />
          <p class="description">
            <?php esc_html_e('For Gmail, use an App Password (not your regular password). Generate one at myaccount.google.com.', 'khatm'); ?>
          </p>
        </div>
      </div>

      <div class="kh-form-field">
        <label for="kh_email_from"><?php esc_html_e('From Address', 'khatm'); ?></label>
        <input name="kh_email_from" type="email" id="kh_email_from" class="regular-text"
          value="<?php echo esc_attr(isset($options['email_from']) ? $options['email_from'] : get_option('admin_email')); ?>"
          placeholder="noreply@example.com" />
        <p class="description">
          <?php esc_html_e('The email address that will appear in the "From" field.', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label for="kh_email_subject"><?php esc_html_e('Email Subject', 'khatm'); ?></label>
        <input name="kh_email_subject" type="text" id="kh_email_subject" class="large-text"
          value="<?php echo esc_attr(isset($options['email_subject']) ? $options['email_subject'] : 'Reminder: {days_remaining} days left to complete your Juz'); ?>" />
        <p class="description">
          <?php esc_html_e('Supports placeholders (see below).', 'khatm'); ?>
        </p>
      </div>

      <div class="kh-form-field">
        <label><?php esc_html_e('Email Body', 'khatm'); ?></label>
        <?php
          $emailBody = isset($options['email_body']) ? $options['email_body'] : kh_email_default_body();
          wp_editor($emailBody, 'kh_email_body', [
            'textarea_name' => 'kh_email_body',
            'textarea_rows' => 12,
            'media_buttons' => false,
            'teeny'         => false,
            'quicktags'     => true,
            'tinymce'       => [
              'toolbar1' => 'bold,italic,underline,bullist,numlist,link,unlink,undo,redo',
              'toolbar2' => '',
            ],
          ]);
        ?>
        <p class="description" style="margin-top: 8px;">
          <?php esc_html_e('Compose the email body using the rich text editor above. HTML is supported.', 'khatm'); ?>
        </p>
      </div>

      <!-- Placeholder reference -->
      <div class="kh-form-field" style="background: #f9f9f9; padding: 12px 16px; border-radius: 6px; border: 1px solid #e0e0e0;">
        <strong><?php esc_html_e('Available Placeholders', 'khatm'); ?></strong>
        <p class="description" style="margin-top: 4px; margin-bottom: 8px;">
          <?php esc_html_e('Use these placeholders in the subject and body. They will be replaced with actual values when the email is sent.', 'khatm'); ?>
        </p>
        <table class="widefat" style="max-width: 500px;">
          <thead>
            <tr>
              <th><?php esc_html_e('Placeholder', 'khatm'); ?></th>
              <th><?php esc_html_e('Description', 'khatm'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (kh_email_get_placeholders() as $placeholder => $desc): ?>
              <tr>
                <td><code><?php echo esc_html($placeholder); ?></code></td>
                <td><?php echo esc_html($desc); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div style="display: flex; gap: 12px; align-items: center; margin-top: 24px;">
      <button type="submit" class="kh-btn-primary"><?php esc_html_e('Save Settings', 'khatm'); ?></button>
      <a href="<?php echo esc_url($dashboardUrl); ?>" class="kh-btn-secondary"><?php esc_html_e('Cancel', 'khatm'); ?></a>
    </div>
  </form>

  <!-- Manual trigger (outside the save form) -->
  <div class="kh-form-section" style="margin-top: 24px;">
    <h2><?php esc_html_e('Manual Trigger', 'khatm'); ?></h2>
    <p class="description" style="margin-bottom: 12px;">
      <?php esc_html_e('Send reminder emails now to all participants who have not completed their juz in the current khatam. This ignores the X/Y schedule and sends immediately.', 'khatm'); ?>
    </p>
    <form method="POST" action="admin-post.php" style="display: inline;">
      <input type="hidden" name="action" value="kh_send_reminders_now" />
      <?php wp_nonce_field('kh_send_reminders_now_verify'); ?>
      <button type="submit" class="kh-btn-secondary" onclick="return confirm('<?php esc_attr_e('Send reminder emails now to all incomplete participants?', 'khatm'); ?>');">
        <?php esc_html_e('Send Reminders Now', 'khatm'); ?>
      </button>
    </form>

    <?php if (isset($_GET['reminders_sent'])): ?>
      <div class="notice notice-success is-dismissible" style="margin-top: 12px;">
        <p><?php echo esc_html(sprintf(
          __('Done! %d reminder(s) sent, %d skipped (already sent today or failed).', 'khatm'),
          absint($_GET['reminders_sent']),
          absint($_GET['reminders_skipped'])
        )); ?></p>
      </div>
    <?php endif; ?>
  </div>

  <script>
  (function() {
    var transportSelect = document.getElementById('kh_email_transport');
    var smtpSettings = document.getElementById('kh-smtp-settings');

    if (transportSelect && smtpSettings) {
      transportSelect.addEventListener('change', function() {
        smtpSettings.style.display = (this.value === 'smtp') ? '' : 'none';
      });
    }
  })();
  </script>
</div>
  <?php
}
