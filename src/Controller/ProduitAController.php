<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Form\ProduitAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitAController extends AbstractController
{
    #[Route('/produit/a/create', name: 'app_produit_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $produitA = new ProduitA();
        $form = $this->createForm(ProduitAType::class, $produitA);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $produitA->setUser($this->getUser());
            $produitA->setGain(0);
            $produitA->setStockdebut($produitA->getQuantite());

            $employer = new Employer();
            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            if ($tempagence->getAgence()) {
               $employer = $tempagence->getAgence();
            }else{
                $this->addFlash('error', 'Agence introuvable pour cet utilisateur vous ne pouvez enregistrer de produit.');
                return $this->redirectToRoute("produit_list");
            }
            $produitA->setAgence($employer);
            $em->persist($produitA);
            $em->flush();

            return $this->redirectToRoute('produit_a_list');
        }
        return $this->render('produit_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/a/list', name: 'produit_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produits = $em->getRepository(ProduitA::class)->findAll(["agence" => $id]);
        return $this->render('produit_a/list.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/a/edit/{id}', name: 'produit_a_edit')]
    public function edite(Request $request, EntityManagerInterface $em,int $id): Response
    {

        $produits = $em->getRepository(ProduitA::class)->find($id);
        if (!$produits) {
            $this->addFlash('error','Le produit que vous recherche n\'existe pas');
        }
        $form = $this->createForm(ProduitAType::class,$produits);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('produit_a_list');
        }

        return $this->render('produit_a/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produits,
        ]);
    }

    #[Route('/produit/a/delete/{id}', name: 'produit_a_delete')]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $produit = $em->getRepository(ProduitA::class)->find($id);
        if (!$produit) {
            $this->addFlash('error','Le produit que vous recherche n\'existe pas');
        }else{
            $em->remove($produit);
            $em->flush();
            $this->addFlash('success','Produit supprimer avec success');
        }
        return $this->redirectToRoute('produit_a_list');
    }

    #[Route('/produit/a/recherche/prix', name: 'produit_prix_a_recherche')]
    public function RecherchePrix(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees['nom'])) {
                $produit = $entityManager->getRepository(ProduitA::class)->findBy(['nom' => $donnees['nom']]);
                if ($produit) {
                    return $this->json([
                        'success' => true,
                        'message' => $produit[0]->getPrixvente(),
                        'quantite' => $produit[0]->getQuantite(),
                    ]);
                } else {
                    return $this->json(['error' => 'Produit non trouvé'], 404);
                }
            }
        }
        return $this->json(['error' => 'Prix non spécifié'], 404);
    }
}
