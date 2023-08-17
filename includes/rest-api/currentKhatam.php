<?php

function kh_rest_api_current_khatam_read_handler ($request) {
  require_once(ABSPATH . "/wp-content/plugins/khatam/repositories/khatamUsersRepository.php");
  require_once(ABSPATH . "/wp-content/plugins/khatam/repositories/khatamRepository.php");
  $currentKhatam = getCurrentKhatam();

  $response['status'] = 2;
  $response['khatam_id'] = $currentKhatam->id;
  $response['data'] = array_map(
    function ($user) {
      $user->lastName = substr($user->lastName, 0, 2);
      return $user;
    }, 
    getKhatamUserList($currentKhatam->id)
  );

  return $response;
}