<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity]
#[ORM\Table(name: "product")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id_product;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: "integer")]
    private int $stock;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: "product", targetEntity: ProductCategorie::class, cascade: ["remove"])]
    #[Ignore]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    // Añadir categoría a un producto
    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
        return $this;
    }

    // Eliminar categoría de un producto
    public function removeCategory(Categorie $category): self
    {
        $this->categories->removeElement($category);
        return $this;
    }
}
