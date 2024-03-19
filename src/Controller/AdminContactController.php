<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AdminContactController extends AbstractController
{
    #[Route('/admin/contacts', name: 'admin_contacts')]
    public function index(ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $contacts = $contactRepository->findBy([], ['created_at' => 'desc']);

        return $this->render('admin/admin_contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_contact')]
    public function deleteMobilhome($id, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $contact = $contactRepository->findOneBy(['id' => $id]);
        if (!$contact) {
            throw $this->createNotFoundException('contact not found');
        }
    
        // Remove the Mobilhome entity from the database
        $entityManager->remove($contact);
    
        // Flush the changes to the database
        $entityManager->flush();
    
        $this->addFlash('success', 'Le contact et son méssage ont été supprimées');
    
        return $this->redirectToRoute('admin_contacts');
    }

    #[Route('/admin/contact/{id}', name: 'show_contact')]
public function showContact($id, ContactRepository $contactRepository): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $contact = $contactRepository->find($id);

    if (!$contact) {
        throw $this->createNotFoundException('Contact non trouvé');
    }

    return $this->render('admin/admin_contact/show.html.twig', [
        'contact' => $contact,
    ]);
}
}
