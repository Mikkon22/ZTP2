<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryControllerTest extends WebTestCase
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
        $client->request('GET', '/category/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    public function testNewAndDelete(): void
    {
        $client = static::createClient();
        $this->logIn($client);
        $crawler = $client->request('GET', '/category/new');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Save')->form([
            'category[name]' => 'Test Category',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/category/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'Test Category');
        // Find the created category and delete it
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $category = $em->getRepository(Category::class)->findOneBy(['name' => 'Test Category']);
        $this->assertNotNull($category);
        $client->request('POST', '/category/'.$category->getId(), [
            '_token' => $client->getContainer()->get('security.csrf.token_manager')->getToken('delete'.$category->getId()),
        ]);
        $this->assertResponseRedirects('/category/');
    }
}
