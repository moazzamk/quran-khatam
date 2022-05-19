<?php

namespace Khatam\Controllers;

use Khatam\Repositories\KhatamRepository;

class FrontController
{
    private KhatamRepository $khatamRepo;

    public function __construct(KhatamRepository $khatamRepo)
    {
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

    public function saveRegistration(array $data): string
    {

        print ' i was called';

        error_log('saving registration');

        return 'hiooo';
    }
}
