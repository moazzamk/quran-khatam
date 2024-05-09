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
    $user = KhatamUsersGetUser(
      $email, $name['firstName'], $name['lastName'], $currentKhatam->id
    );

    // $response['msg'] = $user;
    // return $response;

    if (is_null($user)) {
      $response['msg'] = 'User not found.';
      return $response;
    }

    if ($user->status == 1) {
      $response['msg'] = 'One or more of users have already completed their Juz.';
      return $response;
    } else {
      $isSuccess = khatamsUsersUpdateStatus(
        $email, $name['firstName'], $name['lastName'], $currentKhatam->id, 1
      );
  
      if (!$isSuccess) {
        $response['msg'] = 'Unknown error.';
        return $response;
      }
    }

  }

  $response['status'] = 2;
  return $response;
}
