<?php

namespace App\Controller;

use App\Entity\Brouillon;
use App\Entity\Clients;
use App\Entity\Produit;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrouillonController extends AbstractController
{
    #[Route('/brouillon/create', name: 'app_brouillon')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $brouillon = new Brouillon();

        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        if($request->isXmlHttpRequest() || $request->getContentType() === 'json')
        {
            $data = json_decode($request->getContent(), true);
            $lignevente = end($data);
            array_pop($data);
            $nombre = mt_rand(10, 50);
            if(isset($data)){
                try {
                    $idclient = $lignevente['client'];
                    $tatus = "brouillon";
                   
                    foreach($data as $key => $value)
                    {
                        $brouillon = new Brouillon();

                        $client = $em->getRepository(Clients::class)->find($idclient);
                        $brouillon->setClient($client);
                        $brouillon->setAgence($tempagence->getAgence());
                        $brouillon->setUser($user);
                        $brouillon->setQuatitetotal($lignevente['Qttotal']);
                        $brouillon->setMontanttotal($lignevente['Total']);
                        $brouillon->setCreatetAt(new \DateTime());
                        $brouillon->setFacture($nombre.$client->getTelephone());
                        $produit = $em->getRepository(Produit::class)->findOneBy(["nom" =>$value['produit']]);
                        $brouillon->setProduit($produit);
                        $brouillon->setQuantite($value['quantite']);
                        $brouillon->setPrix($value['prix']);
                        $brouillon->setMontant($value['total']);
                        $brouillon->setStatut($tatus);
                        $em->persist($brouillon);
                    }

                    $em->flush();
                    return $this->json(['status' => true, 'message' => 'Brouillon enregistré avec succès.']);
                } catch (\Throwable $th) {
                   return $this->json([
                        'error' => $th->getMessage(),
                        'status' => false
                        ]
                        , 500);
                }
            }
            
        }

        return $this->redirectToRoute('app_brouillon_list');
    }

    #[Route('/brouillon/list', name: 'app_brouillon_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $tempagence->getAgence();
        
        return $this->render('brouillon/index.html.twig', [
            'brouillons' => $em->getRepository(Brouillon::class)->findByGroupe(),
        ]);
    }

    #[Route('/brouillon/edit/{id}', name: 'app_brouillon_edit')]
    public function edit(EntityManagerInterface $em,Brouillon $brouillon): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $tempagence->getAgence();
        
        return $this->render('brouillon/edit.html.twig', [
            'brouillons' => $em->getRepository(Brouillon::class)->findBy(['facture' => $brouillon->getFacture()]),
            'id' => $brouillon->getId(),
        ]);
    }

    #[Route('/brouillon/delete/{id}', name: 'app_brouillon_delete')]
    public function delete(EntityManagerInterface $em,Brouillon $brouillon): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $tempagence->getAgence();
        if ($brouillon) {
            $em->remove($brouillon);
            $em->flush();
        }
        
        return $this->redirectToRoute('app_brouillon_list');
    }

    #[Route('/brouillon/vente', name: 'app_brouillon_vente')]
    public function vente(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $tempagence->getAgence();
        if($request->isXmlHttpRequest() || $request->getContentType() === 'json')
        {
            $data = json_decode($request->getContent(), true);
            $idbrouillon = $data;
            $lignevente = [];
            $brouillon = $em->getRepository(Brouillon::class)->find($idbrouillon);
            if ($brouillon) {
                $listBrouillon = $em->getRepository(Brouillon::class)->findBy(['facture' => $brouillon->getFacture()]);
                foreach ($listBrouillon as $key => $value) {
                    $lignevente[] = [
                            'client' => $value->getClient()->getId(),
                            'produit' => $value->getProduit()->getNom(),
                            'quantite' => $value->getQuantite(),
                            'prix' => $value->getPrix(),
                            'montant' => $value->getMontant(),
                            'prixtotal' => $value->getMontanttotal(),
                            'quantiteTotal' => $value->getQuatitetotal(),
                    ];
                    $value->setStatut("termine");
                    $em->persist($value); 
                    $em->flush();   
                }
                return $this->json(['status' => true, 'message' => $lignevente]);
            } else {
                return $this->json(['status' => false, 'message' => 'Brouillon non trouvé.'], 404);
            }
        }

        return $this->redirectToRoute('app_brouillon_list');
    }
}
