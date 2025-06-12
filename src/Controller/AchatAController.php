<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Form\AchatAType;
use App\Entity\FournisseurA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AchatAController extends AbstractController
{
    #[Route('/achat/a/create', name: 'app_achat_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $achatA = new AchatA();
        
        if ($request->isXmlHttpRequest() || $request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
            
            try {
                foreach ($data as $key) {
                    $achatA = new AchatA();
                    $date = empty($key['datevalue']) ? new \DateTimeImmutable() : new \DateTimeImmutable($key['datevalue']);
                    $achatA->setCreatedAt($date);
    
                    $fournisseurA = $em->getReference(FournisseurA::class, $key['fournisseur']);
                    $idproduit = $em->getRepository(ProduitA::class)->findBy(['nom' => $key['produit']]);
                    $produitA = $em->getReference(ProduitA::class, $idproduit[0]->getId());
    
                    $ajout = $produitA->getQuantite();
    
                    $achatA->setPrix($key["prix"]);
                    $achatA->setQuantite($key["quantite"]);
                    $achatA->setMontant($key["total"]);
                    $achatA->setUser($this->getUser());
                    $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
                    $achatA->setAgence($tempagence->getAgence());
                    $achatA->setForunisseur($fournisseurA);
                    $achatA->setProduit($produitA);
    
                    $ajout += $key["quantite"];
                    $produitA->setQuantite($ajout);
    
                    $em->persist($achatA);
                }
                $em->flush();
                $data = [
                    'success' => 200,
                    'message' => 'Achat enregistré avec succès',
                ];
            return $this->json($data);
            } catch (\Throwable $th) {
                $data = [
                    'success' => 500,
                    'message' => 'Erreur lors de l\'enregistrement de l\'achat',
                    'error' => $th->getMessage(),
                ];
                return $this->json($data);
            }
            
        }
        $fournisseurA = $em->getRepository(FournisseurA::class)->findAll();
        $produitA = $em->getRepository(ProduitA::class)->findAll();
        return $this->render('achat_a/index.html.twig', [
            'produit' => $produitA,
            'fournisseur' => $fournisseurA,
        ]);
    }

    #[Route('/achat/a/list', name: 'achat_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();
        $achatA = $em->getRepository(AchatA::class)->findAll(["agence" => $id]);
        return $this->render('achat_a/list.html.twig', [
            'achats' => $achatA,
        ]);
    }
}
