<?php

namespace App\Entity;

use App\Repository\ItemsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemsRepository::class)]
class Items
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Itemdex $itemdex = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Inventory $inventory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemdex(): ?Itemdex
    {
        return $this->itemdex;
    }

    public function setItemdex(?Itemdex $itemdex): static
    {
        $this->itemdex = $itemdex;

        return $this;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }
}
