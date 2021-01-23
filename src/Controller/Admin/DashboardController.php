<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * @Route("/admin", name="app_admin_")
 */
class DashboardController
{
    private $twig;
    private $router;
    private $flash;

    public function __construct(Environment $twig, RouterInterface $router, FlashBagInterface $flash){
        $this->twig = $twig;
        $this->router = $router;
        $this->flash = $flash;
    }

    /**
     * @Route("/", name="dashboard")
     * @return Response
     */
    public function index(): Response
    {
        $content = $this->twig->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);

        return new Response($content);
    }
}
