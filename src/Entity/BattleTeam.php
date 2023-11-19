<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BattleTeamRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BattleTeamRepository::class)]
class BattleTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(["getBattleInfos"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getBattleInfos"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getBattleInfos"])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'battleTeams')]
    #[Groups(["getBattleInfos"])]
    #[ORM\JoinColumn(nullable: false)]
    
    private ?Team $teams = null;

    #[ORM\ManyToOne(inversedBy: 'battleTeam')]
 
    private ?Battle $battle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTeams(): ?Team
    {
        return $this->teams;
    }

    public function setTeams(?Team $teams): static
    {
        $this->teams = $teams;

        return $this;
    }

    public function getBattle(): ?Battle
    {
        return $this->battle;
    }

    public function setBattle(?Battle $battle): static
    {
        $this->battle = $battle;

        return $this;
    }
}
