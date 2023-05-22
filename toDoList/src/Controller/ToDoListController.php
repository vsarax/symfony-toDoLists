<?php

namespace App\Controller;

use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ToDoListController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('to_do_list/index.html.twig');
    }

    #[Route('/todolists', name: 'to_do_lists')]
    public function show(Security $security): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $to_do_lists = $user->getToDoLists();


        return $this->render('to_do_list/to_do_lists.html.twig', [
            'to_do_lists' => $to_do_lists,
        ]);
    }

    #[Route('/create/list', name: 'create_list')]
    public function create_list(Request $request, EntityManagerInterface $entityManager): Response
    {
        $title = trim($request->request->get('title'));

        if(empty($title)) {
            return $this->redirectToRoute('to_do_lists');
        }

        $user = $this->getUser();

        $to_do_list = new ToDoList();
        $to_do_list->setTitle($title);

        $user->addToDoList($to_do_list);

        $entityManager->flush();

        return $this->redirectToRoute('to_do_lists');
    }

    #[Route('/delete/list/{id}', name: 'delete_list')]
    public function delete_list(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Log in to delete the list.');
        }

        $to_do_list = $entityManager->getRepository(ToDoList::class)->find($id);

        if (!$to_do_list) {
            throw $this->createNotFoundException('To do list not found');
        }

        $user->removeToDoList($to_do_list);

        $entityManager->flush();

        return $this->redirectToRoute('to_do_lists');
    }
}
