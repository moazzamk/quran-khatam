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
        $khatamStats = null;
        $currentKhatam = $this->khatamRepository->getCurrentKhatam();
        $futureKhatams = $this->khatamRepository->getFutureKhatams() ?? [];
        if (!empty($currentKhatam)) {
            $khatamStats = $this->khatamRepository->getKhatamStats($currentKhatam->id);
        }

        require_once __DIR__ . '/../views/khatam-dashboard.php';
    }

    public function delete($data)
    {
        if (!current_user_can('edit_users')) {
            header('Location: admin.php?page=khatams-dashboard');
            return;
        }

        $this->khatamRepository->delete($data['id']);
        header('Location: admin.php?page=khatams-dashboard');
    }

    public function add()
    {
        $khatam = new \StdClass;
        if (!empty($_REQUEST['id'])) {
            $khatam = $this->khatamRepository->getById($_REQUEST['id']);
        }
        $nonce = wp_create_nonce( 'khatam-save-response' );
        require __DIR__ . '/../views/khatam-add.php';
    }

    public function save($data)
    {
        if (!current_user_can('edit_users')) {
            header('Location: admin.php?page=khatams-dashboard');
            return;
        }

        if (!empty($data['khatam-save-response']) && wp_verify_nonce($data['khatam-save-response'], 'khatam-save-response')) {
            $khatam = [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'meeting_link' => $data['meeting_link'],
                'meeting_ts' => $data['meeting_ts']
            ];

            if (empty($_REQUEST['id'])) {
                $rs = $this->khatamRepository->insert($khatam);
            }
            else {
                $rs = $this->khatamRepository->update($khatam, (int)$_REQUEST['id']);
            }

            header('Location: admin.php?page=khatams-dashboard');
        }
        else {
            print 'You done goofed';
        }
    }
}
