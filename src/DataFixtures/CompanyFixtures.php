<?php

namespace App\DataFixtures;

use App\Entity\Company;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const REFERANCE_NAME = "company";

    public function load(ObjectManager $manager)
    {
        $company = new Company();

        $company->setName("RentCar AS")
            ->setAddress("Torbali")
            ->setCity("IZMIR")
            ->setEmail("info@rentcar.com")
            ->setCreatedAt(new DateTime());

        $manager->persist($company);
        $manager->flush();

        $this->addReference(self::REFERANCE_NAME, $company);
    }
}
