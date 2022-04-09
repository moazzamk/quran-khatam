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
    public function registration()
    {
        ob_start();
        require_once __DIR__ . '/../views/registration.php';
        return ob_get_clean();
    }

    /**
     * Displays the list of Khatam participants and their recitation status
     *
     * @return false|string
     */
    public function getKhatamStatus()
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
}
