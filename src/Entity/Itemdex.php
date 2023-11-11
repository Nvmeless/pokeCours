<?php

namespace App\Entity;

use App\Repository\ItemdexRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemdexRepository::class)]
class Itemdex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $is_money = null;

    #[ORM\Column]
    private ?bool $is_infinite = null;

    #[ORM\ManyToOne(inversedBy: 'itemdexes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Picture $sprite = null;

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

    public function isIsMoney(): ?bool
    {
        return $this->is_money;
    }

    public function setIsMoney(bool $is_money): static
    {
        $this->is_money = $is_money;

        return $this;
    }

    public function isIsInfinite(): ?bool
    {
        return $this->is_infinite;
    }

    public function setIsInfinite(bool $is_infinite): static
    {
        $this->is_infinite = $is_infinite;

        return $this;
    }

    public function getSprite(): ?Picture
    {
        return $this->sprite;
    }

    public function setSprite(?Picture $sprite): static
    {
        $this->sprite = $sprite;

        return $this;
    }
}
