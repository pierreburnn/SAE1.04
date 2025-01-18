<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('page/contact.html.twig');
    }

    #[Route('/send-email', name: 'send_email', methods: ['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        $userEmail = $request->request->get('email');
        $message = $request->request->get('message');

        try {
            $email = (new Email())
                ->from('contact.pierre.hiltenbrand@gmail.com')
                ->to('pierre.hiltenbrand.burianne@etu.univ-st-etienne.fr')
                ->replyTo($userEmail)
                ->subject('Nouveau message du formulaire de contact')
                ->html("<p>Message de : {$userEmail}</p><p>{$message}</p>");

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->addFlash('error', 'Erreur détaillée : ' . $errorMessage);
            // En développement, on peut logger l'erreur complète
            error_log($errorMessage);
        }

        return $this->redirectToRoute('app_contact');
    }
}
