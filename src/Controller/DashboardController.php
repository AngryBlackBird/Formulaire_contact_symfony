<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Message;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository): Response
    {

        return $this->render('dashboard/index.html.twig', [
            'clients' => $clientRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_oneClient')]
    public function oneClient(Client $client): Response
    {
        return $this->render('dashboard/oneClient.html.twig', [
            'client' => $client
        ]);
    }

    #[Route('/message/{id}', name: 'app_dashboard_oneMessage')]
    public function oneMessage(Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($message->getStatus() === Message::STATUS_NOT_OPEN) {
            $message->setStatus(Message::STATUS_OPEN);
            $entityManager->persist($message);
            $entityManager->flush();
        }
        return $this->render('dashboard/oneMessage.html.twig', [
            'message' => $message
        ]);
    }

    #[Route('/message/unread/{id}', name: 'app_dashboard_unread_message')]
    public function unreadMessage(Message $message, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($message->getStatus() === Message::STATUS_OPEN) {
            $message->setStatus(Message::STATUS_NOT_OPEN);
            $entityManager->persist($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_dashboard_oneClient', ["id" => $message->getAuthor()->getId()]);
    }

    #[Route('/message/processed/{id}', name: 'app_dashboard_processed_message')]
    public function processedMessage(Message $message, EntityManagerInterface $entityManager): RedirectResponse
    {
        if ($message->getStatus() === Message::STATUS_OPEN) {
            $message->setStatus(Message::STATUS_PROCESS);
            $entityManager->persist($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_dashboard_oneClient', ["id" => $message->getAuthor()->getId()]);
    }

    #[Route('/download/message', name: 'app_dashboard_download_jsonMessage')]
    public function downloadJsonMessage(): BinaryFileResponse|RedirectResponse
    {

        $zip = new \ZipArchive();
        $finder = new Finder();

        $finder->files()->in($this->getParameter("messages_directory"));
        if ($finder->count() > 0) {
            foreach ($finder as $file) {
                if ($zip->open('messages.zip', \ZipArchive::CREATE) !== true) {
                    throw new FileException('Zip file could not be created/opened.');
                }
                $zip->addFile($file->getRealpath(), basename($file->getRealpath()));
                if (!$zip->close()) {
                    throw new FileException('Zip file could not be closed.');
                }
            }
            $response = new BinaryFileResponse('messages.zip');
            $response->deleteFileAfterSend(true);
        } else {
            $response = $this->redirectToRoute("app_dashboard");
        }


        return $response;
    }

}
