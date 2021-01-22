<?php

namespace App\Controller\Admin;

use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * @Route("/admin/transaction", name="app_admin_transaction_")
 */
class TransactionController
{
    private $twig;
    private $router;
    private $flash;

    public function __construct(Environment $twig, RouterInterface $router, FlashBagInterface $flash)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->flash = $flash;
    }

    /**
     * @Route("/", name="list")
     * @param TransactionRepository $repository
     * @return Response
     */
    public function index(TransactionRepository $repository): Response
    {
        // sadece o sirkete ait transaction islemlerini alabiliriz...
        $transactions = $repository->findAll();
        $content = $this->twig->render('admin/transaction/index.html.twig', ['transactions' => $transactions]);

        return new Response($content);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param TransactionRepository $repository
     * @param $id
     * @return Response
     */
    public function delete(EntityManagerInterface $em, TransactionRepository $repository, $id): Response
    {
        $transaction = $repository->find($id);

        if($transaction){
            $transaction->getCarId()->setAvailable(true);
            $em->remove($transaction);
            $em->flush();

            $this->flash->add('success', 'Deleted was successful.');
        } else {
            $this->flash->add('danger', 'Transaction not found id for ' . $id);
        }

        return new RedirectResponse($this->router->generate('app_admin_transaction_list'));
    }
}
