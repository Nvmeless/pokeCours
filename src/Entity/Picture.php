<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\PictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $realname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $realpath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $publicpath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $uploadDate = null;

    #[Vich\UploadableField(mapping: "pictures", fileNameProperty: "realpath")]
    private $file;

    #[ORM\OneToMany(mappedBy: 'sprite', targetEntity: Itemdex::class)]
    private Collection $itemdexes;

    public function __construct()
    {
        $this->itemdexes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealname(): ?string
    {
        return $this->realname;
    }

    public function setRealname(?string $realname): static
    {
        $this->realname = $realname;

        return $this;
    }

    public function getRealpath(): ?string
    {
        return $this->realpath;
    }

    public function setRealpath(?string $realpath): static
    {
        $this->realpath = $realpath;

        return $this;
    }

    public function getPublicpath(): ?string
    {
        return $this->publicpath;
    }

    public function setPublicpath(?string $publicpath): static
    {
        $this->publicpath = $publicpath;

        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(?string $mime): static
    {
        $this->mime = $mime;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->uploadDate;
    }

    public function setUploadDate(?\DateTimeInterface $uploadDate): static
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): ?Picture
    {
     
        $this->file = $file;
        return $this;
    }

    /**
     * @return Collection<int, Itemdex>
     */
    public function getItemdexes(): Collection
    {
        return $this->itemdexes;
    }

    public function addItemdex(Itemdex $itemdex): static
    {
        if (!$this->itemdexes->contains($itemdex)) {
            $this->itemdexes->add($itemdex);
            $itemdex->setSprite($this);
        }

        return $this;
    }

    public function removeItemdex(Itemdex $itemdex): static
    {
        if ($this->itemdexes->removeElement($itemdex)) {
            // set the owning side to null (unless already changed)
            if ($itemdex->getSprite() === $this) {
                $itemdex->setSprite(null);
            }
        }

        return $this;
    }
}
