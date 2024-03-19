<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em, ContactRepository $contactRepository, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $contact->setBuyer(false);
        $contact->setSeller(false);
        $contact->setJustWantInfo(true);
        $budget = null;

        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);
        
        
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $email = $contactForm->get('email')->getData();
            $existingContact = $contactRepository->findOneBy(['email' => $email]);
            
            if ($existingContact) {
                $contact= $existingContact;
                $contact->setMessage($contactForm->get('message')->getData());
                $contact->setJustWantInfo(true);
            } else {
            
                $em->persist($contact);
            }
    
            $em->flush();
          

            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
            return $this->redirectToRoute('app_contact');
        
        }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()){
                $this->addFlash('error', 'Une erreur est survenue, veuillez vérifier vos renseignements: le tel est il complet? le nom contient il des chiffres? ...');
               
            }
            
        

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView(),
            'contact' => $contact,
            'budget' => $budget,
        ]);
    }
}
