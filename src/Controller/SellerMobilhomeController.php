<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Image;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class SellerMobilhomeController extends AbstractController
{
    #[Route('/seller', name: 'app_seller')]
    public function index(Request $request,EntityManagerInterface $em, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $contact->setBuyer(false);
        $contact->setSeller(true);
        $contact->setJustWantInfo(false);

        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $contactForm->handleRequest($request);
        $destination = $this->getParameter('kernel.project_dir') . '/assets/images';
        $maxImages = 4;
        
        if ($contactForm->isSubmitted() and $contactForm->isValid()) {
            $count = $request->get('count', 1);
            if ($count > $maxImages) {
                $this->addFlash("erreurr", "nombre d'upload maximum: 4.");
                return $this->redirectToRoute('app_seller');      
            }
            //    upload image
                $this->UploadImage($contactForm, $contact, $destination, $em, $maxImages);

                $email = $contactForm->get('email')->getData();
                $existingContact = $contactRepository->findOneBy(['email' => $email]);
    
                if ($existingContact) {
                    $contact = $existingContact;
                    $contact->setMessage($contactForm->get('message')->getData());
                    $contact->setSeller(true);
                }else{
                    $em->persist($contact);
                }
             
                $em->flush();
    
                $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    
                return $this->redirectToRoute('app_seller');
                
            }elseif ($contactForm->isSubmitted() && !$contactForm->isValid()){
                $this->addFlash('error', 'Une erreur est survenue, veuillez vérifier vos renseignements: le tel est il complet? le nom contient il des chiffres? ...');
                
            }

        return $this->render('seller_mobilhome/index.html.twig', [
            'contactForm' => $contactForm->createView(),
            'contact' => $contact,
            'maxImages' => $maxImages,
        ]);
    }

    private function UploadImage(FormInterface $form,Contact $contact,string $destination, EntityManagerInterface $entityManager)
    {
        for ($i = 1; $i <= 4; $i++) {
            $fieldName = 'image' . $i;
            $imageEntity = $form[$fieldName]->getData();
            $image = new Image();
    
            if ($imageEntity instanceof UploadedFile and  $imageEntity !== null) {
                $this->processImage($imageEntity, $destination, $image, $i); 
                $contact->addImage($image);
    
                $entityManager->persist($image);
            }
        }
    } 
        private function processImage(UploadedFile $imageEntity, string $destination, Image $image, int $position): void
        {
            $originalFileName = pathinfo($imageEntity->getClientOriginalName(), PATHINFO_FILENAME);
            $newFileName = Urlizer::urlize($originalFileName) . '_' . md5(uniqid(rand(), true)) . '.webp';
    
            $picture_infos = getimagesize($imageEntity->getPathname());
    
            if ($picture_infos === false) {
                throw new Exception('Incorrect image format');
            }
    
            // Create the source image based on the MIME type
            switch ($picture_infos['mime']) {
                case 'image/png':
                    $picture_source = imagecreatefrompng($imageEntity->getPathname());
                    break;
                case 'image/jpeg':
                    $picture_source = imagecreatefromjpeg($imageEntity->getPathname());
                    break;
                case 'image/webp':
                    $picture_source = imagecreatefromwebp($imageEntity->getPathname());
                    break;
                default:
                    throw new Exception('Incorrect image format');
            }
    
            // Crop the image
            $imageWidth = $picture_infos[0];
            $imageHeight = $picture_infos[1];
            $squareSize = min($imageWidth, $imageHeight);
            $src_x = ($imageWidth - $squareSize) / 2;
            $src_y = ($imageHeight - $squareSize) / 2;
    
            // Create a new blank image
            $resized_picture = imagecreatetruecolor(279, 186);
    
            // Copy the cropped image to the new image
            imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, 279, 186, $squareSize, $squareSize);
    
            // Save the resized image
            imagewebp($resized_picture, $destination . '/' . $newFileName);
    
            // Set image properties
            $image->setFileName('/images' . '/' . $newFileName);
            $image->setPosition($position);
        }
}
