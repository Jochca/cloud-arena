<?php

declare(strict_types=1);

namespace App\Session\Entity;

use App\Player\Entity\Player;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'session_player_key')]
class SessionPlayerKey
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Uuid $id {
        get => $this->id;
        set(Uuid $value) => $this->id = $value;
    }

    #[ORM\OneToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Player $player {
        get => $this->player;
        set(Player $value) => $this->player = $value;
    }

    #[ORM\OneToOne(targetEntity: Session::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Session $session {
        get => $this->session;
        set(Session $value) => $this->session = $value;
    }

    #[ORM\Column(type: 'integer')]
    public int $key {
        get => $this->key;
        set(int $value) => $this->key = $value;
    }
}
