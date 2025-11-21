<?php

namespace App\Controller;

use App\Entity\EmpruntA;
use App\Entity\TempAgence;
use App\Form\EmpruntAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpruntAController extends AbstractController
{
    #[Route('/emprunt/a/creat', name: 'app_emprunt_a')]
    public function index(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $emprunt = new EmpruntA();
        $form = $this->createForm(EmpruntAType::class,$emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt->setAgence($tempagence->getAgence());
            $emprunt->setUser($user);

            $em->persist($emprunt);
            $em->flush();

            return $this->redirectToRoute('app_emprunt_list_a');
        }
        return $this->render('emprunt_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/emprunt/a/list', name: 'app_emprunt_list_a')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $emprunt = $em->getRepository(EmpruntA::class)->findBy(['agence' => $id]);

        return $this->render('emprunt_a/list.html.twig', [
            'emprunts' => $emprunt,
        ]);
    }

    #[Route('/emprunt/a/edit/{id}', name: 'app_emprunt_edit_a')]
    public function edit(EntityManagerInterface $em, EmpruntA $emprunt): Response
    {
        if ($emprunt) {
            return $this->render('emprunt_a/edit.html.twig', [
            'emprunt' => $emprunt,
        ]);
        }
        return $this->redirectToRoute('app_emprunt_list_a');
    }

    #[Route('/emprunt/a/update', name: 'app_emprunt_update_a')]
    public function update(EntityManagerInterface $em, Request $request): Response
    {
        $variable = $request->request->all('emprunts');

        foreach ($variable as $key => $value) {
            $emprunt = $em->getRepository(EmpruntA::class)->find($key);

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
        return $this->redirectToRoute('app_emprunt_list_a');
    }

    #[Route('/emprunt/a/delet/{id}', name: 'app_emprunt_delete_a')]
    public function delete(EntityManagerInterface $em, EmpruntA $emprunt): Response
    {
        if ($emprunt) {
            $em->remove($emprunt);
            $em->flush();
        }
        return $this->redirectToRoute('app_emprunt_list_a');
    }
}
