<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

//PhoneBook Entity
use App\Entity\PhoneBook;
// PhoneBook Repository
use App\Repository\PhoneBookRepository;

class PhoneBookController extends AbstractController
{
    
    private $phoneBookRepository;

    public function __construct(PhoneBookRepository $phoneBookRepository)
    {
        $this->phoneBookRepository = $phoneBookRepository;
    }
     /**
     * Fetching the phone book details of the all customers
     * route url: /api/phonebook/list
     * method : GET
     */
    
    #[Route('/api/phonebook/list', name: 'app_phone_book_list', methods: 'GET')]
    public function listCustomerPhoneBook(): Response
    {
        $phoneBookList = $this->phoneBookRepository->findAll();
        $phoneBookdata = [];
        foreach ($phoneBookList as $phoneBook) {
            $phoneBookdata[] = [
                'id' => $phoneBook->getId(),
                'firstName' => $phoneBook->getFirstName(),
                'lastName' => $phoneBook->getLastName(),
                'address' => $phoneBook->getAddress(),
                'email' => $phoneBook->getEmail(),
                'birthday' => $phoneBook->getBirthday(),
                'phoneNumber' => $phoneBook->getPhoneNumber(),
                'createdAt' => $phoneBook->getCreatedAt(), 
            ];
        }
        return $this->json($phoneBookdata, Response::HTTP_OK);
    }

    /**
     * add new user information to the phone book
     * route url:/api/phonebook/add
     * method : POST
     */
    #[Route('/api/phonebook/add', name: 'app_phone_book_add', methods: 'POST' )]
    public function addCustomerPhoneBook(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $address = $data['address'];
        $email = $data['email'];
        $birthday = $data['birthday'];
        $phoneNumber = $data['phoneNumber'];

        if (empty($firstName) || empty($lastName) || empty($address) || empty($email) || empty($birthday) || empty($phoneNumber)) {
            throw new NotFoundHttpException('Expecting mandatory post parameters!');
        }
        $this->phoneBookRepository->savePhoneBook($firstName,$lastName,$address, $phoneNumber,$birthday , $email);
        return $this->json(['status' => 'Customer details created!'], Response::HTTP_CREATED);
    }

    /**
     * phone book update function
     * route url: /api/phonebook/update/{id}
     * method : PUT
     */
    #[Route('/api/phonebook/update/{id}', name: 'app_phone_book_update', methods: 'PUT')]
    public function editCustomerPhoneBook($id,Request $request): Response
    {
    $phoneBook = $this->phoneBookRepository->findOneBy(['id' => $id]);  
    $data = json_decode($request->getContent(), true);

    empty($data['firstName']) ? true : $phoneBook->setFirstName($data['firstName']);
    empty($data['lastName']) ? true : $phoneBook->setLastName($data['lastName']);
    empty($data['address']) ? true : $phoneBook->setAddress($data['address']);
    empty($data['email']) ? true : $phoneBook->setEmail($data['email']);
    empty($data['birthday']) ? true : $phoneBook->setBirthday($data['birthday']);
    empty($data['phoneNumber']) ? true : $phoneBook->setPhoneNumber($data['phoneNumber']);
    $updatedPhoneBook = $this->phoneBookRepository->editPhoneBook($phoneBook);

    return $this->json(['status' => 'Customer details updated!'], Response::HTTP_OK);
    }

    /**
     * phone book delete function
     * route url: /api/phonebook/delete/{id}
     * method : DELETE
     */
    #[Route('/api/phonebook/delete/{id}', name: 'app_phone_book_delete', methods: 'DELETE')]
    public function deleteCustomerPhoneBook($id,Request $request): Response
    {
    $phoneBook = $this->phoneBookRepository->findOneBy(['id' => $id]);  
    $updatedPhoneBook = $this->phoneBookRepository->removeCustomer($phoneBook);

    return $this->json(['status' => 'Customer details deleted!'], Response::HTTP_OK);
    }

    /**
     * phone book search function
     * route url: /api/phonebook/search/{name}
     * method : POST
     * {name} is query string (first name or last name ) pass to URL 
     */
    #[Route('/api/phonebook/search/{name}', name: 'app_phone_book_search', methods: 'POST')]
    public function searchCustomerPhoneBook($name,Request $request): Response
    {
     $searchCustomerName = $this->phoneBookRepository->searchByName($name);     
     $phoneBookdata = [];
        foreach ($searchCustomerName as $phoneBook) {
            $phoneBookdata[] = [
                'id' => $phoneBook->getId(),
                'firstName' => $phoneBook->getFirstName(),
                'lastName' => $phoneBook->getLastName(),
                'address' => $phoneBook->getAddress(),
                'email' => $phoneBook->getEmail(),
                'birthday' => $phoneBook->getBirthday(),
                'phoneNumber' => $phoneBook->getPhoneNumber(),
                'createdAt' => $phoneBook->getCreatedAt(), 
            ];
        }
    return $this->json($phoneBookdata, Response::HTTP_OK);
    }
}
