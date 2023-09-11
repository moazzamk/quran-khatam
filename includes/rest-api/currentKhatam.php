<?php
function kh_rest_api_current_khatam_read_handler ($request) {
  require_once(__DIR__ . "/../../repositories/khatamUsersRepository.php");
  require_once(__DIR__ . "/../../repositories/khatamRepository.php");
  $currentKhatam = getCurrentKhatam();
  $response = [
    'status' => 1,
    'msg' => 'There is no active Khatam',
    'khatam' => $currentKhatam
  ];


  if (isset($currentKhatam)) {
    $response['status'] = 2;
    $response['msg'] = 'success';

    $response['khatam_id'] = $currentKhatam->id;
    $response['data'] = array_map(
      function ($user) {
        $user->lastName = substr($user->lastName, 0, 2);
        return $user;
      }, 
      getKhatamUserList($currentKhatam->id)
    );
  }

  return $response;
}
