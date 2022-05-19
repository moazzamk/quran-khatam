<?php

namespace Khatam\Commands;

use  \Khatam\Repositories\KhatamRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Checks the date / time for current khatam's meeting time and sends reminders
 * to participants
 */
class MeetingReminderCommand
{
    private KhatamRepository $khatamRepo;
    private PHPMailer $mailer;


    public function __construct(
        KhatamRepository $khatamRepo,
        PHPMailer $mailer
    ) {
        $this->khatamRepo = $khatamRepo;
        $this->mailer = $mailer;
    }

    public function run()
    {
        $currentKhatam = $this->khatamRepo->getCurrentKhatam();
        if (!$currentKhatam) {
            return;
        }

        $currentKhatamUsers = $this->khatamRepo->getKhatamUserList($currentKhatam->id);
        if (!$currentKhatamUsers) {
            return;
        }
 
        if (date("Y-m-d") >= date($currentKhatam->endDate, strtotime("-2 days"))) {
            try {
                $this->mailer->addAddress('qurankhatam1@gmail.com');
            
                //Content
                $this->mailer->isHTML(true);
                $this->mailer->Subject = 'Testing';
                $this->mailer->Body = "HIIIII";
                $this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
                $this->mailer->send();
            } catch (Exception $e) {
                error_log("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}", 1, "some@email.com");
            }
        }
    }

    public function __invoke()
    {
        $this->run();
    }
}
