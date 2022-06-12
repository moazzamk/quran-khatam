<?php

namespace Khatam\Controllers;

use JetBrains\PhpStorm\ArrayShape;
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
     * Handles user form submission on the site.
     * Returns JSON response to the client
     *
     * @param array $data
     * @return void
     */
    public function saveRegistration(array $data)
    {
        // Make sure action exists
        if (empty($data['khatam-action'])) {
            wp_die(json_encode([
                'success' => false,
                'message' => 'You need to select an action'
            ]));
        }

        $rs = match ($data['khatam-action']) {
            'register' => $this->processRegsitration($data),
            'finished' => $this->processCompletion($data),
            default => json_encode([
                'success' => false,
                'message' => 'Invalid action provided'
            ]),
        };

        wp_die(json_encode($rs, JSON_PRETTY_PRINT));
    }

    private function processRegsitration(array $data) : array
    {
        error_log(print_r($data, 1));
        $khatam = $this->khatamRepo->getCurrentKhatam();
        $users = $this->khatamRepo->getKhatamUserList($khatam->id);
        if (count($users) == 30) {

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

    #[ArrayShape(['success' => "bool"])]
    private function processCompletion(array $data) : array
    {
        $khatam = $this->khatamRepo->getCurrentKhatam();
        $this->khatamUserRepo->updateStatus($data['email'], $khatam->id, 1);

        return [
            'success' => true
        ];
    }
}

