<?php


namespace App\Manager;


use App\Entity\User;
use Twig\Environment;

class MailerManager
{
    private $mailer;
    private $twig;

    /**
     * MailerManager constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param \Swift_Message $message
     */
    private function send(\Swift_Message $message) {
        $this->mailer->send($message);
    }

    /**
     * @param User $user
     * @param string $token
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function registrationMessage(User $user, string $token) {
        $this->mailer->send((new \Swift_Message('Confirmation du compte'))
            ->setFrom($user->mail)
            ->setReplyTo($user->mail)
            ->setTo($user->mail)
            ->setBody(
                $this->twig->render(
                    'emails/registration.html.twig',
                    ['name' => $user->fullName,
                        'token' => $token]
                ),
                'text/html'
            ));
    }

    /**
     * @param User $user
     * @param string $token
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function forgetPasswordMessage(User $user, string $token) {
        $this->mailer->send((new \Swift_Message('Renouvellement du mot de passe'))
            ->setFrom($user->mail)
            ->setReplyTo($user->mail)
            ->setTo($user->mail)
            ->setBody(
                $this->twig->render(
                    'emails/forget.html.twig',
                    ['name' => $user->fullName,
                        'token' => $token]
                ),
                'text/html'
            ));
    }
}
