<?php

declare(strict_types=1);

namespace App\Task\Entity;

use App\Player\Entity\Player;
use App\Task\ValueObject\ActivityStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'task_activity')]
class TaskActivity
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

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $dateStart {
        get => $this->dateStart;
        set(\DateTimeImmutable $value) => $this->dateStart = $value;
    }

    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $dateEnd {
        get => $this->dateEnd;
        set(\DateTimeImmutable $value) => $this->dateEnd = $value;
    }

    #[ORM\Column(enumType: ActivityStatus::class)]
    public ActivityStatus $status {
        get => $this->status;
        set(ActivityStatus $value) => $this->status = $value;
    }

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Task $task {
        get => $this->task;
        set(Task $value) => $this->task = $value;
    }
}
