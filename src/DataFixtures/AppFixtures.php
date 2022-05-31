<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use App\Entity\PhoneBook;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
            $phoneBook = new PhoneBook();
            $phoneBook->setFirstName('Asick');
            $phoneBook->setLastName('Ahamed');
            $phoneBook->setAddress('test Address');
            $phoneBook->setPhoneNumber(9999);
            $phoneBook->setBirthday('01.01.1991');
            $phoneBook->setEmail('test@ssss.com');
            $manager->persist($phoneBook);
            $manager->flush();
    }
}
