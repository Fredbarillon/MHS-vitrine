<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max:255)]
    private ?string $file_name = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 1, max: 7)]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Mobilhome $mobilhome = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Contact $contact = null;

// GETTER/SETTER
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getMobilhome(): ?Mobilhome
    {
        return $this->mobilhome;
    }

    public function setMobilhome(?Mobilhome $mobilhome): static
    {
        $this->mobilhome = $mobilhome;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

}
