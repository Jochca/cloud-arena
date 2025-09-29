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

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Player::class, cascade: ['persist', 'remove'])]
    public Collection $players;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Task::class, cascade: ['persist', 'remove'])]
    private Collection $tasks;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
