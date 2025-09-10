<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling task-related business logic.
 */
class TaskService
{
    /**
     * Constructor.
     *
     * @param TaskRepository         $taskRepository the task repository
     * @param EntityManagerInterface $entityManager  the entity manager
     */
    public function __construct(private readonly TaskRepository $taskRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get all tasks.
     *
     * @return Task[] the tasks
     */
    public function getAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    /**
     * Toggle the completion status of a task.
     *
     * @param Task $task the task entity
     */
    public function toggleTask(Task $task): void
    {
        $task->setIsDone(!$task->isIsDone());
        $this->entityManager->flush();
    }
}
