<?php

function kh_rest_api_users_create_handler ($request) {
  require_once(ABSPATH . "/wp-content/plugins/khatam/repositories/khatamUsersRepository.php");
  require_once(ABSPATH . "/wp-content/plugins/khatam/repositories/khatamRepository.php");

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

  // INSERT INTO DB khatam_users
  foreach($names as $name) {
    KhatamUsersInsert($name['firstName'], $name['lastName'], $email);

    // If user has already signup for current khatam
    $userMatches = user_exists($name, $email, $users);
    if ($userMatches) {
      $response['msg'] = 'The user(s) have already signed up for current khatam.';
      $response['data'] = $userMatches;

      return $response;
    }
  }

  // ADDING USERS TO CURRENT KHATAM
  $currentKhatam = getCurrentKhatam();
  $users = getKhatamUserList($currentKhatam->id);
  $results = [];

  // $response['users'] = $users;
  // return $response;

  if (count($users) >= 30) {
    foreach ($names as $name) {
      array_push(
        $results,
        array(
          'name' => $name,
          'email' => $email,
          'juz' => '---',
          'isSuccess' => false
        )
      );
    }
    $response['results'] = $results;
    return $response;
  } else {
    $remainingJuz = 30 - count($users);
    $noOfNamesToAdd = count($names) > $remainingJuz ? 
      $remainingJuz : count($names);

    // $response['noOfNamesToAdd'] = $noOfNamesToAdd;
    // return $response;

    // Add names that CAN be added to the khatam
    $juz = count($users) === 0 ? 0 : count($users);
    for ($i = 0; $i < $noOfNamesToAdd; $i++) {
      $juz = ++$juz;

      // Insert to khatams_users
      $rs = khatamsUsersInsert(
        $email,
        $name['firstName'],
        $name['lastName'],
        $currentKhatam->id,
        $juz
      );

      if ($rs === false) {
        return $response;
      }

      array_push($results, array(
        'name' => $name,
        'email' => $email,
        'juz' => $juz,
        'isSuccess' => true
      ));
    }

    $namesNotAdded = array_slice($names, $noOfNamesToAdd);
    foreach ($namesNotAdded as $name) {
      array_push($results, array(
        'name' => $name,
        'email'=> $email,
        'juz' => NULL,
        'isSuccess' => false
      ));
    }
  }

  $response['results'] = $results;
  $response['openSlots'] = $remainingJuz - $noOfNamesToAdd;
  $response['status'] = 2;
  return $response;
}

function kh_rest_api_users_read_handler ($request) {
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

// SHOULD RETURN AN ARRAY OF ALREADY EXISTING USERS OR FALSE?
function user_exists ($name, $email, $users) {
  $userMatches = [];
    foreach($users as $u) {
    if (
      $u->email == $email &&
      $u->firstName == $name['firstName'] &&
      $u->lastName == $name['lastName']
    ) {
      array_push($userMatches, $u);
    }
  }

  if (count($userMatches) > 0) {
    return $userMatches;
  }
  return false;
}
