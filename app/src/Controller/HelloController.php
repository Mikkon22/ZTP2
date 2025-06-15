<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class HelloController.
 */
class HelloController extends AbstractController
{
    /**
     * Homepage redirect to hello.
     */
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->redirectToRoute('hello_index');
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(
        '/hello',
        name: 'hello_index',
        methods: 'GET'
    )]
    public function index(): Response
    {
        $name = 'John Doe';
        
        return $this->render('hello/index.html.twig', [
            'name' => $name
        ]);
    }

    /**
     * Advanced Twig examples.
     *
     * @return Response HTTP response
     */
    #[Route(
        '/hello/advanced',
        name: 'hello_advanced',
        methods: 'GET'
    )]
    public function advanced(): Response
    {
        return $this->render('hello/advanced.html.twig');
    }
} 