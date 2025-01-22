<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class PageController extends AbstractController
{
    #[Route('', name: 'app_page_principale')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_page');
    }

    #[Route('/home', name: 'app_page')]
    public function index2(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/CV', name: 'app_cv')]
    public function cv(): Response
    {
        return $this->render('page/CV.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
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
            error_log($errorMessage);
        }

        return $this->redirectToRoute('app_contact');
    }

    #[Route('/telechargement', name: 'app_telechargement')]
    public function telechargement(): Response
    {
        return $this->render('page/telechargement.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }


    #[Route('/en_savoir_plus', name: 'app_en_savoir_plus')]
    public function en_savoir_plus(): Response
    {
        return $this->render('page/en_savoir_plus.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }



    #[Route('/presentation_competences', name: 'app_presentation_competences')]
    public function presentation_competences(): Response
    {
        return $this->render('page/presentation_competences.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }





    #[Route('/download-cv', name: 'download_cv', methods: ['POST'])]
    public function downloadCV(Request $request, MailerInterface $mailer): Response
    {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $email = $request->request->get('email');
        
        // Validation des champs
        if (!$firstname || !$lastname || !$email) {
            $this->addFlash('error', 'Tous les champs sont requis');
            return $this->redirectToRoute('app_telechargement');
        }
    
        // Télécharger le CV
        try {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/CV.pdf';
            
            if (!file_exists($filePath)) {
                throw new \Exception('Le fichier PDF n\'existe pas');
            }
    
            // Crée la réponse pour télécharger le fichier
            $response = new BinaryFileResponse($filePath);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'CV_Pierre_HILTENBRAND.pdf'
            );
            // Retourner la réponse de téléchargement du fichier
            return $response;

            // Si le téléchargement a réussi, envoyer l'email
            $date = new \DateTime();
            $formattedDate = $date->format('d F Y');
            $emailBody = sprintf(
                "Monsieur/Madame %s %s a téléchargé votre CV le %s",
                $firstname,
                $lastname,
                $formattedDate
            );
    
            // Création de l'email
            $emailNotification = (new Email())
                ->from('votre-email@domaine.com')
                ->to('pierre.hiltenbrand.burianne@etu.univ-st-etienne.fr')
                ->subject('Nouveau téléchargement de CV')
                ->text($emailBody);
    
            // Envoi de l'email
            $mailer->send($emailNotification);
    
            
    
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue: ' . $e->getMessage());
            return $this->redirectToRoute('app_telechargement');
        }
    }


}