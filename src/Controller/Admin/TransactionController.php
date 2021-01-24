<?php

namespace App\Controller\Admin;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use App\Service\TransactionService;
use DateTime;
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
        $transactions = $repository->findBy([], ['date' => 'DESC']);
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
        TransactionService $service,
        $id = null
    ): Response {
        $transaction = new Transaction();

        if (!is_null($id) && ((int)$id) > 0) {
            $transaction = $repository->find($id) ?? $transaction;
        }

        $company_id = $security->getUser()->getCompany()->getId();
        $form = $formFactory->create(TransactionType::class, $transaction, [
            'company_id' => $company_id
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('reservationDate')->getData();

            $date = $service->parseDate($date);

            $transaction->setPickupDate(new DateTime($date[0]));
            $transaction->setReturnDate(new DateTime($date[1]));

            $days = $service->dateDiff($date[0], $date[1]);
            $daily_price = $transaction->getCarId()->getDailyPrice();
            $daily_km = $transaction->getCarId()->getDailyMaxKm();

            $transaction->setPickupCarKm($transaction->getCarId()->getKm());
            $transaction->setAmount($service->calculateAmount($daily_price, $days));
            $transaction->setReturnCarKm($service->calculateExpectedCarKM($daily_km, $days));

            $transaction->setStatus(1);
            $transaction->getCarId()->setAvailable(0);

            if (!$transaction->getId()) {
                $transaction->setDate(new DateTime());
                $this->flash->add('success', 'Created was successful.');
            } else {
                $this->flash->add('success', 'Updated was successful.');
            }

            $em->persist($transaction);
            $em->flush();

            return new RedirectResponse($this->router->generate('app_admin_transaction_list'));
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

            $km = $transaction->getCarId()->getKm();
            if($km > 0){
                $new_km = $km - $transaction->getReturnCarKm();
                $transaction->getCarId()->setKm($new_km);
            }

            $em->remove($transaction);
            $em->flush();

            $this->flash->add('success', 'Deleted was successful.');
        } else {
            $this->flash->add('danger', 'Transaction not found id for ' . $id);
        }

        return new RedirectResponse($this->router->generate('app_admin_transaction_list'));
    }

    /**
     * @Route("/complete/{id}", name="complete", defaults={"id": null})
     * @param EntityManagerInterface $em
     * @param TransactionRepository $repository
     * @param null $id
     * @return Response
     */
    public function complete(EntityManagerInterface $em, TransactionRepository $repository, $id = null): Response
    {
        $transaction = $repository->find($id);

        if ($transaction) {
            $transaction->getCarId()->setAvailable(1);

            $km = $transaction->getCarId()->getKm(); // first km
            $last_km = $km + $transaction->getReturnCarKm();

            $transaction->getCarId()->setKm($last_km);
            $transaction->setStatus(0);
            $transaction->setReturnDate(new DateTime());

            $em->persist($transaction);
            $em->flush();

            $this->flash->add('success', 'Update was successful.');
        }else {
            $this->flash->add('danger', 'Transaction not found id for ' . $id);
        }

        return new RedirectResponse($this->router->generate('app_admin_transaction_list'));
    }
}
