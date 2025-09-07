<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminChangePasswordType;
use App\Form\AdminEditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
/**
 * Controller for admin user management.
 */
class AdminController extends AbstractController
{
    /**
     * Display the list of users for admin.
     *
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/users', name: 'app_admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Edit a user as admin.
     *
     * @param Request                $request       the HTTP request
     * @param User                   $user          the user entity to edit
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/users/{id}/edit', name: 'app_admin_edit_user')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AdminEditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', \sprintf($this->getParameter('kernel.default_locale') === 'pl' ? 'Użytkownik "%s" został zaktualizowany pomyślnie!' : 'User "%s" has been updated successfully!', $user->getEmail()));

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Change a user's password as admin.
     *
     * @param Request                     $request        the HTTP request
     * @param User                        $user           the user entity whose password is to be changed
     * @param UserPasswordHasherInterface $passwordHasher the password hasher
     * @param EntityManagerInterface      $entityManager  the entity manager
     *
     * @return Response the response object
     */
    #[Route('/users/{id}/change-password', name: 'app_admin_change_user_password')]
    public function changeUserPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AdminChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', \sprintf($this->getParameter('kernel.default_locale') === 'pl' ? 'Hasło dla użytkownika %s zostało zmienione pomyślnie!' : 'Password for user %s has been changed successfully!', $user->getEmail()));

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
