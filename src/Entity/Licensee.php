<?php

namespace App\Entity;

use App\Repository\LicenseeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LicenseeRepository::class)]
class Licensee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "licensees")]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    #[ORM\Column(type: "GenderType")]
    private $gender;

    #[ORM\Column(type: "string", length: 255)]
    private $lastname;

    #[ORM\Column(type: "string", length: 255)]
    private $firstname;

    #[ORM\Column(type: "date")]
    private $birthdate;

    #[ORM\Column(type: "string", length: 7, unique: true, nullable: true)]
    #[Assert\Length(min: 7, max: 7)]
    private $fftaMemberCode;

    #[ORM\Column(type: "integer", unique: true, nullable: true)]
    private $fftaId;

    #[
        ORM\OneToMany(
            mappedBy: "licensee",
            targetEntity: License::class,
            orphanRemoval: true
        )
    ]
    private $licenses;

    #[
        ORM\OneToMany(
            mappedBy: "owner",
            targetEntity: Bow::class,
            orphanRemoval: true
        )
    ]
    private $bows;

    #[
        ORM\OneToMany(
            mappedBy: "owner",
            targetEntity: Arrow::class,
            orphanRemoval: true
        )
    ]
    private $arrows;

    #[
        ORM\OneToMany(
            mappedBy: "participant",
            targetEntity: EventParticipation::class,
            orphanRemoval: true
        )
    ]
    private $eventParticipations;

    #[
        ORM\OneToMany(
            mappedBy: "licensee",
            targetEntity: Result::class,
            orphanRemoval: true
        )
    ]
    private $results;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: "licensees")]
    private $groups;

    public function __construct()
    {
        $this->arrows = new ArrayCollection();
        $this->bows = new ArrayCollection();
        $this->licenses = new ArrayCollection();
        $this->eventParticipations = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFullname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFullname(): string
    {
        return sprintf("%s %s", $this->getFirstname(), $this->getLastname());
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function getAge(): int
    {
        return $this->getBirthdate()->diff(new DateTime())->y;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getFftaId(): ?int
    {
        return $this->fftaId;
    }

    public function setFftaId(?int $fftaId): self
    {
        $this->fftaId = $fftaId;

        return $this;
    }

    public function getFftaMemberCode(): ?string
    {
        return $this->fftaMemberCode;
    }

    public function setFftaMemberCode(?string $fftaMemberCode): self
    {
        $this->fftaMemberCode = $fftaMemberCode;

        return $this;
    }

    /**
     * @return Collection<int, License>
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function getLicenseForSeason(int $year): ?License
    {
        $filteredLicenses = $this->licenses->filter(
            fn(License $l) => $l->getSeason() === $year
        );
        return $filteredLicenses->count() > 0
            ? $filteredLicenses->first()
            : null;
    }

    public function addLicense(License $license): self
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses[] = $license;
            $license->setLicensee($this);
        }

        return $this;
    }

    public function removeLicense(License $license): self
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getLicensee() === $this) {
                $license->setLicensee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bow>
     */
    public function getBows(): Collection
    {
        return $this->bows;
    }

    public function addBow(Bow $bow): self
    {
        if (!$this->bows->contains($bow)) {
            $this->bows[] = $bow;
            $bow->setOwner($this);
        }

        return $this;
    }

    public function removeBow(Bow $bow): self
    {
        if ($this->bows->removeElement($bow)) {
            // set the owning side to null (unless already changed)
            if ($bow->getOwner() === $this) {
                $bow->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Arrow>
     */
    public function getArrows(): Collection
    {
        return $this->arrows;
    }

    public function addArrow(Arrow $arrow): self
    {
        if (!$this->arrows->contains($arrow)) {
            $this->arrows[] = $arrow;
            $arrow->setOwner($this);
        }

        return $this;
    }

    public function removeArrow(Arrow $arrow): self
    {
        if ($this->arrows->removeElement($arrow)) {
            // set the owning side to null (unless already changed)
            if ($arrow->getOwner() === $this) {
                $arrow->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventParticipation>
     */
    public function getEventParticipations(): Collection
    {
        return $this->eventParticipations;
    }

    public function addEventParticipation(
        EventParticipation $eventParticipation
    ): self {
        if (!$this->eventParticipations->contains($eventParticipation)) {
            $this->eventParticipations[] = $eventParticipation;
            $eventParticipation->setParticipant($this);
        }

        return $this;
    }

    public function removeEventParticipation(
        EventParticipation $eventParticipation
    ): self {
        if ($this->eventParticipations->removeElement($eventParticipation)) {
            // set the owning side to null (unless already changed)
            if ($eventParticipation->getParticipant() === $this) {
                $eventParticipation->setParticipant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setLicensee($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getLicensee() === $this) {
                $result->setLicensee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addLicensee($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeLicensee($this);
        }

        return $this;
    }
}
