<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use App\Entity\ToDoList;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'tasks', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ToDoList $to_do_list = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    public function setToDoList(?ToDoList $toDoList): self
    {
        $this->to_do_list = $toDoList;

        return $this;
    }
}
