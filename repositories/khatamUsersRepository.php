<?php

function khatamsUsersInsert($email, $firstName, $lastName, $khatamId=0, $juz=0) {
  global $wpdb;

  if ($khatamId > 0) {
    $rs = $wpdb->insert(
      $wpdb->prefix . 'khatams_users',
      [
        'khatam_id' => $khatamId,
        'user_email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'juz_num' => $juz
      ]
    );

    if ($rs === false) {
      return $rs;
    }
  }

  return $rs;
}

function khatamsUsersUpdateStatus($email, $khatamId, $status) : bool {
  global $wpdb;

  return $wpdb->update(
    $wpdb->prefix . 'khatams_users',
    [
      'status' => $status,
    ],
    [
      'khatam_id' => $khatamId,
      'user_email' => $email
    ]
  );
}

function KhatamUsersInsert($firstname, $lastname, $email) : int | false {
  global $wpdb;

  $rs = $wpdb->replace(
    $wpdb->prefix . 'khatam_users',
    [
      'email' => $email,
      'first_name' => $firstname,
      'last_name' => $lastname,
    ]
  );
  return $rs;
}