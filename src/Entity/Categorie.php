<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "categorie")]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id_category;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    // Getter para id_category
    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    // Getter para name
    public function getName(): string
    {
        return $this->name;
    }

    // Setter para name
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
