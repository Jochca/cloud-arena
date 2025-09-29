<?php

declare(strict_types=1);

namespace App\Player\Entity;

use App\Player\ValueObjects\Gender;
use App\Session\Entity\Session;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'player')]
class Player
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    public Uuid $id {
        get => $this->id;
        set(Uuid $value) => $this->id = $value;
    }

    #[ORM\Column(type: 'string', length: 255)]
    public string $name {
        get => $this->name;
        set(string $value) => $this->name = $value;
    }

    #[ORM\Column(enumType: Gender::class)]
    public Gender $gender {
        get => $this->gender;
        set(Gender $value) => $this->gender = $value;
    }

    #[ORM\ManyToOne(targetEntity: Session::class, inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Session $session {
        get => $this->session;
        set(Session $value) => $this->session = $value;
    }
}
