<?php

namespace App\Repository;

use App\Entity\PhoneBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhoneBook>
 *
 * @method PhoneBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneBook[]    findAll()
 * @method PhoneBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneBook::class);
    }

   
    /**
     * savePhoneBook is a function store the customer details to the phone_book table 
    */
    public function savePhoneBook($firstName, $lastName,$address, $phoneNumber,$birthday, $email)
    {
        $newCustomer = new PhoneBook();

        $newCustomer
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setAddress($address)
            ->setPhoneNumber($phoneNumber)
            ->setBirthday($birthday)
            ->setEmail($email);
        $this->getEntityManager()->persist($newCustomer);
        $this->getEntityManager()->flush();
    }

    /**
    * editPhoneBook is a function update the customer details in the phone_book table 
    */

    public function editPhoneBook(PhoneBook $phoneBook): PhoneBook
    {
        $this->getEntityManager()->persist($phoneBook);
        $this->getEntityManager()->flush();

        return $phoneBook;
    }

    /**
    * removeCustomer is a function delete the customer record in the phone_book table 
    */

    public function removeCustomer(PhoneBook $phoneBook)
    {
        $this->getEntityManager()->remove($phoneBook);
        $this->getEntityManager()->flush();
    }

    /**
    * searchByName is a function get the customer record in the phone_book table 
    * order by recent record
    */

    public function searchByName($value): array
    {
        return $this->createQueryBuilder('phonebook')
            ->andWhere('phonebook.firstName LIKE :firstname')
            ->setParameter('firstname', $value)
            ->orWhere('phonebook.lastName LIKE :lastname')
            ->setParameter('lastname', $value)
            ->orderBy('phonebook.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
