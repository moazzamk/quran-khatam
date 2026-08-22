<?php

require_once __DIR__ . '/../../repositories/khatamRepository.php';

function khatam_dashboard () {
    global $wpdb;
    $options = get_option('kh_options');
    $khatamsRepo = new \Khatam\Repositories\KhatamRepository($wpdb);
    $list = $khatamsRepo->getKhatams();
?>
<style type="text/css">
  .khatam-list-admin th {
      background-color: #1d2327;
      color: #f0f0f1;
  }
</style>
<div class="wrap khatam-list-admin">
    <h1 class="wp-heading-inline"><?php esc_html_e('Quran Khatm', 'khatm' ); ?></h1>
    <a href="?page=kh-plugin-options-alt" class="page-title-action">Add Khatam</a>

    <table style="width: 100%">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Start</th>
            <th>End</th>
            <th>Meet link</th>
            <th>Meet On</th>
            <th>Created On</th>
            <th>Updated On</th>
        </tr>
        <?php foreach ($list as $item) : ?>
            <tr>
                <td><?= $item->id ?></td>
                <td><?= $item->name ?></td>
                <td><?= $item->start_date ?></td>
                <td><?= $item->end_date ?></td>
                <td>
                    <?php if (!empty($item->meeting_link)): ?>
                        <a href="<?= $item->meeting_link ?>" target="_blank">Link</a>
                    <?php endif; ?>
                </td>
                <td><?= $item->meeting_ts ?></td>
                <td><?= $item->created_on ?></td>
                <td><?= $item->updated_on ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

  <div class="wrap">
    <h1><?php esc_html_e('Quran Khatm Settings', 'khatm' ); ?></h1>
    <?php
      if (isset($_GET['status']) && $_GET['status'] == '1') {
        ?>
          <div class="notice notice-success inline">
            <p>
              <?php esc_html_e('Options updated successfully!', 'khatm'); ?>
            </p>
          </div>
        <?php
      }
    ?>
    <form novalidate="novalidate" method="POST" action="admin-post.php">
      <input type="hidden" name="action" value="kh_save_options" />
      <?php wp_nonce_field('kh_options_verify'); ?>
      <table class="form-table">
        <tbody>
          <!-- Open Graph Title -->
          <tr>
            <th>
              <label for="kh_og_title">
                <?php esc_html_e('Open Graph Title', 'khatm'); ?>
              </label>
            </th>
            <td>
              <input name="kh_og_title" type="text" id="kh_og_title"
                class="regular-text" 
                value="<?php echo esc_attr($options['og_title']); ?>"  
              />
            </td>
          </tr>
          <!-- Open Graph Image -->
          <tr>
            <th>
              <label for="kh_og_img">
                <?php esc_html_e('Open Graph Image', 'khatm'); ?>
              </label>
            </th>
            <td>
              <input type="hidden" name="kh_og_img" id="kh_og_img"
                value="<?php echo esc_attr($options['og_img']); ?>" 
              />
              <img id="og-img-preview" src="<?php echo esc_attr($options['og_img']); ?>">
              <a href="#" class="button-primary" id="og-img-btn">
                Select Image
              </a>
            </td>
          </tr>
          <!-- Open Graph Description -->
          <tr>
            <th>
              <label for="kh_og_description">
                <?php esc_html_e('Open Graph Description', 'khatm'); ?>
              </label>
            </th>
            <td>
              <textarea 
                id="kh_og_description" 
                name="kh_og_description"
                class="large-text"
              ><?php echo esc_attr($options['og_description']); ?></textarea>
            </td>
          </tr>
          <!-- Enable Open Graph -->
          <tr>
            <th>
              <?php esc_html_e('Open Graph', 'khatm'); ?>
            </th>
            <td>
            <label for="kh_enable_og"> 
              <input name="kh_enable_og" type="checkbox" id="kh_enable_og" 
                value="1" <?php checked('1', $options['enable_og']); ?> /> 
              <span>Enable</span>
            </label>
            </td>
          </tr>
        </tbody>
      </table>

      <?php submit_button(); ?>
    </form>
  </div>

  <?php
}