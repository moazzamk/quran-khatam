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

        $currentKhatamUsers = $this->khatamRepo->getKhatamStats($currentKhatam->id);
        if (!$currentKhatamUsers) {
            return;
        }
        
        // mailchimp credentials:
        // username: qurankhatam1@gmail.com
        // password: !123456Abcdef
    
        try {
            $this->mailer->addAddress('qurankhatam1@gmail.com');
        
            //Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Testing';
            $this->mailer->Body = $currentKhatam->meetingLink;
            $this->mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
            print 'hiiiii';

            $this->mailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }
    }

    public function __invoke()
    {
        $this->run();
    }
}