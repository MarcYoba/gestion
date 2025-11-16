<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Agence;
use App\Entity\TempAgence;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use App\Form\AchatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AchatController extends AbstractController
{
    #[Route('/achat/create', name: 'app_achat')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);
        
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $data = json_decode($request->getContent(), true);
            
            try {
                foreach ($data as $key) {

                    $achat = new Achat(); 

                    try {
                        $date = empty($key['datevalue']) 
                            ? new \DateTimeImmutable()
                            : new \DateTimeImmutable($key['datevalue']);
                    } catch (\Exception $e) {
                        $date = new \DateTimeImmutable();
                    }
                    $achat->setCreatedAt($date);
                    

                    $fournisseur = $entityManager->getReference(Fournisseur::class, $key['fournisseur']);
                    $produit = $entityManager->getReference(Produit::class, $key['produit']);

                    $ajout = $produit->getQuantite();

                    $achat->setPrix($key["prix"]);
                    $achat->setQuantite($key["quantite"]);
                    $achat->setMontant($key["total"]);
                    $achat->setType($key["type"]);
                    $achat->setUser($this->getUser());
                    $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $this->getUser()]);
                    $achat->setAgence($tempagence->getAgence());
                    $achat->setFournisseur($fournisseur);
                    $achat->setProduit($produit);

                    $ajout = $ajout + $key["quantite"];

                    $produit->setQuantite($ajout);
                    $entityManager->persist($achat);
                    
                }
                $entityManager->flush();
                return $this->json(['success' => true], 200);
            } catch (\Throwable $th) {
                return $this->json(['errors' => $th], 500);
            }
            
            
        }
        return $this->render('achat/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/achat/list', name: 'achat_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $produit = 0;
        $achat = 0;
        $fournisseur = 0;
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $produit = $entityManager->getRepository(Achat::class)->findAll();
            $achat = $entityManager->getRepository(Achat::class)->findAll();
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        }else{
            $produit = $entityManager->getRepository(Achat::class)->findBy(["agence" => $id]);
            $achat = $entityManager->getRepository(Achat::class)->findBy(["agence" => $id]);
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);
        }
        
        return $this->render('achat/list.html.twig', [
            'achat' => $achat,
            'produit' => $produit,
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/achat/edit/{id}', name: 'app_achat_edit')]
    public function Edit(EntityManagerInterface $entityManager, Achat $achat) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();        
        if (!$achat) {
            return $this->redirectToRoute('achat_list');
        }
        $produit = $entityManager->getRepository(Produit::class)->findAll();
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);

        return $this->render('achat/edit.html.twig', [
            'achats' => $achat, 
            'produit' => $produit,
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/achat/update',name:'achat_update', methods:'POST' )]
    public function update(EntityManagerInterface $entityManager, Request $request) : Response 
    {
       $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId(); 
        $achats = $request->request->all('achats');
        foreach ($achats as $key => $value) {
            $achat = $entityManager->getRepository(Achat::class)->find($key);
            $produit = $entityManager->getRepository(Produit::class)->find($value['produit']); 

            if (!empty($value['quantite_nouvelle']) && !empty($value['prix_nouvelle'])) {
                $quantitestock =  $produit->getQuantite() - $value['quantite_precedent'];
            
                $produit->setQuantite($quantitestock + $value['quantite_nouvelle']);

                $achat->setQuantite($value['quantite_nouvelle']);
                $achat->setPrix($value['prix_nouvelle']);
                $achat->setMontant($value['quantite_nouvelle'] * $value['prix_nouvelle']);

                $entityManager->persist($produit);
                $entityManager->flush();
            }

            $achat->settype($value['type']);
            $achat->setUser($user);

            $entityManager->persist($achat);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('achat_list');
    }

    #[Route('achat/delete/{id}', name:'achat_delete')]
    public function delete(EntityManagerInterface $entityManager, Achat $achat) : Response 
    {
        if ($achat) {
            $produit = $entityManager->getRepository(Produit::class)->find($achat->getProduit()->getId());
            if($produit){
                $quantite = $produit->getQuantite();
                $produit->setQuantite($quantite- $achat->getQuantite());

                $entityManager->persist($produit);
                $entityManager->flush();
            }
            
        }
        return $this->redirectToRoute('achat_list');
    }
}
