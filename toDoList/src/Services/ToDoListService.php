<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\ToDoList;
use Doctrine\ORM\EntityManagerInterface;

class ToDoListService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getToDoListsForUser(User $user)
    {
        return $user->getToDoLists();
    }

    public function createToDoList(User $user, string $title)
    {
        $toDoList = new ToDoList();
        $toDoList->setTitle($title);

        $user->addToDoList($toDoList);

        $this->entityManager->flush();
    }

    public function deleteToDoListById(int $id): void
    {
        $toDoList = $this->entityManager->getRepository(ToDoList::class)->find($id);

        if (!$toDoList) {
            throw new \Exception("Not found");
        }

        $this->entityManager->remove($toDoList);
        $this->entityManager->flush();
    }
}
?>
