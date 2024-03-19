<?php

namespace App\Entity;

use App\Repository\MobilhomeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MobilhomeRepository::class)]
class Mobilhome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Length(max: 4)]
    private ?float $length = null;

    #[ORM\Column (type: 'float', nullable: true)]
    #[Assert\Length(max: 4)]
    private ?float $width = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150)]
    private ?string $brand = null;

    #[ORM\Column(length: 150)]
    #[Assert\Length(max: 150)]
    private ?string $model = null;

    #[ORM\Column]
    #[Assert\Range(min: 1980, max: 2050)]
    private ?int $year = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 8)]
    private ?int $nb_bedroom = null;

    #[ORM\Column]
    #[Assert\Length(max: 6)]
    private ?int $price = null;

    #[ORM\Column]
    private ?bool $central_livingroom = null;

    #[ORM\Column]
    private ?bool $oven = null;

    #[ORM\Column]
    private ?bool $AC = null;

    #[ORM\Column]
    private ?bool $double_glazing = null;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    #[Assert\Length(max: 2)]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $slug = null;
    // for referencing to have this slug as url

    #[ORM\Column(type:"datetime_immutable",options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(max: 1000)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $salesArguments = null;

    #[ORM\ManyToOne(inversedBy: 'mobilhome')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Boss $boss = null;

    #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'ContactMobilhome')]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'mobilhome', targetEntity: Image::class,)]
    private Collection $images;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $youtubeLink = null;

    #[ORM\Column(nullable: true)]
    private ?bool $CoupDeCoeur = false;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }



// GETTER/SETTER
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getNbBedroom(): ?int
    {
        return $this->nb_bedroom;
    }

    public function setNbBedroom(int $nb_bedroom): static
    {
        $this->nb_bedroom = $nb_bedroom;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isCentralLivingroom(): ?bool
    {
        return $this->central_livingroom;
    }

    public function setCentralLivingroom(bool $central_livingroom): static
    {
        $this->central_livingroom = $central_livingroom;

        return $this;
    }

    public function isOven(): ?bool
    {
        return $this->oven;
    }

    public function setOven(bool $oven): static
    {
        $this->oven = $oven;

        return $this;
    }

    public function isAC(): ?bool
    {
        return $this->AC;
    }

    public function setAC(bool $AC): static
    {
        $this->AC = $AC;

        return $this;
    }

    public function isDoubleGlazing(): ?bool
    {
        return $this->double_glazing;
    }

    public function setDoubleGlazing(bool $double_glazing): static
    {
        $this->double_glazing = $double_glazing;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    // getting the surface of a mobilhome
    public function getSurface(): ?float
    {
        $surface= $this->getLength() * $this->getWidth();
        $roundedSurface = ceil($surface);
    
        return $roundedSurface;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSalesArguments(): ?string
    {
        return $this->salesArguments;
    }

    public function setSalesArguments(?string $salesArguments): static
    {
        $this->salesArguments = $salesArguments;

        return $this;
    }

    public function getBoss(): ?Boss
    {
        return $this->boss;
    }

    public function setBoss(?Boss $boss): static
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->addContactMobilhome($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            $contact->removeContactMobilhome($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImages(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setMobilhome($this);
        }

        return $this;
    }

    public function removeImages(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMobilhome() === $this) {
                $image->setMobilhome(null);
            }
        }

        return $this;
    }

    public function getYoutubeLink(): ?string
    {
        return $this->youtubeLink;
    }

    public function setYoutubeLink(?string $youtubeLink): static
    {
        $this->youtubeLink = $youtubeLink;

        return $this;
    }

    public function isCoupDeCoeur(): ?bool
    {
        return $this->CoupDeCoeur;
    }

    public function setCoupDeCoeur(?bool $CoupDeCoeur): static
    {
        $this->CoupDeCoeur = $CoupDeCoeur;

        return $this;
    }

    
}
