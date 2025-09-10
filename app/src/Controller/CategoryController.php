<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Security\Voter\CategoryVoter;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller responsible for managing categories.
 */
#[Route('/category')]
#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CategoryService $categoryService the category service
     */
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * Displays a list of categories for the current user.
     *
     * @return Response the response object
     */
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $this->getUser()->getCategories(),
        ]);
    }

    /**
     * Handles creation of a new category.
     *
     * @param Request $request the HTTP request
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new Category();
        $category->setOwner($this->getUser());

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->createCategory($category);

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * Displays a single category.
     *
     * @param Category $category the category entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    #[IsGranted(CategoryVoter::VIEW, subject: 'category')]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * Handles editing of a category.
     *
     * @param Request  $request  the HTTP request
     * @param Category $category the category entity
     *
     * @return Response the response object
     */
    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    #[IsGranted(CategoryVoter::EDIT, subject: 'category')]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->updateCategory($category);

            return $this->redirectToRoute('app_category_show', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * Handles deletion of a category.
     *
     * @param Request  $request  the HTTP request
     * @param Category $category the category entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    #[IsGranted(CategoryVoter::DELETE, subject: 'category')]
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->categoryService->deleteCategory($category);
        }

        return $this->redirectToRoute('app_category_index');
    }
}
