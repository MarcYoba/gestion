<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Emprunt;
use App\Entity\TempAgence;
use App\Form\EmpruntType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt/creat', name: 'app_emprunt')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class,$emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $emprunt->setAgence($tempagence->getAgence());
            $emprunt->setUser($user);

            $em->persist($emprunt);
            $em->flush();

            $balance = $em->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
            if ($balance) {
                $mouvement = $balance->getMouvementDebit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementDebit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            $balance = $em->getRepository(Balance::class)->findOneBy(['Compte' => 1600]);
            if ($balance) {
                $mouvement = $balance->getMouvementCredit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementCredit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            return $this->redirectToRoute('app_emprunt_list');
        }
        return $this->render('emprunt/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/emprunt/list', name: 'app_emprunt_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $emprunt = $em->getRepository(Emprunt::class)->findBy(['agence' => $id]);

        return $this->render('emprunt/list.html.twig', [
            'emprunts' => $emprunt,
        ]);
    }

    #[Route('/emprunt/edit/{id}', name: 'app_emprunt_edit')]
    public function edit(EntityManagerInterface $em, Emprunt $emprunt): Response
    {
        if ($emprunt) {
            return $this->render('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
        ]);
        }
        return $this->redirectToRoute('app_emprunt_list');
    }

    #[Route('/emprunt/update', name: 'app_emprunt_update')]
    public function update(EntityManagerInterface $em, Request $request): Response
    {
        $variable = $request->request->all('emprunts');

        foreach ($variable as $key => $value) {
            $emprunt = $em->getRepository(Emprunt::class)->find($key);

            if ($emprunt) {
                $emprunt->setType($value['type'] ?? 0);
                $emprunt->setMontant($value['montant'] ?? 0);
                $emprunt->setCreatetAt(new \DateTime($value['createtAt']) ?? date("Y-m-d"));
                $emprunt->setDurre($value['durre'] ?? 0);
                $emprunt->setTauxinteretdebiteur($value['tauxinteretdebiteur'] ?? 0);
                $emprunt->setTauxannueleffectifglobal($value['tauxannueleffectifglobal'] ?? 0);
                $emprunt->setCouttotal($value['couttotal'] ?? 0);
                $emprunt->setGarantie($value['garantie'] ?? 0);
                $emprunt->setIdentitepreteur($value['identitepreteur'] ?? 0);
                $emprunt->setEmprunteur($value['emprunteur'] ?? 0);

                $em->persist($emprunt);
                $em->flush();
            }
            
        }
        return $this->redirectToRoute('app_emprunt_list');
    }

    #[Route('/emprunt/delet/{id}', name: 'app_emprunt_delete')]
    public function delete(EntityManagerInterface $em, Emprunt $emprunt): Response
    {
        if ($emprunt) {
            $em->remove($emprunt);
            $em->flush();
        }
        return $this->redirectToRoute('app_emprunt_list');
    }
}
