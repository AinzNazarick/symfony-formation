<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDTO();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to($contact->getService())
                ->subject('Demande de contact')
                ->htmlTemplate('emails/contact.html.twig')
                ->context(['contact' => $contact]);
                $mailer->send($email);
                $this->addFlash('success', 'Email envoyé');
                return $this->redirectToRoute('contact');
            } catch (\Exception $e){
                $this->addFlash('danger', 'Impossible d\'envoyer votre email');
            }
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
