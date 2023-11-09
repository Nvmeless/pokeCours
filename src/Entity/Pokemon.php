<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllPokedex"])]

    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]

    private ?int $pv_max = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]

    private ?int $pv = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]

    private ?int $level = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    #[Groups(["getAllPokedex"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokedex $pokedex = null;

    #[ORM\ManyToOne(inversedBy: 'pokemon')]
    private ?User $master = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPvMax(): ?int
    {
        return $this->pv_max;
    }

    public function setPvMax(int $pv_max): static
    {
        $this->pv_max = $pv_max;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getPokedex(): ?Pokedex
    {
        return $this->pokedex;
    }

    public function setPokedex(?Pokedex $pokedex): static
    {
        $this->pokedex = $pokedex;

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
}
