<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'email', type: 'string', length: 150,)]
    #[Assert\Email]
    #[Assert\Length(max: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150)]
    private ?string $last_name = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150)]
    private ?string $first_name = null;

    #[ORM\Column]
    #[Assert\Length(max: 13)]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 5)]
    #[Assert\Length(max: 5)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(max: 100)]
    private ?string $city = null;

    #[ORM\Column]
    private ?bool $buyer = null;

    #[ORM\Column]
    private ?bool $seller = null;

    #[ORM\Column]
    private ?bool $just_want_info = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(max: 5000)]
    private ?string $message = null;

    #[ORM\ManyToMany(targetEntity: Mobilhome::class, inversedBy: 'contacts')]
    private Collection $ContactMobilhome;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: Image::class)]
    private Collection $image;

    #[ORM\Column(type:"datetime_immutable",options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->ContactMobilhome = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    // GETTER/SETTER
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function isBuyer(): ?bool
    {
        return $this->buyer;
    }

    public function setBuyer(bool $buyer): static
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function isSeller(): ?bool
    {
        return $this->seller;
    }

    public function setSeller(bool $seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    public function isJustWantInfo(): ?bool
    {
        return $this->just_want_info;
    }

    public function setJustWantInfo(bool $just_want_info): static
    {
        $this->just_want_info = $just_want_info;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection<int, Mobilhome>
     */
    public function getContactMobilhome(): Collection
    {
        return $this->ContactMobilhome;
    }

    public function addContactMobilhome(Mobilhome $contactMobilhome): static
    {
        if (!$this->ContactMobilhome->contains($contactMobilhome)) {
            $this->ContactMobilhome->add($contactMobilhome);
        }

        return $this;
    }

    public function removeContactMobilhome(Mobilhome $contactMobilhome): static
    {
        $this->ContactMobilhome->removeElement($contactMobilhome);

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setContact($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getContact() === $this) {
                $image->setContact(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
