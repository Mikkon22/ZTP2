<?php

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

#[Route('/transaction')]
#[IsGranted('ROLE_USER')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(Request $request, TransactionRepository $transactionRepository): Response
    {
        $tagId = $request->query->get('tag');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        $qb = $transactionRepository->createQueryBuilder('t')
            ->leftJoin('t.portfolio', 'p')
            ->where('p.owner = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('t.date', 'DESC');

        if ($tagId) {
            $qb->leftJoin('t.tags', 'tag')
                ->andWhere('tag.id = :tagId')
                ->setParameter('tagId', $tagId);
        }

        if ($startDate) {
            $qb->andWhere('t.date >= :startDate')
                ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $qb->andWhere('t.date <= :endDate')
                ->setParameter('endDate', new \DateTime($endDate . ' 23:59:59'));
        }

        $transactions = $qb->getQuery()->getResult();

        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactions,
            'tags' => $this->getUser()->getTags(),
            'selected_tag' => $tagId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

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
            
            // Check if portfolio belongs to the current user
            if ($portfolio->getOwner() !== $this->getUser()) {
                throw $this->createAccessDeniedException('You cannot create transactions in this portfolio.');
            }

            // Set amount based on transaction type
            $transactionType = $form->get('transactionType')->getData();
            if ($transactionType === 'expense') {
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
            
            // Check if portfolio is selected and belongs to the current user
            if (!$portfolio) {
                $this->addFlash('error', 'Please select a portfolio.');
                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }
            
            if ($portfolio->getOwner() !== $this->getUser()) {
                throw $this->createAccessDeniedException('You cannot edit transactions in this portfolio.');
            }

            // Validate required fields
            if (!$transaction->getTitle()) {
                $this->addFlash('error', 'Please enter a transaction title.');
                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if (!$transaction->getCategory()) {
                $this->addFlash('error', 'Please select a category.');
                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }
            
            // Get the transaction type and adjust the amount accordingly
            $transactionType = $form->get('transactionType')->getData();
            if (!$transactionType) {
                $this->addFlash('error', 'Please select a transaction type.');
                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            $amount = abs($transaction->getAmount());
            if ($amount <= 0) {
                $this->addFlash('error', 'Please enter a valid amount greater than 0.');
                return $this->redirectToRoute('app_transaction_edit', ['id' => $transaction->getId()]);
            }

            if ($transactionType === 'expense') {
                $amount = -$amount;
            }
            $transaction->setAmount($amount);
            
            // Update portfolio balance
            $balanceDiff = $amount - $oldAmount;
            $newBalance = $portfolio->getBalance() + $balanceDiff;
            
            if ($newBalance < 0) {
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

    #[Route('/{id}/delete', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($transaction->getPortfolio()->getOwner() !== $this->getUser()) {
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
} 