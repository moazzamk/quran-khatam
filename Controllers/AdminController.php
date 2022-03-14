<?php

namespace Khatam\Controllers;

use Khatam\Repositories\KhatamRepository;

class AdminController
{
    private KhatamRepository $khatamRepository;

    public function __construct(KhatamRepository $khatamRepository)
    {
        $this->khatamRepository = $khatamRepository;
    }

    public function dashboard()
    {
        $currentKhatam = $this->khatamRepository->getCurrentKhatam();
        require __DIR__ . '/../views/khatam-dashboard.php';
    }

    public function add()
    {
        require __DIR__ . '/../views/khatam-add.php';
    }

    public function save($data)
    {
        if (!empty($data['khatam-add-nonce']) && wp_verify_nonce($data['khatam-add-nonce'])) {
            $this->save([
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'meeting_link' => $data['meeting_link'],
                'meeting_ts' => $data['meeting_ts']
            ]);
        }
        else {
            print json_encode([
                'success' => false,
            ]);
        }
    }
}
