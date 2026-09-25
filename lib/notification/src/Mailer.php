<?php
declare(strict_types=1);

namespace Monorepo\Notification;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    public function __construct(private MailerInterface $mailer, private string $from) {}

    public function send(string $to, string $subject, string $html): void
    {
        $email = (new Email())->from($this->from)->to($to)->subject($subject)->html($html);
        $this->mailer->send($email);
    }
}
