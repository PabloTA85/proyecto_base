<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "productcategorie")]
class ProductCategorie
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "categories")]
    #[ORM\JoinColumn(name: "id_product", referencedColumnName: "id_product", nullable: false)]
    private Product $product;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: "products")]
    #[ORM\JoinColumn(name: "id_category", referencedColumnName: "id_category", nullable: false)]
    private Categorie $category;

    // Getter para Product
    public function getProduct(): Product
    {
        return $this->product;
    }

    // Setter para Product
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    // Getter para Categorie
    public function getCategory(): Categorie
    {
        return $this->category;
    }

    // Setter para Categorie
    public function setCategory(Categorie $category): self
    {
        $this->category = $category;
        return $this;
    }
}
