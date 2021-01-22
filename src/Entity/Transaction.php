<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pickup_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $pickup_car_km;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $return_date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $return_car_km;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarId(): ?Car
    {
        return $this->car_id;
    }

    public function setCarId(?Car $car_id): self
    {
        $this->car_id = $car_id;

        return $this;
    }

    public function getCustomerId(): ?User
    {
        return $this->customer_id;
    }

    public function setCustomerId(?User $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getPickupDate(): ?\DateTimeInterface
    {
        return $this->pickup_date;
    }

    public function setPickupDate(\DateTimeInterface $pickup_date): self
    {
        $this->pickup_date = $pickup_date;

        return $this;
    }

    public function getPickupCarKm(): ?int
    {
        return $this->pickup_car_km;
    }

    public function setPickupCarKm(int $pickup_car_km): self
    {
        $this->pickup_car_km = $pickup_car_km;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(?\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getReturnCarKm(): ?int
    {
        return $this->return_car_km;
    }

    public function setReturnCarKm(?int $return_car_km): self
    {
        $this->return_car_km = $return_car_km;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
