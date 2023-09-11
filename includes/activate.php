<?php 

function kh_activate_plugin () {
  createTables();
}

function createTables () {
  require_once(ABSPATH . "/wp-admin/includes/upgrade.php");
  // require_once(__DIR__ . "/../../../../wp-admin/includes/upgrade.php");
  global $wpdb;
  $charsetCollate = $wpdb->get_charset_collate();

  $khatams = "{$wpdb->prefix}khatams";
  $khatamUsers = "{$wpdb->prefix}khatam_users";
  $khatamsUsers = "{$wpdb->prefix}khatams_users";

  $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS {$khatams} (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      name VARCHAR(255),
      start_date DATE,
      end_date DATE,
      meeting_link VARCHAR(255),
      meeting_ts TIMESTAMP,
      created_on TIMESTAMP DEFAULT now(),
      updated_on TIMESTAMP DEFAULT now(),
      PRIMARY KEY (id)
    ) ENGINE = 'InnoDB' {$charsetCollate};
  SQL;
  dbDelta($schema);

  $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS {$khatamUsers} (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      email VARCHAR(255) NOT NULL,
      first_name VARCHAR(255),
      last_name VARCHAR(255),
      registered_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
      PRIMARY KEY (id)
    )  ENGINE = 'InnoDB' {$charsetCollate};
  SQL;
  dbDelta($schema);

  $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS {$khatamsUsers} (
      khatam_id BIGINT(20),
      user_email VARCHAR(255),
      first_name VARCHAR(255),
      last_name VARCHAR(255),
      status TINYINT NOT NULL DEFAULT 0,
      juz_num TINYINT
    ) ENGINE='InnoDB' {$charsetCollate};
  SQL;
  dbDelta($schema);
}
