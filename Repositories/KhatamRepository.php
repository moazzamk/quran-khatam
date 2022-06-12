<?php

namespace Khatam\Repositories;

class KhatamRepository {
    private \wpdb $db;


    public function __construct(\wpdb $db)
    {
        $this->db = $db;
    }

    public function insert(array $khatam) : int|false
    {
        return $this->db->insert(
            $this->db->prefix . 'khatams',
            $khatam
        );
    }

    public function update(array $khatam, int $id)
    {
        return $this->db->update(
            $this->db->prefix . 'khatams',
            $khatam,
            [ 'id' => $id]);
    }

    public function delete(int $id) : int|false
    {
        return $this->db->delete(
            $this->db->prefix . 'khatams',
            [ 'id' => $id]
        );
    }

    public function getById(int $id)
    {
        $sql = <<<SQL
            SELECT
                id,
                start_date AS startDate,
                end_date AS endDate,
                meeting_link as meetingLink,
                meeting_ts as meetingTs
            FROM {$this->db->prefix}khatams
            WHERE id = %d
        SQL;
        $stmt = $this->db->prepare($sql, $id);

        return $this->db->get_row($stmt);
    }

    public function getFutureKhatams()
    {
        $sql = <<<SQL
            SELECT
                id,
                start_date AS startDate,
                end_date AS endDate,
                meeting_link as meetingLink,
                meeting_ts as meetingTs
            FROM {$this->db->prefix}khatams
            WHERE start_date > CURDATE()
            ORDER BY start_date DESC
        SQL;
        return $this->db->get_results($sql);
    }

    /**
     * Gets a list of users and their details regarding current khatam
     *
     * @param int $khatamId
     * @return array|object|\stdClass[]|null
     */
    public function getKhatamUserList($khatamId)
    {
        $sql = <<<SQL
            SELECT
                ksu.user_email AS email,
                ksu.status AS status,
                ksu.juz_num AS juz,
                ku.name AS name
            FROM {$this->db->prefix}khatams_users AS ksu
            INNER jOIN {$this->db->prefix}khatam_users AS ku ON (ku.email=ksu.user_email)
            WHERE ksu.khatam_id= %d
        SQL;

        $stmt = $this->db->prepare($sql, $khatamId);

        return $this->db->get_results($stmt);
    }

    /**
     * Get a khatam's stats
     *
     * @param int $id  Khatam ID
     *
     * @return object|void|null
     */
    public function getKhatamStats(int $id)
    {
        $sql = <<<SQL
            SELECT 
                status,
                count
            FROM {$this->db->prefix}khatams_users
            GROUP BY status
            WHERE
                khatam_id={$id}
        SQL;
        return $this->db->get_results($sql);
    }

    /**
     * @return array|object|null
     */
    public function getCurrentKhatam()
    {
        $sql = <<<SQL
            SELECT
                id,
                start_date AS startDate,
                end_date AS endDate,
                meeting_link as meetingLink,
                meeting_ts as meetingTs
            FROM {$this->db->prefix}khatams
            WHERE start_date <= CURDATE()
                AND end_date >= CURDATE()
        SQL;

        return $this->db->get_row($sql);
    }
}
