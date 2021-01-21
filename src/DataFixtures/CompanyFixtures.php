<?php

namespace App\DataFixtures;

use App\Entity\Company;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const REFERENCE_NAME = 'company';
    public const REFERENCE_NAME_1 = 'company1';
    public const REFERENCE_NAME_2 = 'company2';
    public const REFERENCE_NAME_3 = 'company3';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getCompanyData() as [$name, $address, $city, $email, $reference_name]) {
            $company = new Company();
            $company->setName($name)
                ->setAddress($address)
                ->setCity($city)
                ->setEmail($email)
                ->setCreatedAt(new DateTime());

            $this->addReference((string)$reference_name, $company);
            $manager->persist($company);
        }

        $manager->flush();
    }

    private function getCompanyData(): array
    {
        return [
            // $companyData = [$name, $address, $city, $email, $reference_name];
            ['RentCar AS', 'Torbali', 'IZMIR', 'info@rentcar.com', self::REFERENCE_NAME],
            ['Vera AS', 'Alsancak', 'IZMIR', 'info@vera.com', self::REFERENCE_NAME_1],
            ['Moonaly Rent a Car', 'Konyaalti', 'ANTALYA', 'info@moonaly.com', self::REFERENCE_NAME_2],
            ['S-Tour', 'Beylikduzu', 'ISTANBUL', 'info@stour.com', self::REFERENCE_NAME_3],
        ];
    }
}
