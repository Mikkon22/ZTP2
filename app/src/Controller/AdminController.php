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
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
/**
 * Controller for admin user management.
 */
class AdminController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserService $userService the user service
     */
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Display the list of users for admin.
     *
     * @return Response the response object
     */
    #[Route('/users', name: 'app_admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function users(): Response
    {
        $users = $this->userService->getAllUsers();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Edit a user as admin.
     *
     * @param Request $request the HTTP request
     * @param User    $user    the user entity to edit
     *
     * @return Response the response object
     */
    #[Route('/users/{id}/edit', name: 'app_admin_edit_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function editUser(Request $request, User $user): Response
    {

        $form = $this->createForm(AdminEditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->updateUser($user);

            $this->addFlash('success', 'common.success_user_updated');

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
     * @param Request $request the HTTP request
     * @param User    $user    the user entity whose password is to be changed
     *
     * @return Response the response object
     */
    #[Route('/users/{id}/change-password', name: 'app_admin_change_user_password')]
    #[IsGranted('ROLE_ADMIN')]
    public function changeUserPassword(Request $request, User $user): Response
    {

        $form = $this->createForm(AdminChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->changePassword($user, $form->get('newPassword')->getData());

            $this->addFlash('success', 'common.success_password_changed_for');

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/change_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
