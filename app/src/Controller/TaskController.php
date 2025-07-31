<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for managing tasks.
 */
#[Route('/task')]
class TaskController extends AbstractController
{
    /**
     * Display the list of tasks.
     *
     * @param TaskRepository $taskRepository the task repository
     *
     * @return Response the response object
     */
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * Create a new task (example, no form).
     *
     * @return Response the response object
     */
    #[Route('/new', name: 'task_new', methods: ['GET'])]
    public function new(): Response
    {
        // We'll add form handling later
        $task = new Task();
        $task->setTitle('Example Task');
        $task->setDescription('This is an example task created without a form.');

        return $this->render('task/new.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * Show a task.
     *
     * @param Task $task the task entity
     *
     * @return Response the response object
     */
    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * Toggle the completion status of a task.
     *
     * @param Task                   $task          the task entity
     * @param EntityManagerInterface $entityManager the entity manager
     *
     * @return Response the response object
     */
    #[Route('/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(Task $task, EntityManagerInterface $entityManager): Response
    {
        $task->setIsDone(!$task->isIsDone());
        $entityManager->flush();

        return $this->redirectToRoute('task_index');
    }
}
