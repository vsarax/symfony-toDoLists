<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    #[Route('/list/{id}', name: 'list')]
    public function list(int $id, EntityManagerInterface $entityManager): Response
    {
        $to_do_list = $entityManager->getRepository(ToDoList::class)->find($id);

        if (!$to_do_list) {
            throw $this->createNotFoundException('To do list not found');
        }

        $tasks = $entityManager->getRepository(Task::class)->findBy([
        'to_do_list' => $to_do_list,
        'status' => 0,
        ]);

        $completed_tasks = $entityManager->getRepository(Task::class)->findBy([
        'to_do_list' => $to_do_list,
        'status' => 1,
        ]);

        return $this->render('to_do_list/tasks.html.twig', [
        'to_do_list' => $to_do_list,
        'tasks' => $tasks,
        'completed_tasks' => $completed_tasks,
        ]);  
    }

    #[Route('/create/task/{to_do_list_id}', name: 'create_task')]
    public function create_task(Request $request, int $to_do_list_id, EntityManagerInterface $entityManager): Response
    {
        $name = trim($request->request->get('name'));

        if(empty($name)) {
            return $this->redirectToRoute('list');
        }

        $to_do_list = $entityManager->getRepository(ToDoList::class)->find($to_do_list_id);

        if (!$to_do_list) {
            throw $this->createNotFoundException('Nie znaleziono listy zadań o podanym id.');
        }

        $task = new Task();
        $task->setName($name);
        $task->setStatus(0);

        $task->setToDoList($to_do_list);

        $to_do_list->addTask($task);

        $entityManager->flush();

        return $this->redirectToRoute('list', ['id' => $to_do_list_id]);
    }

    #[Route('/completed/{id}', name: 'task_completed')]
    public function completed(int $id, EntityManagerInterface $entityManager): Response
    {

        $task = $entityManager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('Nie znaleziono zadania o podanym id.');
        }
    
        $currentStatus = $task->getStatus();
    
        $newStatus = $currentStatus === 0 ? 1 : 0;
    
        $task->setStatus($newStatus);
        $entityManager->flush();

        $to_do_list_id = $task->getToDoList()->getId();

        return $this->redirectToRoute('list', ['id' => $to_do_list_id]);
    }
}
