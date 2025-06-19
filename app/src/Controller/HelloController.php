<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     *
     * @return Response redirects to hello index
     */
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->redirectToRoute('hello_index');
    }

    /**
     * Index action.
     *
     * @return Response the response object
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
            'name' => $name,
        ]);
    }

    /**
     * Advanced Twig examples.
     *
     * @return Response the response object
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
