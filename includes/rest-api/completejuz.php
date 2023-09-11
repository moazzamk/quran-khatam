<?php

function kh_rest_api_current_khatam_complete_juz_handler ($request) {
  require_once(__DIR__ . "/../../repositories/khatamUsersRepository.php");
  require_once(__DIR__ . "/../../repositories/khatamRepository.php");
  $currentKhatam = getCurrentKhatam();
  $users = getKhatamUserList($currentKhatam->id);
  $results = [];
  $response = ['status' => 1];
  $params = $request->get_json_params();

  if (
    !isset($params['email'], $params['names']) ||
    empty($params['email']) ||
    empty($params['names'])
  ) {
    return $response;
  }

  $email = sanitize_email($params['email']);
  $names = array_map(
    function ($n) {
      return [
        'firstName' => trim(strtolower(explode(" ", sanitize_text_field($n))[0]), " \n\r\t\v\x00"),
        'lastName' => trim(strtolower(explode(" ", sanitize_text_field($n))[1]), " \n\r\t\v\x00"),
      ];
    },
    $params['names']
  );

  if (!is_email($email)) {
    return $response;
  }

  foreach($names as $name) {
    $isSuccess = khatamsUsersUpdateStatus(
      $email, $name['firstName'], $name['lastName'], $currentKhatam->id, 1
    );

    if (!$isSuccess) {
      $response['msg'] = 'User not found.';
      return $response;
    }
  }

  $response['status'] = 2;
  return $response;
}