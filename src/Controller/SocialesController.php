<?php

namespace App\Controller;

use App\Entity\Sociales;
use App\Entity\TempAgence;
use App\Form\SocialesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocialesController extends AbstractController
{
    #[Route('/sociales/creat', name: 'app_sociales')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $sociale = new Sociales();
        $form = $this->createForm(SocialesType::class,$sociale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sociale->setUser($user);
            $sociale->setAgence($tempagence->getAgence());

            $em->persist($sociale);
            $em->flush();
            return $this->redirectToRoute('app_sociales_list');
        }
        return $this->render('sociales/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sociales/list', name: 'app_sociales_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $sociale = $em->getRepository(Sociales::class)->findBy(['agence' => $id]);

        return $this->render('sociales/list.html.twig', [
            'sociales' => $sociale,
        ]);
    }

    #[Route('/sociales/edit/{id}', name: 'app_sociales_edit')]
    public function edit(Sociales $sociale): Response
    {
        if ($sociale) {
            return $this->render('sociales/edit.html.twig', [
            'sociale' => $sociale,
        ]);
        }

        return $this->redirectToRoute('app_sociales_list'); 
    }

    #[Route('/sociales/update', name: 'app_sociales_update', methods:["POST"])]
    public function update(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $variable=  $request->request->all('sociales');
        foreach ($variable as $key => $value) {
            $sociale = $em->getRepository(Sociales::class)->find($key);

            if($sociale)
            {
                $sociale->setNom($value['nom'] ?? 0);
                $sociale->setNumeroCotisation($value['numeroCotisation'] ?? 0);
                $sociale->setProfession($value['profession'] ?? 0);
                $sociale->setCni($value['cni'] ?? 0);
                $sociale->setDu($value['du'] ?? 0);
                $sociale->setVille($value['ville'] ?? 0);
                $sociale->setParent($value['parent'] ?? 0);
                $sociale->setIdentite($value['identite'] ?? 0);
                $sociale->setDelivree($value['delivree'] ?? 0);
                $sociale->setCity($value['city'] ?? 0);
                $sociale->setCreatetAt(new \DateTime($value['createtAt']) ?? 0);

                $em->persist($sociale);
                $em->flush();
            }
        }

    
        return $this->redirectToRoute('app_sociales_list');
    }

    #[Route('/sociales/delete/{id}', name: 'app_sociales_delete')]
    public function delete(EntityManagerInterface $em,Sociales $sociale): Response
    {
        if ($sociale) {
            $em->remove($sociale);
            $em->flush();
        }

        return $this->redirectToRoute('app_sociales_list');
    }
}
