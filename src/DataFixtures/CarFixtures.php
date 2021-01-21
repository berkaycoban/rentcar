<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    private const MANUEL = "Manuel";
    private const SEMI_AUTO = "Semi Automatic";
    private const AUTOMATIC = "Automatic";

    public function load(ObjectManager $manager)
    {
        $car = new Car();
        $car->setAirbag(true)
            ->setAirConditioning(true)
            ->setYear(2020)
            ->setSeats(5)
            ->setModel("A3")
            ->setBrand("Audi")
            ->setClass(3)
            ->setDailyMaxKm(300)
            ->setDailyPrice(1000)
            ->setFuel("Diesel")
            ->setGear(self::AUTOMATIC)
            ->setMinLicenseYear(3)
            ->setMinDriverAge(27)
            ->setKm(1000)
            ->setImgSrc('a3-2020.webp')
            ->setImgAlt('Audi A3 2020')
            ->setOwnerId($this->getReference(CompanyFixtures::REFERANCE_NAME))
            ->setCreatedAt(new \DateTime());

        $manager->persist($car);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            CompanyFixtures::class,
        );
    }
}
