<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TransactionControllerTest extends WebTestCase
{
    private function logIn($client)
    {
        $user = self::getContainer()->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneBy([]);
        $client->loginUser($user);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $this->logIn($client);
        $client->request('GET', '/transaction/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testNewForm(): void
    {
        $client = static::createClient();
        $this->logIn($client);
        $client->request('GET', '/transaction/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
}
