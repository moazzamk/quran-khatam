<?php
/**
 * Admin Onboarding Tutorial Overlay
 *
 * Renders a multi-step guided tutorial overlay on the dashboard page
 * when the admin visits for the first time after plugin activation.
 */

/**
 * Get the tutorial steps configuration.
 *
 * Each step has:
 *   - title: Step heading
 *   - content: Description text
 *   - target: CSS selector of the element to highlight (null for centered modal)
 *   - position: Tooltip position relative to target (top, bottom, left, right)
 *
 * @return array
 */
function kh_get_tutorial_steps() {
  return [
    [
      'title'    => __('Welcome to Quran Khatm!', 'khatm'),
      'content'  => __('This plugin helps you organize collaborative Quran reading. 30 people each read one Juz, completing the entire Quran together. Let\'s walk you through the main features.', 'khatm'),
      'target'   => null,
      'position' => 'center',
    ],
    [
      'title'    => __('Dashboard Overview', 'khatm'),
      'content'  => __('This is your main dashboard. The summary cards show your total khatams, active sessions, participants, and days remaining at a glance.', 'khatm'),
      'target'   => '.kh-cards',
      'position' => 'bottom',
    ],
    [
      'title'    => __('Khatam List', 'khatm'),
      'content'  => __('Below the cards you\'ll find all your khatams listed with their status (Active, Upcoming, or Completed), duration, participant count, and actions.', 'khatm'),
      'target'   => '.kh-table, .kh-empty',
      'position' => 'top',
    ],
    [
      'title'    => __('Create a New Khatam', 'khatm'),
      'content'  => __('Click "New Khatam" to create a reading session. You\'ll set a name, start/end dates, and an optional meeting link for your group.', 'khatm'),
      'target'   => '.kh-btn-primary',
      'position' => 'left',
    ],
    [
      'title'    => __('Manage Participants', 'khatm'),
      'content'  => __('From the khatam list, click "People" to view and manage participants. You can add/remove people, reassign juz numbers, and track who has completed their reading.', 'khatm'),
      'target'   => '.kh-actions',
      'position' => 'top',
    ],
    [
      'title'    => __('Settings & Email Reminders', 'khatm'),
      'content'  => __('Go to Settings to configure Open Graph sharing, email reminders (automatic notifications to participants who haven\'t finished), SMTP transport, and message templates with placeholders.', 'khatm'),
      'target'   => '#adminmenu .toplevel_page_quran-khatam-dashboard .wp-submenu',
      'position' => 'right',
    ],
    [
      'title'    => __('Add Blocks to Your Pages', 'khatm'),
      'content'  => __('Use the Gutenberg editor to add the "Khatam Form" block (signup form) and "Khatam Table" block (participant progress) to any page. Visitors can sign up and mark their juz complete right from the front end.', 'khatm'),
      'target'   => null,
      'position' => 'center',
    ],
    [
      'title'    => __('You\'re All Set!', 'khatm'),
      'content'  => __('That\'s everything you need to get started. Create your first khatam, share the page with your community, and watch the progress come together. JazakAllahu Khairan!', 'khatm'),
      'target'   => null,
      'position' => 'center',
    ],
  ];
}

/**
 * Render the tutorial overlay HTML.
 * Called from the dashboard page when the tutorial flag is active.
 */
function kh_render_tutorial_overlay() {
  $steps = kh_get_tutorial_steps();
  $stepsJson = wp_json_encode($steps);
  ?>
  <div id="kh-tutorial-overlay" class="kh-tutorial-overlay">
    <div class="kh-tutorial-backdrop"></div>
    <div class="kh-tutorial-spotlight"></div>
    <div class="kh-tutorial-tooltip">
      <div class="kh-tutorial-tooltip-arrow"></div>
      <div class="kh-tutorial-step-indicator">
        <span class="kh-tutorial-step-current">1</span> / <span class="kh-tutorial-step-total"><?php echo count($steps); ?></span>
      </div>
      <h3 class="kh-tutorial-title"></h3>
      <p class="kh-tutorial-content"></p>
      <div class="kh-tutorial-actions">
        <button type="button" class="kh-tutorial-btn-skip"><?php esc_html_e('Skip Tour', 'khatm'); ?></button>
        <div class="kh-tutorial-nav">
          <button type="button" class="kh-tutorial-btn-prev" disabled><?php esc_html_e('Back', 'khatm'); ?></button>
          <button type="button" class="kh-tutorial-btn-next"><?php esc_html_e('Next', 'khatm'); ?></button>
        </div>
      </div>
    </div>
  </div>
  <script>
    window.khTutorialSteps = <?php echo $stepsJson; ?>;
    window.khTutorialDismissUrl = '<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=kh_dismiss_tutorial'), 'kh_dismiss_tutorial_nonce')); ?>';
  </script>
  <?php
}
