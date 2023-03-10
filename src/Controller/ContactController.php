<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Message;
use App\Form\ContactType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $form = $this->createForm(ContactType::class);
        return $this->render('home/home.html.twig', [

            'controller_name' => 'Home',
        ]);
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $clientRepository->findOneBy(array('email' => $form->getData()['email']));
            if (!$author) {
                $author = new Client();
                $author->setEmail($form->getData()['email']);
                $author->setName($form->getData()['name']);
            }
            $message = new Message();
            $message->setContent($form->getData()['content']);
            $message->setDatetime(new \DateTime());
            $message->setStatus(Message::STATUS_NOT_OPEN);

            $author->addMessage($message);

            $entityManager->persist($message);
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash("success", "Votre message a bien été envoyé, il sera traité dans les plus brefs délais.");
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ContactController',
        ]);
    }
}
