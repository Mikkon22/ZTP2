<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Default controller for the application home route.
 */
class DefaultController extends AbstractController
{
    /**
     * Home page route.
     *
     * @return Response redirects to the appropriate route based on user authentication
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_portfolio_index');
        }

        return $this->redirectToRoute('app_register');
    }
}
