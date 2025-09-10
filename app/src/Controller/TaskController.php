<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskService;
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
     * Constructor.
     *
     * @param TaskService $taskService the task service
     */
    public function __construct(private readonly TaskService $taskService)
    {
    }

    /**
     * Display the list of tasks.
     *
     * @return Response the response object
     */
    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskService->getAllTasks(),
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
     * @param Task $task the task entity
     *
     * @return Response the response object
     */
    #[Route('/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(Task $task): Response
    {
        $this->taskService->toggleTask($task);

        return $this->redirectToRoute('task_index');
    }
}
