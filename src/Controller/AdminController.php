<?php

namespace App\Controller;

use App\Entity\YouTubeFrame;
use App\Repository\MobilhomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin')]
#[IsGranted('ROLE_USER')]
class AdminController extends AbstractController
{
   
    #[Route('/', name: '_index')]
    public function index2(MobilhomeRepository $mobilhomeRepository): Response
    {
        $this->denyAccessUnlessGranted(attribute: 'IS_AUTHENTICATED_FULLY');
        $mobilhome = $mobilhomeRepository->findBy([], ['created_at' => 'desc']);
        return $this->render('admin/index.html.twig', [
            'mobilhomes' => $mobilhome,
        ]);
    }

    #[Route('/select-youtube-link', name: '_select_youtube_link')]
    public function selectYouTubeLink(Request $request, MobilhomeRepository $mobilhomeRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Retrieve the mobile home slug from the request parameters
        $slug = $request->query->get('slug');
    
        // Fetch the mobile home entity using the slug
        $mobilhome = $mobilhomeRepository->findOneBy(['slug' => $slug]);
    
        // If there is no mobile home entity, redirect with a message
        if (!$mobilhome) {
            return $this->redirectToRoute('home', [
                'message' => 'Visitez notre chaîne YouTube ici : https://www.youtube.com/channel/UCM7GAmYBWfHVnlIxzfKpWrQ'
            ]);
        }
    
        $youTubeLink = $mobilhome->getYouTubeLink();
        // Clean and format the YouTube link
        $youTubeLinkCleaned = $this->cleanAndFormatYouTubeLink($youTubeLink);
    
        $youTubeFrame = $entityManager->getRepository(YouTubeFrame::class)->findOneBy([]);
    
        // If a YouTubeFrame entity exists, update its URL; otherwise, create a new one
        if ($youTubeFrame) {
            $youTubeFrame->setUrl($youTubeLinkCleaned);
        } else {
            $youTubeFrame = new YouTubeFrame();
            $youTubeFrame->setUrl($youTubeLinkCleaned);
            $entityManager->persist($youTubeFrame);
        }
    
        
        $entityManager->flush();
    
        return $this->redirectToRoute('home');
    }
    
    //   clean and format YouTube link
    private function cleanAndFormatYouTubeLink(string $youTubeLink): string
    {
        // Remove "/shorts/" from the YouTube link
        $cleanedUrl = str_replace('/shorts/', '/embed/', $youTubeLink);
        
        // Makes sure "www." is included in the URL
        if (strpos($cleanedUrl, 'www.') === false) {
            $cleanedUrl = 'https://www.' . ltrim($cleanedUrl, 'https://');
        }
    
        return $cleanedUrl;
    }
    

}
