<?php

declare(strict_types=1);

namespace App\Session\Entity;

use App\Player\Entity\Player;
use App\Task\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'session')]
class Session
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Uuid $id {
        get => $this->id;
        set(Uuid $value) => $this->id = $value;
    }

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Player $player {
        get => $this->player;
        set(Player $value) => $this->player = $value;
    }

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Task::class, cascade: ['persist', 'remove'])]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setSession($this);
        }
    }

    public function removeTask(Task $task): void
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getSession() === $this) {
                $task->setSession(null);
            }
        }
    }
}
