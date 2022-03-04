<?php

namespace Khatam;

class Setup
{
	public static function activate() 
	{
		global $wpdb;
		self::install($wpdb);
	}

    public static function deactivate()
    {
	    global $wpdb;
	    self::uninstall($wpdb);
    }

    protected static function uninstall($db)
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $tables = [
            "{$db->prefix}khatams",
            "{$db->prefix}khatam_users",
            "{$db->prefix}khatams_users"
        ];

        foreach ($tables as $table) {
            $sql = 'DROP TABLE ' . $table;
            $db->query($sql);
            error_log($sql);
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
				juz_num TINYINT
			) $collate;
SQL;

		dbDelta($schema);
		add_option('khatam_db_version', '1');

	}
}

