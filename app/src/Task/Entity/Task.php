<?php

declare(strict_types=1);

namespace App\Task\Entity;

use App\Player\Entity\Player;
use App\Session\Entity\Session;
use App\Task\ValueObject\TaskStatus;
use App\Task\ValueObject\TaskType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'task')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Uuid $id {
        get => $this->id;
        set(Uuid $value) => $this->id = $value;
    }

    #[ORM\ManyToOne(targetEntity: Session::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Session $session {
        get => $this->session;
        set(Session $value) => $this->session = $value;
    }

    #[ORM\Column(enumType: TaskType::class)]
    public TaskType $type {
        get => $this->type;
        set(TaskType $value) => $this->type = $value;
    }

    #[ORM\Column(type: 'integer')]
    public int $value {
        get => $this->value;
        set(int $value) => $this->value = $value;
    }

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL', nullable: true)]
    public ?Player $player {
        get => $this->player;
        set(?Player $value) => $this->player = $value;
    }

    #[ORM\Column(type: 'string', length: 255)]
    public string $name {
        get => $this->name;
        set(string $value) => $this->name = $value;
    }

    #[ORM\Column(type: 'string', length: 255)]
    public string $description {
        get => $this->description;
        set(string $value) => $this->description = $value;
    }

    #[ORM\Column(enumType: TaskStatus::class)]
    public TaskStatus $status {
        get => $this->status;
        set(TaskStatus $value) => $this->status = $value;
    }
}

