<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller responsible for user profile actions.
 */
class ProfileController extends AbstractController
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
     * Handles password change for the logged-in user.
     *
     * @param Request $request the HTTP request
     *
     * @return Response the response object
     */
    #[Route('/profile/change-password', name: 'app_change_password')]
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('You need to be logged in to change your password.');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->changePassword($user, $form->get('newPassword')->getData());

            $this->addFlash('success', 'common.success_password_changed');

            return $this->redirectToRoute('app_portfolio_index');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
