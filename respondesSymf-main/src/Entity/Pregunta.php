<?php 

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Pregunta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El enunciado es obligatorio.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'El enunciado no puede superar los {{ limit }} caracteres.'
    )]
    private ?string $enunciado = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La opción A es obligatoria.')]
    private ?string $a = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La opción B es obligatoria.')]
    private ?string $b = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La opción C no puede superar los {{ limit }} caracteres.'
    )]
    private ?string $c = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La opción D no puede superar los {{ limit }} caracteres.'
    )]
    private ?string $d = null;

    #[ORM\Column(length: 1)]
    #[Assert\NotBlank(message: 'Debes seleccionar una opción correcta.')]
    #[Assert\Choice(
        choices: ['a', 'b', 'c', 'd'],
        message: 'La opción correcta debe ser una de las siguientes: A, B, C o D.'
    )]
    private ?string $oCorrecta = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTimeInterface::class, message: 'La fecha de inicio debe ser válida.')]
    #[Assert\LessThan(propertyPath: 'fFin', message: 'La fecha de inicio debe ser anterior a la fecha de fin.')]
    private ?\DateTimeInterface $fInicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTimeInterface::class, message: 'La fecha de fin debe ser válida.')]
    #[Assert\GreaterThanOrEqual("today", message: 'La fecha de fin debe ser en el futuro.')]
    private ?\DateTimeInterface $fFin = null;


    
    /**
     * @var Collection<int, Respuesta>
     */
    #[ORM\OneToMany(targetEntity: Respuesta::class, mappedBy: 'pregunta_id', orphanRemoval: true)]
    private Collection $respuestas;

    #[ORM\Column]
    private bool $activa = false;

    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnunciado(): ?string
    {
        return $this->enunciado;
    }

    public function setEnunciado(string $enunciado): static
    {
        $this->enunciado = $enunciado;

        return $this;
    }

    public function getA(): ?string
    {
        return $this->a;
    }

    public function setA(string $a): static
    {
        $this->a = $a;

        return $this;
    }

    public function getB(): ?string
    {
        return $this->b;
    }

    public function setB(string $b): static
    {
        $this->b = $b;

        return $this;
    }

    public function getC(): ?string
    {
        return $this->c;
    }

    public function setC(?string $c): static
    {
        $this->c = $c;

        return $this;
    }

    public function getD(): ?string
    {
        return $this->d;
    }

    public function setD(?string $d): static
    {
        $this->d = $d;

        return $this;
    }

    public function getOCorrecta(): ?string
    {
        return $this->oCorrecta;
    }

    public function setOCorrecta(string $oCorrecta): static
    {
        $this->oCorrecta = $oCorrecta;

        return $this;
    }

    public function getFInicio(): ?\DateTimeInterface
    {
        return $this->fInicio;
    }

    public function setFInicio(?\DateTimeInterface $fInicio): static
    {
        $this->fInicio = $fInicio;

        return $this;
    }

    public function getFFin(): ?\DateTimeInterface
    {
        return $this->fFin;
    }

    public function setFFin(?\DateTimeInterface $fFin): static
    {
        $this->fFin = $fFin;

        return $this;
    }

    /**
     * @return Collection<int, Respuesta>
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Respuesta $respuesta): static
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas->add($respuesta);
            $respuesta->setPreguntaId($this);
        }

        return $this;
    }

    public function removeRespuesta(Respuesta $respuesta): static
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getPreguntaId() === $this) {
                $respuesta->setPreguntaId(null);
            }
        }

        return $this;
    }

    public function getActiva(): bool
    {
        return $this->activa;
    }

    public function setActiva(bool $activa): self
    {
        $this->activa = $activa;

        return $this;
    }
}
