<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id_order;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "orders")]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client", nullable: false)]
    private Client $client;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $order_date;

    #[ORM\Column(type: "string", length: 20, options: ["default" => "Pending"])]
    private string $status = "Pending";

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $total;

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderDetail::class, cascade: ["persist", "remove"])]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->order_date = new \DateTime();
        $this->orderDetails = new ArrayCollection();
    }


    // Getter para id_order
    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    // Getter para client
    public function getClient(): Client
    {
        return $this->client;
    }

    // Setter para client
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    // Getter para order_date
    public function getOrderDate(): \DateTimeInterface
    {
        return $this->order_date;
    }

    // Setter para order_date
    public function setOrderDate(\DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;
        return $this;
    }

    // Getter para status
    public function getStatus(): string
    {
        return $this->status;
    }

    // Setter para status
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    // Getter para total
    public function getTotal(): float
    {
        return $this->total;
    }

    // Setter para total
    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }
}
