<?php

namespace App\Entity;

use App\Repository\PokedexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: PokedexRepository::class)]
class Pokedex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllPokedex", "getBattleInfos"])]
    private ?int $id = null;
    
    //To Anonymise
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"une entree pokedex doit avoir un nom")]
    #[Assert\Length(
        min: 2,
        max: 5,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    #[Groups(["getAllPokedex", "getProfile", "getBattleInfos"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex", "getBattleInfos"])]
    private ?int $pv_min = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $pv_max = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $attack_min = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $attack_max = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $defense_min = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $defense_max = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $special_min = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $special_max = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $speed_min = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex"])]
    private ?int $speed_max = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'devolution_id')]
    // #[Groups(["getAllPokedex"])]
    private ?self $evolution_id = null;

    #[ORM\OneToMany(mappedBy: 'evolution_id', targetEntity: self::class)]
    // #[Groups(["getAllPokedex"])]
    private Collection $devolution_id;

    #[ORM\Column]
    #[Groups(["getAllPokedex", "getProfile", "getBattleInfos"])]
    private ?int $pokemon_id_first = null;

    #[ORM\Column]
    #[Groups(["getAllPokedex", "getProfile", "getBattleInfos"])]
    private ?int $pokemon_id_second = null;

    #[ORM\OneToMany(mappedBy: 'pokedex', targetEntity: Pokemon::class)]
    private Collection $pokemon;

    public function __construct()
    {
        $this->devolution_id = new ArrayCollection();
        $this->pokemon = new ArrayCollection();
    }

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
        if($name !== "Fury"){

            $this->name = $name;
        }
        else{
            $this->name = "Petit nom mignon";
        }
        return $this;
    }

    public function getPvMin(): ?int
    {
        return $this->pv_min;
    }

    public function setPvMin(int $pv_min): static
    {
        $this->pv_min = $pv_min;

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

    public function getAttackMin(): ?int
    {
        return $this->attack_min;
    }

    public function setAttackMin(int $attack_min): static
    {
        $this->attack_min = $attack_min;

        return $this;
    }

    public function getAttackMax(): ?int
    {
        return $this->attack_max;
    }

    public function setAttackMax(int $attack_max): static
    {
        $this->attack_max = $attack_max;

        return $this;
    }

    public function getDefenseMin(): ?int
    {
        return $this->defense_min;
    }

    public function setDefenseMin(int $defense_min): static
    {
        $this->defense_min = $defense_min;

        return $this;
    }

    public function getDefenseMax(): ?int
    {
        return $this->defense_max;
    }

    public function setDefenseMax(int $defense_max): static
    {
        $this->defense_max = $defense_max;

        return $this;
    }

    public function getSpecialMin(): ?int
    {
        return $this->special_min;
    }

    public function setSpecialMin(int $special_min): static
    {
        $this->special_min = $special_min;

        return $this;
    }

    public function getSpecialMax(): ?int
    {
        return $this->special_max;
    }

    public function setSpecialMax(int $special_max): static
    {
        $this->special_max = $special_max;

        return $this;
    }

    public function getSpeedMin(): ?int
    {
        return $this->speed_min;
    }

    public function setSpeedMin(int $speed_min): static
    {
        $this->speed_min = $speed_min;

        return $this;
    }

    public function getSpeedMax(): ?int
    {
        return $this->speed_max;
    }

    public function setSpeedMax(int $speed_max): static
    {
        $this->speed_max = $speed_max;

        return $this;
    }

    public function getEvolutionId(): ?self
    {
        return $this->evolution_id;
    }

    public function setEvolutionId(?self $evolution_id): static
    {
        $this->evolution_id = $evolution_id;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDevolutionId(): Collection
    {
        return $this->devolution_id;
    }

    public function addDevolutionId(self $devolutionId): static
    {
        if (!$this->devolution_id->contains($devolutionId)) {
            $this->devolution_id->add($devolutionId);
            $devolutionId->setEvolutionId($this);
        }

        return $this;
    }

    public function removeDevolutionId(self $devolutionId): static
    {
        if ($this->devolution_id->removeElement($devolutionId)) {
            // set the owning side to null (unless already changed)
            if ($devolutionId->getEvolutionId() === $this) {
                $devolutionId->setEvolutionId(null);
            }
        }

        return $this;
    }

    public function getPokemonIdFirst(): ?int
    {
        return $this->pokemon_id_first;
    }

    public function setPokemonIdFirst(int $pokemon_id_first): static
    {
        $this->pokemon_id_first = $pokemon_id_first;

        return $this;
    }

    public function getPokemonIdSecond(): ?int
    {
        return $this->pokemon_id_second;
    }

    public function setPokemonIdSecond(int $pokemon_id_second): static
    {
        $this->pokemon_id_second = $pokemon_id_second;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon->add($pokemon);
            $pokemon->setPokedex($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        if ($this->pokemon->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getPokedex() === $this) {
                $pokemon->setPokedex(null);
            }
        }

        return $this;
    }
}
