<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_materiel','add_materiel','delete_materiel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_materiel', 'add_materiel','delete_materiel'])]
    #[Assert\NotBlank(message: 'Le nom du matériel est obligatoire')]
    #[assert\NotNull(message: 'Le nom du matériel est obligatoire')]
    #[assert\Length(min: 3, minMessage: 'Le nom du matériel doit contenir au moins {{ limit }} caractères')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['add_materiel'])]
    private ?int $type = null;

    #[ORM\Column]
    #[Groups(['get_materiel', 'add_materiel'])]
    private ?bool $available = null;

    #[ORM\Column]
    #[Groups(['add_materiel'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(['add_materiel','delete_materiel'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    #[Groups(['add_materiel','delete_materiel'])]
    private ?string $status = null;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
