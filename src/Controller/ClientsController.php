<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientsController extends AbstractController
{
    #[Route('/clients/create', name: 'app_clients')]
    public function index(): Response
    {
        return $this->render('clients/index.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }

    #[Route('/clients/list', name: 'clients_list')]
    public function list(): Response
    {
        return $this->render('clients/list.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }

    #[Route('/clients/edit', name: 'clients_edit')]
    public function edit(): Response
    {
        return $this->render('clients/edit.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }
    #[Route('/clients/delete', name: 'clients_delete')]
    public function delete(): Response
    {
        return $this->render('clients/delete.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }
    #[Route('/clients/recherche', name: 'clients_recherche')]
    public function view(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees)) {
                $client = $entityManager->getRepository(Clients::class)->findBy(['nom' => $donnees]);
                if ($client) {
                    return $this->json([
                        'success' => true,
                        'nom' => $client[0]->getId(), 
                    ]);
                } else {
                    return $this->json(['error' => 'Client non trouvé','donne'=>$donnees], 200);
                }
            }

        }
        return $this->json(['error' => 'Client non spécifié'], 404);
    }
}
