<?php

namespace Khatam\Repositories;

class KhatamUsersRepository
{
    private \wpdb $db;

    public function __construct(\wpdb $db)
    {
        $this->db = $db;
    }

    public function insert($name, $email, $khatamId=0, $juz=0)
    {
        $this->db->insert(
            $this->db->prefix . 'khatam_users',
            [
                'email' => $email,
                'name' => $name,
            ]
        );

        if ($khatamId > 0) {
            $rs = $this->db->insert(
                $this->db->prefix . 'khatams_users',
                [
                    'khatam_id' => $khatamId,
                    'user_email' => $email,
                    'juz_num' => $juz
                ]
            );

            if ($rs === false) {
                return $rs;
            }
        }

        return $rs;
    }

}