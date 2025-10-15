<?php

namespace App\Controller;

use App\Entity\TempAgence;
use App\Entity\TresorerieA;
use App\Form\TresorerieAType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TresorerieAController extends AbstractController
{
    #[Route('/tresorerie/a', name: 'app_tresorerie_a')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $tresorerie = new TresorerieA();
        $form = $this->createForm(TresorerieAType::class,$tresorerie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tresorerie->setUser($user);
            $tresorerie->setAgence($tempagence);

            $ordre = $entityManager->getRepository(TresorerieA::class)->findOneBy([], ['id' => 'DESC']);
            if ($ordre) {
                $valeur = $ordre->getOrdre();
                $tresorerie->setOrdre($valeur+1);
            }else{
                $tresorerie->setOrdre(1);
            }

            $entityManager->persist($tresorerie);
            $entityManager->flush();

            return $this->redirectToRoute('app_tresorerie_a_list');
        }
        return $this->render('tresorerie_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $tempagence->getId(),
        ]);
    }

    #[Route('/tresorerie/a/list', name: 'app_tresorerie_a_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $html = $this->render('tresorerie_a/list.html.twig', [
            'controller_name' => 'TresorerieController',
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );
         
    }
}
