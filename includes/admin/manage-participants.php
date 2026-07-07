<?php

function kh_manage_participants_page () {
  require_once __DIR__ . '/../../repositories/khatamRepository.php';
  require_once __DIR__ . '/../../repositories/khatamUsersRepository.php';

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

  $notice = '';
  $noticeType = '';

  // Handle add participant
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kh_add_participant_nonce'])) {
    if (!wp_verify_nonce($_POST['kh_add_participant_nonce'], 'kh_add_participant_' . $id)) {
      wp_die(__('Security check failed.', 'khatm'));
    }

    $email = sanitize_email($_POST['kh_participant_email']);
    $firstName = sanitize_text_field($_POST['kh_participant_first_name']);
    $lastName = sanitize_text_field($_POST['kh_participant_last_name']);
    $juz = absint($_POST['kh_participant_juz']);

    if (empty($email) || empty($firstName)) {
      $notice = __('Email and first name are required.', 'khatm');
      $noticeType = 'error';
    } else {
      $existing = khatamsUsersGetByJuz($id, $juz);
      if ($existing) {
        $notice = sprintf(
          __('Juz %d is already assigned to %s %s (%s).', 'khatm'),
          $juz, $existing->firstName, $existing->lastName, $existing->email
        );
        $noticeType = 'error';
      } else {
        $result = khatamsUsersInsert($email, $firstName, $lastName, $id, $juz);
        if ($result !== false) {
          $notice = __('Participant added successfully!', 'khatm');
          $noticeType = 'success';
        } else {
          $notice = __('Error adding participant.', 'khatm');
          $noticeType = 'error';
        }
      }
    }
  }

  // Handle delete participant
  if (isset($_GET['action']) && $_GET['action'] === 'delete_participant') {
    if (!wp_verify_nonce($_GET['_wpnonce'], 'kh_delete_participant_' . $id)) {
      wp_die(__('Security check failed.', 'khatm'));
    }

    $delEmail = sanitize_email($_GET['email']);
    $delFirst = sanitize_text_field($_GET['first_name']);
    $delLast = sanitize_text_field($_GET['last_name']);

    $result = khatamsUsersDelete($delEmail, $delFirst, $delLast, $id);
    if ($result !== false) {
      $notice = __('Participant removed.', 'khatm');
      $noticeType = 'success';
    } else {
      $notice = __('Error removing participant.', 'khatm');
      $noticeType = 'error';
    }
  }

  // Handle change juz
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kh_change_juz_nonce'])) {
    if (!wp_verify_nonce($_POST['kh_change_juz_nonce'], 'kh_change_juz_' . $id)) {
      wp_die(__('Security check failed.', 'khatm'));
    }

    $juzEmail = sanitize_email($_POST['kh_juz_email']);
    $juzFirst = sanitize_text_field($_POST['kh_juz_first_name']);
    $juzLast = sanitize_text_field($_POST['kh_juz_last_name']);
    $newJuz = absint($_POST['kh_new_juz']);

    if ($newJuz >= 1 && $newJuz <= 30) {
      $existing = khatamsUsersGetByJuz($id, $newJuz);
      if ($existing && !($existing->email === $juzEmail && $existing->firstName === $juzFirst && $existing->lastName === $juzLast)) {
        $notice = sprintf(
          __('Juz %d is already assigned to %s %s (%s).', 'khatm'),
          $newJuz, $existing->firstName, $existing->lastName, $existing->email
        );
        $noticeType = 'error';
      } else {
        $result = khatamsUsersUpdateJuz($juzEmail, $juzFirst, $juzLast, $id, $newJuz);
        if ($result !== false) {
          $notice = __('Juz assignment updated.', 'khatm');
          $noticeType = 'success';
        } else {
          $notice = __('Error updating juz.', 'khatm');
          $noticeType = 'error';
        }
      }
    } else {
      $notice = __('Juz must be between 1 and 30.', 'khatm');
      $noticeType = 'error';
    }
  }

  // Get participants list sorted by juz
  $participants = getKhatamUserList($id);
  usort($participants, function($a, $b) { return $a->juz - $b->juz; });
  $participantCount = count($participants);
  $completedCount = count(array_filter($participants, function($p) { return $p->status == 1; }));
  $progressPercent = $participantCount > 0 ? round(($completedCount / $participantCount) * 100) : 0;

  // Find next available juz
  $assignedJuzs = array_map(function($p) { return (int)$p->juz; }, $participants);
  $nextJuz = 1;
  for ($i = 1; $i <= 30; $i++) {
    if (!in_array($i, $assignedJuzs)) {
      $nextJuz = $i;
      break;
    }
  }

  $khatamName = $khatam->name ?: 'Khatam #' . $id;
  $dashboardUrl = admin_url('admin.php?page=quran-khatam-dashboard');
