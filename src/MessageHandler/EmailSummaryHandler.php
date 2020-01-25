<?php

namespace App\MessageHandler;

use App\Message\EmailSummary;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Environment;

class EmailSummaryHandler implements MessageHandlerInterface
{   

    private $mailer;
    private $twig;

    /**
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param EmailSummary $emailSummary
     */
    public function __invoke(EmailSummary $emailSummary)
    {
        $message = (new \Swift_Message('Récapitulatif des promotions sur les produits'))
            ->setFrom('noreply@example.com')
            ->setTo($emailSummary->getAdminEmail())
            ->setBody(
                $this->twig->render(
                    'emails/discount_summary.html.twig',
                    ['products' => $emailSummary->getProducts()]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}