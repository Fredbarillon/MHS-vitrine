<?php
namespace App\Controller;

use App\Entity\YouTubeFrame;
use App\Repository\MobilhomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(MobilhomeRepository $mobilhomeRepository, EntityManagerInterface $entityManager): Response
    {
        $youTubeFrameRepository = $entityManager->getRepository(YouTubeFrame::class);
        $youTubeFrame = $youTubeFrameRepository->findOneBy([], ['id' => 'DESC']);
        $recentMobilhomes = $mobilhomeRepository->findRecent(6);
        $youTubeLink = $youTubeFrame ? $youTubeFrame->getUrl() : null;

        foreach ($recentMobilhomes as $recentMobilhome) {
           
            $image = $recentMobilhome->getImages()->filter(function ($image) {
                return $image->getPosition() == 1;
            })->first();

            $mobilhomeData[] = [
                'mobilhome' => $recentMobilhome,
                'image' => $image,
            ];
            
        }

        return $this->render('home/index.html.twig', [
            'mobilhomeData' => $mobilhomeData,
            'youTubeLink' => $youTubeLink,
        ]);
    } 
    
    #[Route('/CGU', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('cgu.html.twig');
    } 
}
