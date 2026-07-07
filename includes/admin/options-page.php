<?php

require_once __DIR__ . '/../../repositories/khatamRepository.php';
require_once __DIR__ . '/../../repositories/khatamUsersRepository.php';

function khatam_dashboard () {
    $list = getKhatams();
    $today = date('Y-m-d');

    // Compute stats
    $totalKhatams = count($list);
    $activeCount = 0;
    $upcomingCount = 0;
    $currentParticipants = 0;
    $currentKhatam = null;

    foreach ($list as $item) {
      if ($item->start_date <= $today && $item->end_date >= $today) {
        $activeCount++;
        $currentKhatam = $item;
      } elseif ($item->start_date > $today) {
        $upcomingCount++;
      }
    }

    if ($currentKhatam) {
      $participants = getKhatamUserList($currentKhatam->id);
      $currentParticipants = count($participants);
    }

    // Days remaining for current khatam
    $daysRemaining = '—';
    if ($currentKhatam) {
      $diff = (strtotime($currentKhatam->end_date) - strtotime($today)) / 86400;
      $daysRemaining = max(0, (int) $diff);
    }
?>
<div class="wrap kh-wrap">
  <h1><?php esc_html_e('Quran Khatm', 'khatm'); ?></h1>
  <p class="kh-subtitle"><?php esc_html_e('Manage your Quran reading groups', 'khatm'); ?></p>

  <?php if (isset($_GET['ended']) && $_GET['ended'] == '1'): ?>
    <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Khatam ended successfully.', 'khatm'); ?></p></div>
  <?php endif; ?>
  <?php if (isset($_GET['created']) && $_GET['created'] == '1'): ?>
    <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Khatam created successfully!', 'khatm'); ?></p></div>
  <?php endif; ?>
  <?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
    <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Khatam updated successfully!', 'khatm'); ?></p></div>
  <?php endif; ?>
  <?php if (isset($_GET['end_error']) && $_GET['end_error'] == '1'): ?>
    <div class="notice notice-error is-dismissible"><p><?php esc_html_e('Error ending khatam.', 'khatm'); ?></p></div>
  <?php endif; ?>

  <!-- Summary Cards -->
  <div class="kh-cards">
    <div class="kh-card">
      <div class="kh-card-value"><?php echo esc_html($totalKhatams); ?></div>
      <div class="kh-card-label"><?php esc_html_e('Total Khatams', 'khatm'); ?></div>
    </div>
    <div class="kh-card">
      <div class="kh-card-value green"><?php echo esc_html($activeCount); ?></div>
      <div class="kh-card-label"><?php esc_html_e('Active Now', 'khatm'); ?></div>
    </div>
    <div class="kh-card">
      <div class="kh-card-value blue"><?php echo esc_html($upcomingCount); ?></div>
      <div class="kh-card-label"><?php esc_html_e('Upcoming', 'khatm'); ?></div>
    </div>
    <div class="kh-card">
      <div class="kh-card-value"><?php echo esc_html($currentParticipants); ?>/30</div>
      <div class="kh-card-label"><?php esc_html_e('Current Participants', 'khatm'); ?></div>
    </div>
    <div class="kh-card">
      <div class="kh-card-value amber"><?php echo esc_html($daysRemaining); ?></div>
      <div class="kh-card-label"><?php esc_html_e('Days Remaining', 'khatm'); ?></div>
    </div>
  </div>

  <!-- Table Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
    <h2 style="margin: 0; font-size: 15px; font-weight: 600;"><?php esc_html_e('All Khatams', 'khatm'); ?></h2>
    <a href="?page=kh-plugin-options-alt" class="kh-btn-primary">+ <?php esc_html_e('New Khatam', 'khatm'); ?></a>
  </div>

  <?php if (empty($list)): ?>
    <div class="kh-empty">
      <div class="kh-empty-icon">&#128214;</div>
      <h3><?php esc_html_e('No khatams yet', 'khatm'); ?></h3>
      <p><?php esc_html_e('Create your first Quran reading group to get started.', 'khatm'); ?></p>
      <a href="?page=kh-plugin-options-alt" class="kh-btn-primary">+ <?php esc_html_e('Create Khatam', 'khatm'); ?></a>
    </div>
  <?php else: ?>
    <table class="kh-table">
      <thead>
        <tr>
          <th><?php esc_html_e('Name', 'khatm'); ?></th>
          <th><?php esc_html_e('Status', 'khatm'); ?></th>
          <th><?php esc_html_e('Duration', 'khatm'); ?></th>
          <th><?php esc_html_e('Participants', 'khatm'); ?></th>
          <th><?php esc_html_e('Meeting', 'khatm'); ?></th>
          <th><?php esc_html_e('Actions', 'khatm'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $item) :
          $status = 'past';
          $statusLabel = __('Completed', 'khatm');
          if ($item->start_date <= $today && $item->end_date >= $today) {
            $status = 'active';
            $statusLabel = __('Active', 'khatm');
          } elseif ($item->start_date > $today) {
            $status = 'upcoming';
            $statusLabel = __('Upcoming', 'khatm');
          }

          // Participant count for this khatam
          $itemParticipants = getKhatamUserList($item->id);
          $pCount = count($itemParticipants);

          $editUrl = admin_url('admin.php?page=kh-edit-khatam&khatam_id=' . $item->id);
          $participantsUrl = admin_url('admin.php?page=kh-manage-participants&khatam_id=' . $item->id);
          $endUrl = wp_nonce_url(
            admin_url('admin-post.php?action=kh_end_khatam&khatam_id=' . $item->id),
            'kh_end_khatam_' . $item->id
          );

          $startFormatted = date('M j, Y', strtotime($item->start_date));
          $endFormatted = date('M j, Y', strtotime($item->end_date));
        ?>
          <tr>
            <td class="kh-name-cell">
              <a href="<?php echo esc_url($editUrl); ?>"><?php echo esc_html($item->name ?: 'Khatam #' . $item->id); ?></a>
            </td>
            <td>
              <span class="kh-badge kh-badge-<?php echo esc_attr($status); ?>"><?php echo esc_html($statusLabel); ?></span>
            </td>
            <td>
              <?php echo esc_html($startFormatted); ?> — <?php echo esc_html($endFormatted); ?>
            </td>
            <td>
              <a href="<?php echo esc_url($participantsUrl); ?>"><?php echo esc_html($pCount); ?>/30</a>
            </td>
            <td>
              <?php if (!empty($item->meeting_link)): ?>
                <a href="<?php echo esc_url($item->meeting_link); ?>" target="_blank"><?php esc_html_e('Join', 'khatm'); ?> &rarr;</a>
              <?php else: ?>
                <span class="kh-muted">&mdash;</span>
              <?php endif; ?>
            </td>
            <td class="kh-actions">
              <a href="<?php echo esc_url($editUrl); ?>"><?php esc_html_e('Edit', 'khatm'); ?></a>
              <a href="<?php echo esc_url($participantsUrl); ?>"><?php esc_html_e('People', 'khatm'); ?></a>
              <?php if ($status !== 'past'): ?>
                <a href="<?php echo esc_url($endUrl); ?>" class="kh-danger"
                  onclick="return confirm('<?php echo esc_js(__('End this khatam? Its end date will be set to today.', 'khatm')); ?>');">
                  <?php esc_html_e('End', 'khatm'); ?>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php
}
