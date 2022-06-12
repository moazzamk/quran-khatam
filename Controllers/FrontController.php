<?php

namespace Khatam\Controllers;

use Khatam\Repositories\KhatamRepository;
use Khatam\Repositories\KhatamUsersRepository;

class FrontController
{
    private KhatamUsersRepository $khatamUserRepo;
    private KhatamRepository $khatamRepo;

    public function __construct(
        KhatamUsersRepository $khatamUserRepo,
        KhatamRepository $khatamRepo
    ) {
        $this->khatamUserRepo = $khatamUserRepo;
        $this->khatamRepo = $khatamRepo;
    }

    /**
     * Displays registration/completion form
     *
     * @return false|string
     */
    public function registration(): bool|string
    {
        wp_enqueue_script('custom-script', KHATAM_URL . '/public/registration.js');
        wp_localize_script(
            'custom-script',
            'params',
            [ 'url' => admin_url( 'admin-ajax.php' ) ]
        );

        error_log(json_encode([ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ]));

        ob_start();
        require_once __DIR__ . '/../views/registration.php';
        return ob_get_clean();
    }

    /**
     * Displays the list of Khatam participants and their recitation status
     *
     * @return false|string
     */
    public function getKhatamStatus(): bool|string
    {
        $khatamStats = [];
        $khatam = $this->khatamRepo->getCurrentKhatam();
        if (!empty($khatam->id)) {
            $khatamStats = $this->khatamRepo->getKhatamUserList($khatam->id);
        }

        ob_start();
        require_once __DIR__ . '/../views/khatam-stats.php';
        return ob_get_clean();
    }

    /**
     * Handles user registration for a khatam.
     * Returns JSON response to the client
     *
     * @param array $data
     * @return void
     */
    public function saveRegistration(array $data)
    {
        $khatam = $this->khatamRepo->getCurrentKhatam();
        $users = $this->khatamRepo->getKhatamUserList($khatam->id);
        if (count($users) == 30) {
            // There are no empty slots
        }

        $juz = count($users);
        $rs = $this->khatamUserRepo->insert(
            $data['name'],
            $data['email'],
            $khatam->id,
            $juz
        );

        if ($rs === false) {
            wp_die(json_encode([
                'success' => false
            ]));
        }

        wp_die(json_encode([
            'success' => true,
            'juz' => $juz
        ]));
    }
}

