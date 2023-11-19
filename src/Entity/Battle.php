<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BattleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BattleRepository::class)]
class Battle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\OneToMany(mappedBy: 'battle', targetEntity: BattleTeam::class)]
    #[Groups(["getBattleInfos"])]
    private Collection $battleTeam;

    #[Groups(["getBattleInfos"])]
    #[ORM\OneToOne(inversedBy: 'battle', cascade: ['persist', 'remove'])]
    private ?BattleTeam $winner = null;

    #[ORM\OneToOne(inversedBy: 'battle', cascade: ['persist', 'remove'])]
    private ?BattleTeam $looser = null;

    #[Groups(["getBattleInfos"])]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function __construct()
    {
        $this->battleTeam = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BattleTeam>
     */
    public function getBattleTeam(): Collection
    {
        return $this->battleTeam;
    }

    public function addBattleTeam(BattleTeam $battleTeam): static
    {
        if (!$this->battleTeam->contains($battleTeam)) {
            $this->battleTeam->add($battleTeam);
            $battleTeam->setBattle($this);
        }

        return $this;
    }

    public function removeBattleTeam(BattleTeam $battleTeam): static
    {
        if ($this->battleTeam->removeElement($battleTeam)) {
            // set the owning side to null (unless already changed)
            if ($battleTeam->getBattle() === $this) {
                $battleTeam->setBattle(null);
            }
        }

        return $this;
    }

    public function getWinner(): ?BattleTeam
    {
        return $this->winner;
    }

    public function setWinner(?BattleTeam $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLooser(): ?BattleTeam
    {
        return $this->looser;
    }

    public function setLooser(?BattleTeam $looser): static
    {
        $this->looser = $looser;

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
}
