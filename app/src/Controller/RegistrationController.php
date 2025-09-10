<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\CategoryService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller responsible for user registration.
 */
class RegistrationController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserService                 $userService        the user service
     * @param CategoryService             $categoryService    the category service
     * @param UserPasswordHasherInterface $userPasswordHasher the password hasher
     */
    public function __construct(private readonly UserService $userService, private readonly CategoryService $categoryService, private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    /**
     * Handles user registration.
     *
     * @param Request $request The HTTP request object
     *
     * @return Response The HTTP response
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);
            $this->userService->createUser($user);

            // Create default categories for the new user
            $this->categoryService->createDefaultCategories($user);

            $this->addFlash('success', 'common.success_account_created');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
