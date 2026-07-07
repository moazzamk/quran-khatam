<?php 

function kh_activate_plugin () {
  createTables();

  // Show tutorial on first admin visit after activation
  update_option('kh_show_tutorial', '1');
}

function createTables () {
  ob_start();
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

  // Email reminder log table
  $emailLog = "{$wpdb->prefix}khatam_email_log";
  $schema = <<<SQL
    CREATE TABLE IF NOT EXISTS {$emailLog} (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      khatam_id BIGINT(20) NOT NULL,
      email VARCHAR(255) NOT NULL,
      sent_date DATE NOT NULL,
      success TINYINT NOT NULL DEFAULT 0,
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
      PRIMARY KEY (id),
      KEY idx_khatam_email_date (khatam_id, email, sent_date)
    ) ENGINE='InnoDB' {$charsetCollate};
  SQL;
  dbDelta($schema);

  // Create a khatam if the table is empty then create a Khatam
  $row_count = $wpdb->get_var("SELECT COUNT(*) FROM {$khatams}");

  if ($row_count > 0) {
    return;
  }

  $wpdb->insert("{$wpdb->prefix}khatams", array(
    "id" => 24,
    "name" => 'xyz_khatam',
    "start_date" => '2023-12-10',
    "end_date" => '2023-12-30',
    "meeting_link" => 'asda.com',
    "meeting_ts" => 'asda.com',
    "created_on" => '2023-12-10',
    "updated_on" => '2023-12-10',

  ));
}
