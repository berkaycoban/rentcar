<?php

namespace App\Controller\Admin;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
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

        $transactions = $repository->findAll();
        $content = $this->twig->render('admin/transaction/index.html.twig', ['transactions' => $transactions]);

        return new Response($content);
    }

    /**
     * @Route("/create/{id}", name="create", defaults={"id": null})
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TransactionRepository $repository
     * @param Security $security
     * @param null $id
     * @return Response
     */
    public function create(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        TransactionRepository $repository,
        Security $security,
        $id = null
    ): Response
    {
        $transaction = new Transaction();

        if(!is_null($id) && ((int)$id) > 0) {
            $transaction = $repository->find($id) ?? $transaction;
        }

        $company_id = $security->getUser()->getCompany()->getId();
        $form = $formFactory->create(TransactionType::class, $transaction, [
            'company_id' => $company_id
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $date = $form->get('reservationDate')->getData();
            // tarig explode edilip
            // ilk ve ikinci tarih bulunacak
            // sonra gun hesaplanacak
            // sonra fiyat, km hesaplanacak

            // amount
            // pickup km
            // return km
        }

        $content = $this->twig->render('admin/transaction/create.html.twig', [
            'form' => $form->createView()
        ]);

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

        if ($transaction) {
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
