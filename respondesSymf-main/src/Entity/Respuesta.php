<?php

namespace App\Entity;

use App\Repository\RespuestaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RespuestaRepository::class)]
class Respuesta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'respuestas')]
    #[ORM\JoinColumn(name:"user_id_id", referencedColumnName:"id", nullable:false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: Pregunta::class, inversedBy: 'respuestas')]
    #[ORM\JoinColumn(name:"pregunta_id_id", referencedColumnName:"id", nullable:false)]
    private ?Pregunta $pregunta_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaRespuesta = null;

    #[ORM\Column(length: 5)]
    private ?string $opcElegida = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPreguntaId(): ?Pregunta
    {
        return $this->pregunta_id;
    }

    public function setPreguntaId(?Pregunta $pregunta_id): static
    {
        $this->pregunta_id = $pregunta_id;

        return $this;
    }

    public function getFechaRespuesta(): ?\DateTimeInterface
    {
        return $this->fechaRespuesta;
    }

    public function setFechaRespuesta(\DateTimeInterface $fechaRespuesta): static
    {
        $this->fechaRespuesta = $fechaRespuesta;

        return $this;
    }

    public function getOpcElegida(): ?string
    {
        return $this->opcElegida;
    }

    public function setOpcElegida(string $opcElegida): static
    {
        $this->opcElegida = $opcElegida;

        return $this;
    }

   
}
