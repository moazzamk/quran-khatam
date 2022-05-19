<?php

namespace Khatam;

use \Khatam\Commands\MeetingReminderCommand;

class Setup
{
	private MeetingReminderCommand $meetingReminder;
	private \wpdb $db;

	public function __construct(
		MeetingReminderCommand $meetingReminder,
		\wpdb $db
	) {
		$this->meetingReminder = $meetingReminder;
		$this->db = $db;

		$this->activate();
	}

	public function activate() 
	{
		$this->install($this->db);
		$this->setupCron();
	}

    public function deactivate()
    {
		$this->removeCron();
		$this->uninstall();
	}

    protected function uninstall()
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $tables = [
            "{$db->prefix}khatams",
            "{$db->prefix}khatam_users",
            "{$db->prefix}khatams_users"
        ];

        foreach ($tables as $table) {
            $sql = 'DROP TABLE ' . $table;
            // $this->db->query($sql);
            // error_log($sql);
            dbDelta($sql);
        }
    }

	protected static function install($db)
	{
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$collate = $db->get_charset_collate();
		error_log($collate);
		$schema = <<<SQL
			CREATE TABLE IF NOT EXISTS {$db->prefix}khatams (
				id INT AUTO_INCREMENT,
				name VARCHAR(255),
				start_date DATE,
				end_date DATE,
				meeting_link VARCHAR(255),
				meeting_ts TIMESTAMP,
				created_on TIMESTAMP default now(),
				updated_on TIMESTAMP default now(),
				PRIMARY KEY (id)
			) $collate;
SQL;
        dbDelta($schema);

        $schema = <<<SQL
			CREATE TABLE IF NOT EXISTS {$db->prefix}khatam_users (
				email varchar(255) NOT NULL,
				name varchar(255) ,
				registered_on TIMESTAMP,
				PRIMARY KEY(email)
			) $collate;
SQL;
        dbDelta($schema);

        $schema = <<<SQL
            CREATE TABLE IF NOT EXISTS {$db->prefix}khatams_users (
				khatam_id INT,
				user_email VARCHAR(255),
				status TINYINT NOT NULL DEFAULT 0,
				juz_num TINYINT
			) $collate;
SQL;

		dbDelta($schema);
		add_option('khatam_db_version', '1');

	}

	protected function setupCron()
	{
		add_action(
			'kq_check_meeting_hook', 
			$this->meetingReminder, 
			10
		);

		if ( ! wp_next_scheduled( 'kq_check_meeting_hook' ) ) {
			wp_schedule_event( 
				time(), 
				'daily', 
				'kq_check_meeting_hook' 
			);
		}
	}

	protected function removeCron()
	{
		$timestamp = wp_next_scheduled( 'kq_check_meeting_hook' );
		wp_unschedule_event( $timestamp, 'kq_check_meeting_hook' );
	}

}

