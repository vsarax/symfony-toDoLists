<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ToDoList;

class TaskService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTaskList(int $id): array
    {
        $toDoList = $this->entityManager->getRepository(ToDoList::class)->find($id);

        if (!$toDoList) {
            throw new \Exception("Not found");
        }

        $tasks = $this->entityManager->getRepository(Task::class)->findBy([
            'to_do_list' => $toDoList,
            'status' => 0,
        ]);

        $completedTasks = $this->entityManager->getRepository(Task::class)->findBy([
            'to_do_list' => $toDoList,
            'status' => 1,
        ]);

        return [
            'to_do_list' => $toDoList,
            'tasks' => $tasks,
            'completed_tasks' => $completedTasks,
        ];
    }

    public function createTask(int $toDoListId, string $name): void
    {
        $toDoList = $this->entityManager->getRepository(ToDoList::class)->find($toDoListId);

        if (!$toDoList) {
            throw new \Exception("Not found");
        }

        $task = new Task();
        $task->setName($name);
        $task->setStatus(0);

        $task->setToDoList($toDoList);

        $toDoList->addTask($task);

        $this->entityManager->persist($task);

        $this->entityManager->flush();
    }

    public function markTaskCompleted(int $id): void
    {

        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw new \Exception("Not found");
        }
    
        $currentStatus = $task->getStatus();
    
        $newStatus = $currentStatus === 0 ? 1 : 0;
    
        $task->setStatus($newStatus);
        $this->entityManager->flush();
    }

    public function getToDoListIdForTask(int $taskId): int
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            throw new \Exception("Not found");
        }

        return $task->getToDoList()->getId();
    }
}
?>
