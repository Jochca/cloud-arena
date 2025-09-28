<?php

declare(strict_types=1);

namespace App\Session\Entity;

use App\Player\Entity\Player;
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
}

