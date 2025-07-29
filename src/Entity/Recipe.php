<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    private ?string $ingredients = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getters and Setters for the properties
     * These methods allow access to the private properties of the class.
     */
    public function getTitle(): ?string{return $this->title;}
    public function getSlug(): ?string{return $this->slug;}
    public function getContent(): ?string {return $this->content;}
    public function getCreatedAt(): ?\DateTimeImmutable {return $this->createdAt;}
    public function getUpdatedAt(): ?\DateTimeImmutable {return $this->updatedAt;}

    public function setTitle(string $title): static {$this->title = $title; return $this;}
    public function setSlug(string $slug): static {$this->slug = $slug; return $this;}
    public function setCreatedAt(\DateTimeImmutable $createdAt): static {$this->createdAt = $createdAt; return $this;}

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static {$this->updatedAt = $updatedAt; return $this;}

    public function setContent(string $content): static {$this->content = $content; return $this;}

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }
}
