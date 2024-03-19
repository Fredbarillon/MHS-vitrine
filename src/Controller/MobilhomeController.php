<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Mobilhome;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use App\Repository\MobilhomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mobilhome', name: 'app_mobilhome')]
class MobilhomeController extends AbstractController
{
    #[Route('/', name: '_occasion')]
    public function index(Request $request, MobilhomeRepository $mobilhomeRepository, EntityManagerInterface $entityManager, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
         
        $contact->setBuyer(false);
        $contact->setSeller(false);
        $contact->setJustWantInfo(true);

        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() and $contactForm->isValid()) {
    
                $email = $contactForm->get('email')->getData();
                $existingContact = $contactRepository->findOneBy(['email' => $email]);
    
                if ($existingContact) {
                    $contact = $existingContact;
                    $contact->setMessage($contactForm->get('message')->getData());
                    $contact->setJustWantInfo(true);
                }else{
                    $entityManager->persist($contact);
                }
             
                $entityManager->flush();
    
                $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    
               
                return $this->redirectToRoute('app_mobilhome_occasion');
                
            }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()){
                $this->addFlash('error', 'Une erreur est survenue, veuillez vérifier vos renseignements: le tel est il complet? le nom contient il des chiffres? ...');
                
            }

        $mobilhomes = $mobilhomeRepository->findBy([], ['price' => 'DESC']);
        foreach ($mobilhomes as $mobilhome) {
           
            $image = $mobilhome->getImages()->filter(function ($image) {
                return $image->getPosition() == 1;
            })->first();

            $mobilhomeData[] = [
                'mobilhome' => $mobilhome,
                'image' => $image,
            ];
            
        }
        
        return $this->render('mobilhome/index.html.twig', [
            'mobilhomeData' => $mobilhomeData,
            'contactForm' => $contactForm->createView(),
        ]);
    }


    #[Route('/{slug}', name:'_productDetails')]

    public function productDetails(Mobilhome $mobilhome, Request $request, EntityManagerInterface $em, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $contact->setBuyer(true);
        $contact->setSeller(false);
        $contact->setJustWantInfo(false);

       
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $email = $contactForm->get('email')->getData();
            $existingContact = $contactRepository->findOneBy(['email' => $email]);


            if ($existingContact) {
                $contact = $existingContact;
                $contact->setMessage($contactForm->get('message')->getData());
                $contact->setBuyer(true);
            }else{
                if (!$contact->getContactMobilhome()->contains($mobilhome)) {
                    $contact->addContactMobilhome($mobilhome);
                }
                
                $em->persist($contact);
            }
      
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
            
           
            return $this->redirectToRoute('app_mobilhome_productDetails', ['slug' => $mobilhome->getSlug()]);

        }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()){
            $this->addFlash('error', 'Une erreur est survenue, veuillez vérifier vos renseignements: le tel est il complet? le nom contient il des chiffres? ...');
            return $this->redirectToRoute('app_contact');
        }

        $image = $mobilhome->getImages()->filter(function ($image) {
            return $image->getPosition() == 1;
        })->first();
        

        $mobilhomeData[] = [
            'mobilhome' => $mobilhome,
            'image' => $image,
        ];
      

        return $this->render('mobilhome/productDetails.html.twig',[
            'contactForm'=> $contactForm,
            'mobilhome'=> $mobilhome,
            'mobilhomeData' => $mobilhomeData,
        ]);
    }
    
}

