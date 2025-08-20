<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Portfolio;
use App\Form\PortfolioType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/portfolio')]
#[IsGranted('ROLE_USER')]
/**
 * Controller for managing user portfolios.
 */
class PortfolioController extends AbstractController
{
    /**
     * Display the list of portfolios for the current user.
     *
     * @return Response the response object
     */
    #[Route('/', name: 'app_portfolio_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('portfolio/index.html.twig', [
            'portfolios' => $this->getUser()->getPortfolios(),
        ]);
    }

    /**
     * Create a new portfolio for the current user.
     *
     * @param Request                $request       the HTTP request
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'app_portfolio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $portfolio = new Portfolio();
        $portfolio->setOwner($this->getUser());

        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($portfolio);
            $entityManager->flush();

            return $this->redirectToRoute('app_portfolio_index');
        }

        return $this->render('portfolio/new.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form,
        ]);
    }

    /**
     * Show a portfolio.
     *
     * @param Portfolio $portfolio the portfolio entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_portfolio_show', methods: ['GET'])]
    public function show(Portfolio $portfolio): Response
    {
        $this->denyAccessUnlessGranted('view', $portfolio);

        return $this->render('portfolio/show.html.twig', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * Edit a portfolio.
     *
     * @param Request                $request       the HTTP request
     * @param Portfolio              $portfolio     the portfolio entity
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/{id}/edit', name: 'app_portfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Portfolio $portfolio, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $portfolio);

        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_portfolio_show', ['id' => $portfolio->getId()]);
        }

        return $this->render('portfolio/edit.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form,
        ]);
    }

    /**
     * Delete a portfolio.
     *
     * @param Request                $request       the HTTP request
     * @param Portfolio              $portfolio     the portfolio entity
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_portfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Portfolio $portfolio, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('delete', $portfolio);

        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($portfolio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_portfolio_index');
    }
}
