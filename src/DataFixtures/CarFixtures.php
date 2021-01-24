<?php

namespace App\DataFixtures;

use App\Entity\Car;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    private const MANUEL = "Manuel";
    private const SEMI_AUTO = "Semi Automatic";
    private const AUTOMATIC = "Automatic";
    private const ECONOMIC_CLASS = "Economic";
    private const COMFORT_CLASS = "Comfort";
    private const PREMIUM_CLASS = "Premium";
    private const SUV_CLASS = "Suv";

    public function load(ObjectManager $manager)
    {
        foreach ($this->getCarData() as [$brand, $model, $year, $fuel, $gear, $class, $seats, $km, $daily_price, $daily_max_km, $min_license_year, $min_driver_age, $src, $alt, $company, $reference_name]) {
            $car = new Car();
            $car->setBrand($brand)
                ->setModel($model)
                ->setYear($year)
                ->setFuel($fuel)
                ->setGear($gear)
                ->setClass($class)
                ->setSeats($seats)
                ->setKm($km)
                ->setDailyPrice($daily_price)
                ->setDailyMaxKm($daily_max_km)
                ->setMinLicenseYear($min_license_year)
                ->setMinDriverAge($min_driver_age)
                ->setImgSrc($src)
                ->setImgAlt($alt)
                ->setAirbag(1)
                ->setAirConditioning(1)
                ->setAvailable(1)
                ->setOwnerId($this->getReference((string)$company))
                ->setCreatedAt(new DateTime())
            ;

            if($reference_name){
                $this->addReference((string)$reference_name, $car);
            }

            $manager->persist($car);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            CompanyFixtures::class,
        );
    }

    private function getCarData(): array
    {
        return [
            // $carData = [$brand, $model, $year, $fuel, $gear, $class, $seats, $km, $daily_price, $daily_max_km, $min_license_year, $min_driver_age, $src, $alt, $company, $reference_name];
            ['Renault', 'Clio 1.5', '2019', 'Diesel', self::MANUEL, self::ECONOMIC_CLASS, 4, 1000, 300, 300, 1, 20, 'clio.png', 'Clio', CompanyFixtures::REFERENCE_NAME, 'car3'],
            ['Fiat', 'Egea 1.3', '2020', 'Gasoline', self::MANUEL, self::ECONOMIC_CLASS, 4, 2000, 400, 300, 1, 20, 'egea.png', 'Egea', CompanyFixtures::REFERENCE_NAME_1, 'car4'],
            ['Ford', 'Focus 1.6', '2019', 'Gas & LPG', self::SEMI_AUTO, self::COMFORT_CLASS, 4, 1000, 500, 300, 1, 20, 'focus.png', 'Focus', CompanyFixtures::REFERENCE_NAME_2, ''],
            ['Toyota', 'Corolla Hybrid', '2020', 'Hybrid', self::AUTOMATIC, self::COMFORT_CLASS, 4, 1000, 500, 300, 1, 20, 'corolla.png', 'Corolla', CompanyFixtures::REFERENCE_NAME_3, ''],
            ['Opel', 'Insignia', '2020', 'Gasoline', self::MANUEL, self::COMFORT_CLASS, 5, 200, 600, 400, 2, 21, 'insignia.png', 'Insignia', CompanyFixtures::REFERENCE_NAME, ''],
            ['BMW', '320i', '2020', 'Diesel', self::SEMI_AUTO, self::PREMIUM_CLASS, 4, 100, 700, 300, 2, 21, '320i.png', '320', CompanyFixtures::REFERENCE_NAME, 'car1'],
            ['Volvo', 'S90', '2019', 'Gas & LPG', self::AUTOMATIC, self::PREMIUM_CLASS, 4, 2000, 700, 400, 2, 22, 's90.png', 'S90',CompanyFixtures::REFERENCE_NAME, 'car2'],
            ['Ford', 'Kuga 1.5', '2020', 'Gas & LPG', self::MANUEL, self::SUV_CLASS, 5, 100, 700, 400, 2, 22, 'kuga.png', 'Kuga', CompanyFixtures::REFERENCE_NAME, ''],
            ['Volvo', 'XC90', '2019', 'Diesel', self::SEMI_AUTO, self::SUV_CLASS, 4, 1000, 800, 500, 2, 23, 'xc90.png', 'XC90', CompanyFixtures::REFERENCE_NAME, ''],
        ];
    }
}
