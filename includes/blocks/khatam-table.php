<?php

function kh_khatam_table_render_cb ($atts) {

	ob_start();
	?>
		<section id="kh-table-container" class="kh-users">

		</section>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}
