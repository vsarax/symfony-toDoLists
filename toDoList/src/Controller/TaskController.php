<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TaskService;


class TaskController extends AbstractController
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    
    #[Route('/list/{id}', name: 'list')]
    public function showTaskList(int $id): Response
    {
        $taskList = $this->taskService->getTaskList($id);

        return $this->render('toDoList/tasks.html.twig', $taskList);
    }


    #[Route('/create/task/{toDoListId}', name: 'createTask')]
    public function createTask(Request $request, int $toDoListId): Response
    {
        $name = trim($request->request->get('name'));

        if(empty($name)) {
            return $this->redirectToRoute('toDoList.html.twig');
        }

        $this->taskService->createTask($toDoListId, $name);

        return $this->redirectToRoute('list', ['id' => $toDoListId]);
    }


    #[Route('/completed/{id}', name: 'taskCompleted')]
    public function markTaskCompleted(int $id): Response
    {
        $this->taskService->markTaskCompleted($id);
    
        $toDoListId = $this->taskService->getToDoListIdForTask($id);

        return $this->redirectToRoute('list', ['id' => $toDoListId]);
    }
}
