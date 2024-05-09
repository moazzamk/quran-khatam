<?php

function kh_khatam_form_render_cb ($atts) {
	$khatamStatus = updateKhatamInfo();

	ob_start();
	?>
		<section id="kh-form-container"
			<?php if ($khatamStatus != false) { ?>
				data-is-khatam-full="<?php echo $khatamStatus['is_khatam_full']; ?>"
				data-available-spots="<?php echo $khatamStatus['available_spots']; ?>"
			<?php } ?>
		>

		</section>
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

function updateKhatamInfo () {
  require_once(__DIR__ . "/../../repositories/khatamUsersRepository.php");
  require_once(__DIR__ . "/../../repositories/khatamRepository.php");

	$currentKhatam = getCurrentKhatam();

	if ($currentKhatam != null) {
		$users = getKhatamUserList($currentKhatam->id);
		$isKhatamFull = count($users) >= 30 ? true : false;
	
		return [
			'available_spots' => 30 - count($users),
			'is_khatam_full' => $isKhatamFull
		];
	}

	return false;
}
