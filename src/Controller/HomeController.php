<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\ReservationType;
use App\Repository\CarRepository;
use App\Service\TransactionService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class HomeController
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
     * @Route("/", name="home")
     * @param CarRepository $carRepository
     * @return Response
     */
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy(['available' => 1], ['daily_price' => 'DESC' ]);

        $content = $this->twig->render('home/index.html.twig', [
            'cars' => $cars
        ]);

        return new Response($content);
    }

    /**
     * @Route("/rent/{id}", name="rent")
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TransactionService $service
     * @param Security $security
     * @param CarRepository $carRepo
     * @param null $id
     * @return Response
     */
    public function rent(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        TransactionService $service,
        Security $security,
        CarRepository $carRepo,
        $id = null
    ): Response
    {
        if($security->isGranted('ROLE_USER')){
            $car = $carRepo->find($id);

            $transaction = new Transaction();

            $form = $formFactory->create(ReservationType::class, $transaction);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $date = $form->get('date')->getData();
                $date = $service->parseDate($date);

                $transaction->setPickupDate(new DateTime($date[0]));
                $transaction->setReturnDate(new DateTime($date[1]));

                $days = $service->dateDiff($date[0], $date[1]);
                $daily_price = $car->getDailyPrice();
                $daily_km = $car->getDailyMaxKm();

                $transaction->setPickupCarKm($car->getKm());
                $transaction->setAmount($service->calculateAmount($daily_price, $days));
                $transaction->setReturnCarKm($service->calculateExpectedCarKM($daily_km, $days));

                $transaction->setCarId($car);

                $transaction->setCustomerId($security->getUser());
                $transaction->setStatus(1);
                $car->setAvailable(0);

                $transaction->setDate(new DateTime());
                $this->flash->add('success', 'Reservation was successful.');

                $em->persist($transaction);
                $em->flush();

                return new RedirectResponse($this->router->generate('home'));
            }

            $content = $this->twig->render('home/rent.html.twig', [
                'form' => $form->createView(),
                'car' => $car
            ]);

            return new Response($content);
        }else {
            return new RedirectResponse($this->router->generate('app_login'));
        }

    }

}
