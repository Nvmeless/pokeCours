<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getProfile", 'getBattleInfos'])]

    private ?string $name = null;

    #[ORM\Column]
    private ?int $max = 3;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Groups(["getProfile", 'getBattleInfos'])]
    #[ORM\ManyToMany(targetEntity: Pokemon::class, inversedBy: 'teams')]
    private Collection $monsters;


    #[ORM\ManyToOne(inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $master = null;

    #[ORM\OneToMany(mappedBy: 'teams', targetEntity: BattleTeam::class)]
    private Collection $battleTeams;

    public function __construct()
    {
        $this->monsters = new ArrayCollection();
        $this->battleTeams = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): static
    {
        $this->max = $max;

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

    /**
     * @return Collection<int, Pokemon>
     */
    public function getMonsters(): Collection
    {
        return $this->monsters;
    }

    public function addMonster(Pokemon $monster): static
    {
        // if($this->getMonsters()->count() <= $this->getMax()){
            if (!$this->monsters->contains($monster)) {
                $this->monsters->add($monster);
            }
        // }

        return $this;
    }

    public function removeMonster(Pokemon $monster): static
    {
        $this->monsters->removeElement($monster);

        return $this;
    }

    public function getMaster(): ?User
    {
        return $this->master;
    }

    public function setMaster(?User $master): static
    {
        $this->master = $master;

        return $this;
    }

    /**
     * @return Collection<int, BattleTeam>
     */
    public function getBattleTeams(): Collection
    {
        return $this->battleTeams;
    }

    public function addBattleTeam(BattleTeam $battleTeam): static
    {
        if (!$this->battleTeams->contains($battleTeam)) {
            $this->battleTeams->add($battleTeam);
            $battleTeam->setTeams($this);
        }

        return $this;
    }

    public function removeBattleTeam(BattleTeam $battleTeam): static
    {
        if ($this->battleTeams->removeElement($battleTeam)) {
            // set the owning side to null (unless already changed)
            if ($battleTeam->getTeams() === $this) {
                $battleTeam->setTeams(null);
            }
        }

        return $this;
    }
}
