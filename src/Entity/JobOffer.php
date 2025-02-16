<?php

namespace App\Entity;

use App\Interfaces\SluggerInterface;
use App\Repository\JobOfferRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer implements SluggerInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255)]
    private ?string $jobTitle = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobOfferType $jobType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jobLocation = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $closingAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $salary = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'jobOffer', orphanRemoval: true)]
    private Collection $applications;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    public function __construct(DateTimeImmutable $createdAt = new DateTimeImmutable())
    {
        $this->createdAt = $createdAt;
        $this->applications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobType(): ?JobOfferType
    {
        return $this->jobType;
    }

    public function setJobType(?JobOfferType $jobType): static
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getJobLocation(): ?string
    {
        return $this->jobLocation;
    }

    public function setJobLocation(?string $jobLocation): static
    {
        $this->jobLocation = $jobLocation;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getClosingAt(): ?\DateTimeImmutable
    {
        return $this->closingAt;
    }

    public function setClosingAt(?\DateTimeImmutable $closingAt): static
    {
        $this->closingAt = $closingAt;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(?int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setJobOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getJobOffer() === $this) {
                $application->setJobOffer(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
