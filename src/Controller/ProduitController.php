<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Produit;
use App\Entity\TempAgence;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProduitController extends AbstractController
{
    #[Route('/produit/create', name: 'app_produit')]
    public function index(Request $request,EntityManagerInterface $entityManager,Security $security): Response
    {
        $produit = new Produit();
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute("app_logout");
        }
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setPrixachat(0);
            $produit->setGain(0);
            $produit->setUser($security->getUser());
            $employer = new Employer();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            if ($tempagence) {
               $employer = $tempagence->getAgence();
            }else{
                $this->addFlash('error', 'Agence introuvable pour cet utilisateur vous ne pouvez enregistrer de produit.');
                return $this->redirectToRoute("produit_list");
            }
            $produit->setAgence($employer);

            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/list', name: 'produit_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $produit = new Produit();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
         if ($tempagence->isGenerale()== 1) {
            $produit = $entityManager->getRepository(Produit::class)->findAll();
        }else{
            $produit = $entityManager->getRepository(Produit::class)->findBy(["agence" => $id]);
        }
        
        return $this->render('produit/list.html.twig', [
            'produits' => $produit,
        ]);
    }

    #[Route('/produit/recherche/prix', name: 'produit_prix_recherche')]
    public function RecherchePrix(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees['nom'])) {
                $produit = $entityManager->getRepository(Produit::class)->findBy(['nom' => $donnees['nom']]);
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
    #[Route('/produit/edit/{id}', name: 'produit_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/delete/{id}', name: 'produit_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute("produit_list");
    }
}
