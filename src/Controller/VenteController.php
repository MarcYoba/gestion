<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Entity\Produit;
use App\Form\VenteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteController extends AbstractController
{
    #[Route('/vente/create', name: 'app_vente')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);
        $produit = $entityManager->getRepository(Produit::class)->findAll();

        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $data = json_decode($request->getContent(), true);
            if (isset($data)) {
                    return $this->json(['donne'=>$data], 200);
            }
            // try {
            //     foreach ($data as $key) {
            //         $vente = new Vente();
            //         $date = empty($key['datevalue']) 
            //             ? new \DateTimeImmutable()
            //             : new \DateTimeImmutable($key['datevalue']);
            //         $vente->setCreatedAt($date);
            //         $vente->setPrix($key["prix"]);
            //         $vente->setQuantite($key["quantite"]);
                    
            //         $vente->setUser($this->getUser());
            //         $entityManager->persist($vente);
            //     }
            //     $entityManager->flush();
            //     return $this->json(['success' => true], 200);
            // } catch (\Throwable $th) {
            //     return $this->json(['errors' => $th], 500);
            // }
        }

        return $this->render('vente/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/vente/list', name: 'vente_list')]
    public function list(): Response
    {
        return $this->render('vente/list.html.twig', [
            'controller_name' => 'VenteController',
        ]);
    }

    
}