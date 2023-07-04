<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\ToDoListService;


class ToDoListController extends AbstractController
{
    private $toDoListService;

    public function __construct(ToDoListService $toDoListService)
    {
        $this->toDoListService = $toDoListService;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('toDoList/index.html.twig');
    }

    #[Route('/todolists', name: 'toDoList')]
    public function showLists(Security $security): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $to_do_lists = $this->toDoListService->getToDoListsForUser($user);

        return $this->render('toDoList/toDoLists.html.twig', [
            'to_do_lists' => $to_do_lists,
        ]);
    }

    #[Route('/create/list', name: 'createList')]
    public function createList(Request $request): Response
    {
        $title = trim($request->request->get('title'));

        if(empty($title)) {
            return $this->redirectToRoute('toDoList');
        }

        $user = $this->getUser();

        $this->toDoListService->createToDoList($user, $title);

        return $this->redirectToRoute('toDoList');
    }

    #[Route('/delete/list/{id}', name: 'deleteList')]
    public function deleteList(int $id): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Log in to delete the list.');
        }

        $this->toDoListService->deleteToDoListById($id);

        return $this->redirectToRoute('toDoList');
    }
}
?>
