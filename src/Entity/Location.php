<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use App\Service\CalendarioLaboralBankHolidayFinder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'locations')]
    private $country;

    public function __construct(Country $country, string $name)
    {
        $this->country = $country;
        $this->name = $name;
        $this->holidays = new ArrayCollection();
    }
    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Holiday::class)]
    private Collection $holidays;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getHolidays(): Collection
    {
        return $this->holidays;
    }

    public function setHolidays(Collection $holidays): self
    {
        $this->holidays = $holidays;

        return $this;
    }
}
