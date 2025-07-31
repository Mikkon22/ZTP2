<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;

class PortfolioControllerTest extends WebTestCase
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
        $client->request('GET', '/portfolio/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testNewAndDelete(): void
    {
        $client = static::createClient();
        $this->logIn($client);
        $crawler = $client->request('GET', '/portfolio/new');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Save')->form([
            'portfolio[name]' => 'Test Portfolio',
            'portfolio[type]' => 'cash',
            'portfolio[balance]' => 100,
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/portfolio/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'Test Portfolio');
        // Find the created portfolio and delete it
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $portfolio = $em->getRepository(Portfolio::class)->findOneBy(['name' => 'Test Portfolio']);
        $this->assertNotNull($portfolio);
        $client->request('POST', '/portfolio/'.$portfolio->getId(), [
            '_token' => $client->getContainer()->get('security.csrf.token_manager')->getToken('delete'.$portfolio->getId()),
        ]);
        $this->assertResponseRedirects('/portfolio/');
    }
} 