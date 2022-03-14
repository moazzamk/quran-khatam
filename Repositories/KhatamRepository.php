<?php

namespace Khatam\Repositories;

class KhatamRepository {
    private \wpdb $db;


    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add($khatam)
    {
        return $this->db->insert(
            $this->db->prefix . khatams,
            $khatam
        );
    }

    /**
     * @return array|object|void|null
     */
    public function getCurrentKhatam()
    {
        $sql = <<<SQL
            SELECT 
                   * 
            FROM 
            WHERE start_date <= CURDATE()
                AND end_date >= CURDATE()
        SQL;

        return $this->db->get_row($sql);
    }
}
