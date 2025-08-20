<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller responsible for managing transactions.
 */
#[Route('/transaction')]
#[IsGranted('ROLE_USER')]
class TransactionController extends AbstractController
{
    /**
     * Displays a list of transactions for the current user, with optional filtering by tag and date range.
     *
     * @param Request               $request               the HTTP request
     * @param TransactionRepository $transactionRepository the transaction repository
     *
     * @return Response the response object
     */
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(Request $request, TransactionRepository $transactionRepository): Response
    {
        $tagId = $request->query->get('tag');
        $categoryName = $request->query->get('category');
        $portfolioName = $request->query->get('portfolio');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $qb = $transactionRepository->createQueryBuilder('t')
            ->leftJoin('t.portfolio', 'p')
            ->leftJoin('t.category', 'c')
            ->where('p.owner = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('t.date', 'DESC');

        if ($tagId) {
            $qb->leftJoin('t.tags', 'tag')
                ->andWhere('tag.id = :tagId')
                ->setParameter('tagId', $tagId);
        }

        if ($categoryName) {
            $qb->andWhere('c.name = :categoryName')
                ->setParameter('categoryName', $categoryName);
        }

        if ($portfolioName) {
            $qb->andWhere('p.name = :portfolioName')
                ->setParameter('portfolioName', $portfolioName);
        }

        if ($startDate) {
            $qb->andWhere('t.date >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $qb->andWhere('t.date <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate.' 23:59:59'));
        }

        $transactions = $qb->getQuery()->getResult();

        // Get all categories and portfolios for the user (for dropdowns)
        $userCategories = $transactionRepository->createQueryBuilder('t')
            ->select('DISTINCT c.name')
            ->leftJoin('t.portfolio', 'p')
            ->leftJoin('t.category', 'c')
            ->where('p.owner = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getScalarResult();

        $userPortfolios = $transactionRepository->createQueryBuilder('t')
            ->select('DISTINCT p.name')
            ->leftJoin('t.portfolio', 'p')
            ->where('p.owner = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactions,
            'tags' => $this->getUser()->getTags(),
            'categories' => array_column($userCategories, 'name'),
            'portfolios' => array_column($userPortfolios, 'name'),
            'selected_tag' => $tagId,
            'selected_category' => $categoryName,
            'selected_portfolio' => $portfolioName,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    /**
     * Handles creation of a new transaction.
     *
     * @param Request                $request       the HTTP request
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio = $transaction->getPortfolio();

            if ($this->getUser() !== $portfolio->getOwner()) {
                throw $this->createAccessDeniedException('You cannot create transactions in this portfolio.');
            }

            $transactionType = $form->get('transactionType')->getData();
            if ('expense' === $transactionType) {
                $transaction->setAmount(-abs($transaction->getAmount()));
            } else {
                $transaction->setAmount(abs($transaction->getAmount()));
            }

            $entityManager->persist($transaction);
            $portfolio->addTransaction($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction created successfully.');

            return $this->redirectToRoute('app_transaction_index');
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    /**
     * Handles editing of an existing transaction.
     *
     * @param Request                $request       the HTTP request
     * @param Transaction            $transaction   the transaction entity
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('edit', $transaction->getPortfolio());
        $oldAmount = $transaction->getAmount();

        $form = $this->createForm(TransactionType::class, $transaction, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio = $transaction->getPortfolio();

            if (null === $portfolio) {
                $this->addFlash('error', 'Please select a portfolio.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if ($this->getUser() !== $portfolio->getOwner()) {
                throw $this->createAccessDeniedException('You cannot edit transactions in this portfolio.');
            }

            if (null === $transaction->getTitle()) {
                $this->addFlash('error', 'Please enter a transaction title.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if (null === $transaction->getCategory()) {
                $this->addFlash('error', 'Please select a category.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $transactionType = $form->get('transactionType')->getData();
            if (null === $transactionType) {
                $this->addFlash('error', 'Please select a transaction type.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $amount = abs($transaction->getAmount());
            if (0 >= $amount) {
                $this->addFlash('error', 'Please enter a valid amount greater than 0.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if ('expense' === $transactionType) {
                $amount = -$amount;
            }
            $transaction->setAmount($amount);

            $balanceDiff = $amount - $oldAmount;
            $newBalance = $portfolio->getBalance() + $balanceDiff;

            if (0 > $newBalance) {
                $this->addFlash('error', 'Transaction would result in negative balance.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $portfolio->setBalance($newBalance);

            $entityManager->flush();

            $this->addFlash('success', 'Transaction updated successfully.');

            return $this->redirectToRoute('app_transaction_index');
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    /**
     * Handles deletion of a transaction.
     *
     * @param Request                $request       the HTTP request
     * @param Transaction            $transaction   the transaction entity
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/{id}/delete', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $transaction->getPortfolio()->getOwner()) {
            throw $this->createAccessDeniedException('You cannot delete this transaction.');
        }

        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $portfolio = $transaction->getPortfolio();
            $portfolio->removeTransaction($transaction);
            $entityManager->remove($transaction);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction deleted successfully.');
        }

        return $this->redirectToRoute('app_transaction_index');
    }

    /**
     * Displays a single transaction.
     *
     * @param Transaction $transaction the transaction entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        $this->denyAccessUnlessGranted('view', $transaction->getPortfolio());

        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }
}
