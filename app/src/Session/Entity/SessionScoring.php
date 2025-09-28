<?php

declare(strict_types=1);

namespace App\Session\Entity;

use App\Player\Entity\Player;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'session_scoring')]
class SessionScoring
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Uuid $id {
        get => $this->id;
        set(Uuid $value) => $this->id = $value;
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

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Player $winner {
        get => $this->winner;
        set(Player $value) => $this->winner = $value;
    }

    #[ORM\ManyToOne(targetEntity: Session::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Session $session {
        get => $this->session;
        set(Session $value) => $this->session = $value;
    }
}
