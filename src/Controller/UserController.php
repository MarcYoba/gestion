<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('app_home');

        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/user/fotgot-password', name: "app_forgot")]
     
    public function fotgotPassword(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher) :Response {
        
        $user = new User();
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        $userExite = 0;
        //dd($request->request->get('email'));
        if ($form->isSubmitted() && $form->isValid()) {
            dd("bonjour");
            $data = $form->getData();
            $email = $form->get('email')->getData();
            dd($email);
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $userExite = 1;
            } else {
                $this->addFlash('error', 'Aucun utilisateur trouvé avec cet email.');
                $userExite = 0;
            }

            if (!empty($data['password'])) {
                // Hachage et mise à jour du mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
                $user->setPassword($hashedPassword);
                
                $entityManager->flush();
    
                $this->addFlash('success', 'Mot de passe mis à jour avec succès !');
                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('security/fotgot_password.html.twig',[
            'form' => $form->createView(),
            'user' => $userExite,
        ]);
    }

    /**
     *@Route(path ="/user/list/{id}" , name="user_list")
     */
    public function list(EntityManagerInterface $entityManager, int $id) : Response {
       $user = $entityManager->getRepository(User::class)->findAll();
        return $this->render('security/list.html.twig',[
            'user' => $user,
        ]);
    }
    #[Route(path : '/user/edit/{id}' , name: 'user_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id) : Response {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('user_list');
        }
        return $this->render('security/edit.html.twig',[
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    #[Route(path : '/user/delete/{id}' , name: 'user_delete')]
    public function delete(EntityManagerInterface $entityManager, User $user) : Response {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_list');
    }
}
