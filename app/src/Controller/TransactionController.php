<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Controller responsible for managing transactions.
 */
#[Route('/transaction')]
#[IsGranted('ROLE_USER')]
class TransactionController extends AbstractController
{
    public function __construct(
        private TransactionService $transactionService,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * Displays a list of transactions for the current user, with optional filtering by tag and date range.
     *
     * @param Request $request the HTTP request
     *
     * @return Response the response object
     */
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filters = [
            'tag' => $request->query->get('tag'),
            'category' => $request->query->get('category'),
            'portfolio' => $request->query->get('portfolio'),
            'start_date' => $request->query->get('start_date'),
            'end_date' => $request->query->get('end_date'),
        ];

        $transactions = $this->transactionService->getTransactionsByUser($this->getUser(), $filters);
        $userCategories = $this->transactionService->getUserCategories($this->getUser());
        $userPortfolios = $this->transactionService->getUserPortfolios($this->getUser());

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactions,
            'tags' => $this->getUser()->getTags(),
            'categories' => $userCategories,
            'portfolios' => $userPortfolios,
            'selected_tag' => $filters['tag'],
            'selected_category' => $filters['category'],
            'selected_portfolio' => $filters['portfolio'],
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ]);
    }

    /**
     * Handles creation of a new transaction.
     *
     * @param Request $request the HTTP request
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
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
            if (null === $transactionType) {
                $this->addFlash('error', $this->translator->trans('common.error_select_transaction_type'));
                return $this->render('transaction/new.html.twig', [
                    'transaction' => $transaction,
                    'form' => $form,
                ]);
            }

            if ('expense' === $transactionType) {
                $transaction->setAmount(-abs($transaction->getAmount()));
            } else {
                $transaction->setAmount(abs($transaction->getAmount()));
            }

            $this->transactionService->createTransaction($transaction);

            $this->addFlash('success', $this->translator->trans('transaction.transaction_created_successfully'));

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
     * @param Request     $request     the HTTP request
     * @param Transaction $transaction the transaction entity
     *
     * @return Response the response object
     */
    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', subject: 'transaction')]
    public function edit(Request $request, Transaction $transaction): Response
    {
        $oldAmount = $transaction->getAmount();

        $form = $this->createForm(TransactionType::class, $transaction, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio = $transaction->getPortfolio();

            if (null === $portfolio) {
                $this->addFlash('error', $this->getParameter('kernel.default_locale') === 'pl' ? 'Proszę wybrać portfel.' : 'Please select a portfolio.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if ($this->getUser() !== $portfolio->getOwner()) {
                throw $this->createAccessDeniedException('You cannot edit transactions in this portfolio.');
            }

            if (null === $transaction->getTitle()) {
                $this->addFlash('error', $this->getParameter('kernel.default_locale') === 'pl' ? 'Proszę podać tytuł transakcji.' : 'Please enter a transaction title.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if (null === $transaction->getCategory()) {
                $this->addFlash('error', $this->getParameter('kernel.default_locale') === 'pl' ? 'Proszę wybrać kategorię.' : 'Please select a category.');

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $transactionType = $form->get('transactionType')->getData();
            if (null === $transactionType) {
                $this->addFlash('error', $this->translator->trans('transaction.please_select_transaction_type'));

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $amount = abs($transaction->getAmount());
            if (0 >= $amount) {
                $this->addFlash('error', $this->translator->trans('transaction.please_enter_valid_amount'));

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if ('expense' === $transactionType) {
                $amount = -$amount;
            }
            $transaction->setAmount($amount);

            $balanceDiff = $amount - $oldAmount;
            $newBalance = $portfolio->getBalance() + $balanceDiff;

            if (0 > $newBalance) {
                $this->addFlash('error', $this->translator->trans('transaction.transaction_negative_balance'));

                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $portfolio->setBalance($newBalance);

            $this->transactionService->updateTransaction($transaction);

            $this->addFlash('success', $this->translator->trans('transaction.transaction_updated_successfully'));

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
     * @param Request     $request     the HTTP request
     * @param Transaction $transaction the transaction entity
     *
     * @return Response the response object
     */
    #[Route('/{id}/delete', name: 'app_transaction_delete', methods: ['POST'])]
    #[IsGranted('delete', subject: 'transaction')]
    public function delete(Request $request, Transaction $transaction): Response
    {

        if ($this->isCsrfTokenValid('delete' . $transaction->getId(), $request->request->get('_token'))) {
            $this->transactionService->deleteTransaction($transaction);

            $this->addFlash('success', $this->translator->trans('transaction.transaction_deleted_successfully'));
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
    #[IsGranted('view', subject: 'transaction')]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Get categories for transaction type via AJAX.
     *
     * @param Request $request the HTTP request
     *
     * @return JsonResponse the JSON response
     */
    #[Route('/get-categories', name: 'app_transaction_get_categories', methods: ['POST'])]
    public function getCategories(Request $request): JsonResponse
    {
        if (!$this->isCsrfTokenValid('transaction-type', $request->headers->get('X-CSRF-TOKEN'))) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid CSRF token'], 400);
        }

        $transactionType = $request->request->get('transactionType');
        if (!in_array($transactionType, ['income', 'expense'])) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid transaction type: ' . $transactionType], 400);
        }

        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'error' => 'User not authenticated'], 401);
        }

        // Debug: sprawdź wszystkie kategorie użytkownika
        $allCategories = $user->getCategories();
        $allCategoriesData = [];
        foreach ($allCategories as $category) {
            $allCategoriesData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'type' => $category->getType(),
            ];
        }

        $categories = $user->getCategories()->filter(function ($category) use ($transactionType) {
            return $category->getType() === $transactionType;
        });

        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'categories' => $categoryData,
            'debug' => [
                'transactionType' => $transactionType,
                'totalCategories' => $user->getCategories()->count(),
                'filteredCategories' => $categories->count(),
                'allCategories' => $allCategoriesData,
            ],
        ]);
    }
}