?>
<div class="wrap kh-wrap">

  <!-- Breadcrumb -->
  <div class="kh-breadcrumb">
    <a href="<?php echo esc_url($dashboardUrl); ?>"><?php esc_html_e('Quran Khatm', 'khatm'); ?></a>
    <span class="kh-sep">&rsaquo;</span>
    <span><?php echo esc_html($khatamName); ?></span>
    <span class="kh-sep">&rsaquo;</span>
    <span><?php esc_html_e('Participants', 'khatm'); ?></span>
  </div>

  <h1><?php printf(esc_html__('Participants — %s', 'khatm'), esc_html($khatamName)); ?></h1>
  <p class="kh-subtitle"><?php echo esc_html($participantCount); ?>/30 <?php esc_html_e('spots filled', 'khatm'); ?> &middot; <?php echo esc_html($completedCount); ?> <?php esc_html_e('completed', 'khatm'); ?></p>

  <?php if ($notice): ?>
    <div class="notice notice-<?php echo esc_attr($noticeType); ?> is-dismissible">
      <p><?php echo esc_html($notice); ?></p>
    </div>
  <?php endif; ?>

  <!-- Progress Bar -->
  <div class="kh-progress-wrap">
    <div class="kh-progress-label">
      <span><?php esc_html_e('Completion Progress', 'khatm'); ?></span>
      <span><?php echo esc_html($completedCount); ?>/<?php echo esc_html($participantCount); ?> <?php esc_html_e('juz completed', 'khatm'); ?> (<?php echo esc_html($progressPercent); ?>%)</span>
    </div>
    <div class="kh-progress-bar">
      <div class="kh-progress-fill" style="width: <?php echo esc_attr($progressPercent); ?>%;"></div>
    </div>
  </div>

  <!-- Add Participant Form (above table) -->
  <?php if ($participantCount < 30): ?>
    <div class="kh-inline-form">
      <h3><?php esc_html_e('Add Participant', 'khatm'); ?></h3>
      <form method="POST">
        <?php wp_nonce_field('kh_add_participant_' . $id, 'kh_add_participant_nonce'); ?>
        <div class="kh-form-grid">
          <div>
            <label for="kh_participant_first_name"><?php esc_html_e('First Name', 'khatm'); ?></label>
            <input name="kh_participant_first_name" type="text" id="kh_participant_first_name" required />
          </div>
          <div>
            <label for="kh_participant_last_name"><?php esc_html_e('Last Name', 'khatm'); ?></label>
            <input name="kh_participant_last_name" type="text" id="kh_participant_last_name" />
          </div>
          <div>
            <label for="kh_participant_email"><?php esc_html_e('Email', 'khatm'); ?></label>
            <input name="kh_participant_email" type="email" id="kh_participant_email" required />
          </div>
          <div>
            <label for="kh_participant_juz"><?php esc_html_e('Juz', 'khatm'); ?></label>
            <input name="kh_participant_juz" type="number" id="kh_participant_juz" min="1" max="30"
              value="<?php echo esc_attr($nextJuz); ?>" class="kh-juz-input" style="width:100%;" required />
          </div>
          <div>
            <label>&nbsp;</label>
            <button type="submit" class="kh-btn-primary" style="width:100%;"><?php esc_html_e('Add', 'khatm'); ?></button>
          </div>
        </div>
      </form>
    </div>
  <?php else: ?>
    <div class="kh-inline-form">
      <p style="margin:0; font-weight: 500; color: var(--kh-green-600);">&#10003; <?php esc_html_e('This khatam is full — all 30 juz are assigned.', 'khatm'); ?></p>
    </div>
  <?php endif; ?>

  <!-- Participants Table -->
  <?php if (empty($participants)): ?>
    <div class="kh-empty">
      <div class="kh-empty-icon">&#128100;</div>
      <h3><?php esc_html_e('No participants yet', 'khatm'); ?></h3>
      <p><?php esc_html_e('Add the first participant using the form above.', 'khatm'); ?></p>
    </div>
  <?php else: ?>
    <table class="kh-table">
      <thead>
        <tr>
          <th><?php esc_html_e('Juz', 'khatm'); ?></th>
          <th><?php esc_html_e('Name', 'khatm'); ?></th>
          <th><?php esc_html_e('Email', 'khatm'); ?></th>
          <th><?php esc_html_e('Status', 'khatm'); ?></th>
          <th><?php esc_html_e('Actions', 'khatm'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($participants as $p):
          $rowClass = $p->status == 1 ? 'kh-row-complete' : 'kh-row-pending';
          $deleteUrl = wp_nonce_url(
            admin_url('admin.php?page=kh-manage-participants&khatam_id=' . $id . '&action=delete_participant&email=' . urlencode($p->email) . '&first_name=' . urlencode($p->firstName) . '&last_name=' . urlencode($p->lastName)),
            'kh_delete_participant_' . $id
          );
        ?>
          <tr class="<?php echo esc_attr($rowClass); ?>">
            <td>
              <form method="POST" style="display:inline; white-space:nowrap;">
                <?php wp_nonce_field('kh_change_juz_' . $id, 'kh_change_juz_nonce'); ?>
                <input type="hidden" name="kh_juz_email" value="<?php echo esc_attr($p->email); ?>" />
                <input type="hidden" name="kh_juz_first_name" value="<?php echo esc_attr($p->firstName); ?>" />
                <input type="hidden" name="kh_juz_last_name" value="<?php echo esc_attr($p->lastName); ?>" />
                <input type="number" name="kh_new_juz" value="<?php echo esc_attr($p->juz); ?>" min="1" max="30" class="kh-juz-input" />
                <button type="submit" class="button button-small"><?php esc_html_e('Set', 'khatm'); ?></button>
              </form>
            </td>
            <td class="kh-name-cell"><?php echo esc_html($p->firstName . ' ' . $p->lastName); ?></td>
            <td><?php echo esc_html($p->email); ?></td>
            <td>
              <?php if ($p->status == 1): ?>
                <span class="kh-badge kh-badge-complete"><?php esc_html_e('Completed', 'khatm'); ?></span>
              <?php else: ?>
                <span class="kh-badge kh-badge-pending"><?php esc_html_e('In Progress', 'khatm'); ?></span>
              <?php endif; ?>
            </td>
            <td class="kh-actions">
              <a href="<?php echo esc_url($deleteUrl); ?>" class="kh-danger"
                onclick="return confirm('<?php echo esc_js(__('Remove this participant?', 'khatm')); ?>');">
                <?php esc_html_e('Remove', 'khatm'); ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php
}
