<?php

function khatamsUsersInsert(
  $email, $firstName, $lastName, $khatamId=0, $juz=0
) {
  global $wpdb;

  if ($khatamId <= 0 || $khatamId == null) {
    return false;
  }

  $rs = $wpdb->insert(
    $wpdb->prefix . 'khatams_users',
    [
      'user_email' => $email,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'khatam_id' => $khatamId,
      'juz_num' => $juz
    ]
  );
  
  if ($rs === false) {
    return $rs;
  }

  return $rs;
}

function khatamsUsersUpdateStatus(
  $email, $firstName, $lastName, $khatamId, $status
) : bool {
  global $wpdb;

  return $wpdb->update(
    $wpdb->prefix . 'khatams_users',
    [
      'status' => $status,
    ],
    [
      'khatam_id' => $khatamId,
      'user_email' => $email,
      'first_name' => $firstName,
      'last_name' => $lastName
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

function KhatamUsersGetUser(
  string $email, string $firstname, string $lastname, int $khatamId
): array|object|null|string {
  global $wpdb;
  $sql = <<<SQL
    SELECT * FROM {$wpdb->prefix}khatams_users WHERE (
        khatam_id={$khatamId} AND
        user_email="{$email}" AND
        first_name="{$firstname}" AND
        last_name="{$lastname}"
    );
  SQL;

  // return $sql;
  $rs = $wpdb->get_row(
    $sql
  );

  return $rs;
}