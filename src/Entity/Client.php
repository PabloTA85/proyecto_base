<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "client")]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id_client;

    #[ORM\Column(type: "string", length: 255)]
    private string $first_name;

    #[ORM\Column(type: "string", length: 255)]
    private string $last_name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $registration_date;

    public function __construct()
    {
        $this->registration_date = new \DateTime();
    }

    // Getter y Setter para id_client
    public function getIdClient(): int
    {
        return $this->id_client;
    }

    // Getter y Setter para first_name
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }

    // Getter y Setter para last_name
    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }

    // Getter y Setter para email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // Getter y Setter para phone
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    // Getter y Setter para address
    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    // Getter y Setter para registration_date
    public function getRegistrationDate(): \DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;
        return $this;
    }
}
