<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Mobilhome;
use App\Form\AddMobilhomeFormType;
use App\Repository\MobilhomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/mobilhome', name: 'app_admin_mobil')]
#[IsGranted('ROLE_USER')]
class AdminMobilhomeController extends AbstractController
{
    #[Route('/delete/{slug}', name: '_deleteProduct')]
    public function deleteMobilhome($slug, MobilhomeRepository $mobilhomeRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mobilhome = $mobilhomeRepository->findOneBy(['slug' => $slug]);
        if (!$mobilhome) {
            throw $this->createNotFoundException('Mobilhome not found');
        }
        $images= $mobilhome->getImages();
        if($images){
            foreach ($images as $image) {

                $imagePath= '/home/mobilhq/www/public' . $image->getFileName();
                if(file_exists($imagePath)){
                    unlink($imagePath);
                }
             
                $entityManager->remove($image);
            }
        }
    
        $entityManager->remove($mobilhome);
       
        $entityManager->flush();
        
        $this->addFlash('success', 'le mobile-home et ses photos ont été supprimées');

        return $this->redirectToRoute('app_admin_index');
    }
    


    #[Route('/add', name: '_addMobil')]
    public function addMobilhome(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mobilhome = new Mobilhome();
        $mobilhomeForm = $this->createForm(AddMobilhomeFormType::class, $mobilhome);
        $mobilhomeForm->handleRequest($request);
        $destination = $this->getParameter('kernel.project_dir') . '/public/build/images/imageUpload';
        $maxImages = 10;
    
        if ($mobilhomeForm->isSubmitted() && $mobilhomeForm->isValid()) {
            $count = $request->get('count', 1);
            if ($count > $maxImages) {
                $this->addFlash("erreur", "nombre d'upload maximum: 10.");
                return $this->redirectToRoute('app_admin_index');
            }
    
            //    upload image
            $this->UploadImage($mobilhomeForm, $mobilhome, $destination, $entityManager, $maxImages);
    
            // Generate slug
            $mobilhome->setSlug(strtolower($mobilhome->getBrand() . '.' . $mobilhome->getModel() . '.' . $mobilhome->getYear()));
            $mobilhome->setBoss($this->getUser());
    
            $entityManager->persist($mobilhome);
            $entityManager->flush();
    
            $this->addFlash('success', 'Mobilhome ajouté avec succès');
    
            return $this->redirectToRoute('app_admin_index');    
        }else{
            $this->addFlash('erreur', 'Une erreur est survenue, veuillez vérifier vos renseignements');
        }
    
        return $this->render('admin/adminMobilhome/addMobilhome.html.twig', [
            'mobilhomeForm' => $mobilhomeForm->createView(),
            'maxImages' => $maxImages,
        ]);
    }

    #[Route('/edit/{slug}', name: '_editProduct')]
    public function editProduct(Request $request, Mobilhome $mobilhome, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $mobilhomeForm = $this->createForm(AddMobilhomeFormType::class, $mobilhome);
        $mobilhomeForm->handleRequest($request);
        $destination = $this->getParameter('kernel.project_dir') . '/public/build/images/imageUpload';
    
        if ($mobilhomeForm->isSubmitted() && $mobilhomeForm->isValid()) {
            // Retrieve existing images
            $existingImages = $mobilhome->getImages();
    
            // Loop through form data to process uploaded images
            for ($i = 1; $i <= 10; $i++) {
                $fieldName = 'image' . $i;
                $imageEntity = $mobilhomeForm[$fieldName]->getData();
    
                // If a new image is uploaded for this position
                if ($imageEntity instanceof UploadedFile && $imageEntity !== null) {
                    // Remove the existing image for this position, if it exists
                    foreach ($existingImages as $existingImage) {
                        if ($existingImage->getPosition() == $i) {
                            $mobilhome->removeImages($existingImage);
                            $entityManager->remove($existingImage);
                        }
                    }
    
                    // Upload and process  image
                    $newImage = new Image;
                    $this->processImage($imageEntity,$destination,$newImage,$i);
                    $newImage->setPosition($i);
                    $mobilhome->addImages($newImage);
                    $entityManager->persist($newImage);
                }
            }
    
            // Generate slug 
            $mobilhome->setSlug(strtolower($mobilhome->getBrand() . '.' . $mobilhome->getModel() . '.' . $mobilhome->getYear()));
    
            $entityManager->flush();
    
            $this->addFlash('succes', 'Le mobile-home a été modifié');
    
            return $this->redirectToRoute('app_admin_index');
        }
    
        return $this->render('admin/adminMobilhome/editProduct.html.twig', [
            'mobilhomeForm' => $mobilhomeForm->createView(),
            'mobilhome' => $mobilhome,
        ]);
    }


 private function UploadImage(FormInterface $form,Mobilhome $mobilhome,string $destination, EntityManagerInterface $entityManager)
{
    for ($i = 1; $i <= 10; $i++) {
        $fieldName = 'image' . $i;
        $imageEntity = $form[$fieldName]->getData();
        $image = new Image();

        if ($imageEntity instanceof UploadedFile and  $imageEntity !== null) {
            $this->processImage($imageEntity, $destination, $image, $i); 
            $mobilhome->addImages($image);

            $entityManager->persist($image);
        }
    }
} 
private function processImage(UploadedFile $imageEntity, string $destination, Image $image, int $position): void
{
    $originalFileName = pathinfo($imageEntity->getClientOriginalName(), PATHINFO_FILENAME);
    $newFileName = Urlizer::urlize($originalFileName) . '_' . md5(uniqid(rand(), true)) . '.webp';

    $picture_infos = getimagesize($imageEntity->getPathname());
    
    // check rotation
    $exif = exif_read_data($imageEntity->getPathname());
    $orientation = $exif['Orientation'] ?? 1;

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
            throw new Exception("Format d'image incorrect");
    }

    // rotation based on format 
    if ($orientation !== 1) {
        $degrees = 0;
        switch ($orientation) {
            case 3:
                $degrees = 180;
                break;
            case 6:
                $degrees = -90;
                break;
            case 8:
                $degrees = 90;
                break;
        }
        $picture_source = imagerotate($picture_source, $degrees, 0);
    }

    // Resize the image while maintaining aspect ratio and high resolution
    $resized_picture = $this->resizeImage($picture_source, 1000, 667);

    // Save the resized image
    imagewebp($resized_picture, $destination . '/' . $newFileName);

    // Set image properties
    $image->setFileName('/build/images/imageUpload/' . $newFileName);
    $image->setPosition($position);
}

private function resizeImage($image, $newWidth, $newHeight)
{
    // Get original dimensions
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Calculate aspect ratio
    $aspectRatio = $originalWidth / $originalHeight;

    // Calculate new dimensions maintaining aspect ratio
    if ($newWidth / $newHeight > $aspectRatio) {
        $newHeight = $newWidth / $aspectRatio;
    } else {
        $newWidth = $newHeight * $aspectRatio;
    }

    // Create a new true color image with the new dimensions
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Resize the image using bicubic interpolation
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

    return $resizedImage;
}

}

    