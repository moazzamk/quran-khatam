<?php 

function kh_form_shortcode () {
  $plugin_dir = WP_PLUGIN_DIR . '/khatam';
  wp_enqueue_script('kh-form', $plugin_dir . '/shortcode/khatm-form/index.js', [], null);

  ob_start();
	?>
		<section id="kh-form-container">
      <h3>I work!</h3>
      <p><?php echo $plugin_dir . '/shortcode/khatm-form/index.js'; ?></p>
		</section>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}