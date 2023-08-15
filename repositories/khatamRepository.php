<?php

function khatamInsert(array $khatam) : int|false {
  global $wpdb;

    return $wpdb->insert(
        $wpdb->prefix . 'khatams',
        $khatam
    );
}

function khatamUpdate(array $khatam, int $id) {
  global $wpdb;

  return $wpdb->update(
    $wpdb->prefix . 'khatams',
    $khatam,
    [ 'id' => $id]
  );
}

function khatamDelete(int $id) : int|false {
  global $wpdb;

  return $wpdb->delete(
      $wpdb->prefix . 'khatams',
      [ 'id' => $id]
  );
}

function getKhatamById(int $id) {
  global $wpdb;

  $sql = <<<SQL
    SELECT
        id,
        start_date AS startDate,
        end_date AS endDate,
        meeting_link as meetingLink,
        meeting_ts as meetingTs
    FROM {$wpdb->prefix}khatams
    WHERE id = %d
  SQL;
  $stmt = $wpdb->prepare($sql, $id);

  return $wpdb->get_row($stmt);
}

function getFutureKhatams() {
  global $wpdb;

  $sql = <<<SQL
    SELECT
        id,
        start_date AS startDate,
        end_date AS endDate,
        meeting_link as meetingLink,
        meeting_ts as meetingTs
    FROM {$wpdb->prefix}khatams
    WHERE start_date > CURDATE()
    ORDER BY start_date DESC
  SQL;
  return $wpdb->get_results($sql);
}

/**
 * Gets a list of users and their details regarding current khatam
 *
 * @param int $khatamId
 * @return array|object|\stdClass[]|null
 */
function getKhatamUserList($khatamId) {
  global $wpdb;

  $sql = <<<SQL
    SELECT
      user_email AS email,
      status AS status,
      juz_num AS juz,
      first_name AS firstName,
      last_name AS lastName
    FROM {$wpdb->prefix}khatams_users
    WHERE khatam_id= %d
  SQL;

  $stmt = $wpdb->prepare($sql, $khatamId);

  return $wpdb->get_results($stmt);
}

/**
 * Get a khatam's stats
 *
 * @param int $id  Khatam ID
 *
 * @return object|void|null
 */
function getKhatamStats(int $id) {
  global $wpdb;

  $sql = <<<SQL
      SELECT 
          status,
          count
      FROM {$wpdb->prefix}khatams_users
      GROUP BY status
      WHERE
          khatam_id={$id}
  SQL;
  return $wpdb->get_results($sql);
}

/**
 * @return array|object|null
 */
function getCurrentKhatam() {
  global $wpdb;

  $sql = <<<SQL
    SELECT
        id,
        start_date AS startDate,
        end_date AS endDate,
        meeting_link as meetingLink,
        meeting_ts as meetingTs
    FROM {$wpdb->prefix}khatams
    WHERE start_date <= CURDATE()
        AND end_date >= CURDATE()
  SQL;

  return $wpdb->get_row($sql);
}

/**
 * @return array|bool
 */
function getKhatamUsers() {
  global $wpdb;

  $sql = <<<SQL
    SELECT * FROM {$wpdb->prefix}khatam_users
  SQL;

  return $wpdb->get_results($sql);
}