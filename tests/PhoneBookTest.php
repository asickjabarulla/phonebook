<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\Client;


class PhoneBookTest extends WebTestCase
{
    public function testJsonRequest(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/phonebook/list');

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
       
    }

    public function testJsonPostRequest(): void
    {
        $client = new Client();
        $response = $client->request('POST', 'http://127.0.0.1:8000/api/phonebook/add', [
        'json' => 
        ['firstName' => 'Asick','lastName' 
        => 'ahamed','address' => 'Hamburg',
        'email' => 'asick@gmail.com',
        'birthday' => '01.01.1991','phoneNumber' => '02222555555']]);
        $this->assertSame(201, $response->getStatusCode());
        $result = json_decode($response->getBody(), true);
        $this->assertEquals('Customer details created!', $result['status']);    
 
    }

    public function testJsonPutRequest(): void
    {
        $client = new Client();
        $response = $client->request('PUT', 'http://127.0.0.1:8000/api/phonebook/update/28', [
        'json' => 
        ['firstName' => 'John','lastName' 
        => 'John','address' => 'Hamburg',
        'email' => 'john@gmail.com',
        'birthday' => '01.01.1992','phoneNumber' => '1233']]);
        $this->assertSame(200, $response->getStatusCode());
        $result = json_decode($response->getBody(), true);
        $this->assertEquals('Customer details updated!', $result['status']);    
 
    }

    public function testJsonDeleteRequest(): void
    {
        $client = new Client();
        $response = $client->delete('http://127.0.0.1:8000/api/phonebook/delete/27');
        $this->assertSame(200, $response->getStatusCode());
        $result = json_decode($response->getBody(), true);
        $this->assertEquals('Customer details deleted!', $result['status']);   
    }

    public function testJsonSearchRequest(): void
    {
        $client = new Client();
        $response = $client->post('http://127.0.0.1:8000/api/phonebook/search/asick');
        $this->assertSame(200, $response->getStatusCode());
        $result = json_decode($response->getBody(), true);
    
    }


}
