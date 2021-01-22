<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $fuel;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $gear;

    /**
     * @ORM\Column(type="boolean")
     */
    private $airbag;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $luggage_volume;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $seats;

    /**
     * @ORM\Column(type="boolean")
     */
    private $air_conditioning;

    /**
     * @ORM\Column(type="integer")
     */
    private $min_license_year;

    /**
     * @ORM\Column(type="integer")
     */
    private $min_driver_age;

    /**
     * @ORM\Column(type="integer")
     */
    private $km;

    /**
     * @ORM\Column(type="integer")
     */
    private $daily_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $daily_max_km;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_src;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img_alt;

    /**
     * @ORM\Column(type="string", nullable=true, length=40)
     */
    private $class;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="car_id")
     */
    private $transactions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getGear(): ?string
    {
        return $this->gear;
    }

    public function setGear(string $gear): self
    {
        $this->gear = $gear;

        return $this;
    }

    public function getAirbag(): ?bool
    {
        return $this->airbag;
    }

    public function setAirbag(bool $airbag): self
    {
        $this->airbag = $airbag;

        return $this;
    }

    public function getLuggageVolume(): ?int
    {
        return $this->luggage_volume;
    }

    public function setLuggageVolume(?int $luggage_volume): self
    {
        $this->luggage_volume = $luggage_volume;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(?int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    public function getAirConditioning(): ?bool
    {
        return $this->air_conditioning;
    }

    public function setAirConditioning(bool $air_conditioning): self
    {
        $this->air_conditioning = $air_conditioning;

        return $this;
    }

    public function getMinLicenseYear(): ?int
    {
        return $this->min_license_year;
    }

    public function setMinLicenseYear(int $min_license_year): self
    {
        $this->min_license_year = $min_license_year;

        return $this;
    }

    public function getMinDriverAge(): ?int
    {
        return $this->min_driver_age;
    }

    public function setMinDriverAge(int $min_driver_age): self
    {
        $this->min_driver_age = $min_driver_age;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getDailyPrice(): ?int
    {
        return $this->daily_price;
    }

    public function setDailyPrice(int $daily_price): self
    {
        $this->daily_price = $daily_price;

        return $this;
    }

    public function getDailyMaxKm(): ?int
    {
        return $this->daily_max_km;
    }

    public function setDailyMaxKm(int $daily_max_km): self
    {
        $this->daily_max_km = $daily_max_km;

        return $this;
    }

    public function getImgSrc(): ?string
    {
        return $this->img_src;
    }

    public function setImgSrc(?string $img_src): self
    {
        $this->img_src = $img_src;

        return $this;
    }

    public function getImgAlt(): ?string
    {
        return $this->img_alt;
    }

    public function setImgAlt(string $img_alt): self
    {
        $this->img_alt = $img_alt;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getOwnerId(): ?Company
    {
        return $this->owner_id;
    }

    public function setOwnerId(?Company $owner_id): self
    {
        $this->owner_id = $owner_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCarId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCarId() === $this) {
                $transaction->setCarId(null);
            }
        }

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function __toString()
    {
        return $this->getBrand().' '.$this->getModel().' '.$this->getYear();
    }
}
