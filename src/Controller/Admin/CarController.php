<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Service\FileUploader;
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

/**
 * @Route("/admin/car", name="app_admin_car_")
 */
class CarController
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
     * @param CarRepository $repository
     * @return Response
     */
    public function index(CarRepository $repository, Security $security): Response
    {
        $user = $security->getUser();

        if($security->isGranted('ROLE_SUPER_ADMIN')){
            $cars = $repository->findAll();
        }else {
            $company_id = $user->getCompany()->getId();
            $cars = $repository->findBy(['owner_id' => $company_id]);
        }

        $content = $this->twig->render('admin/car/index.html.twig', ['cars' => $cars]);

        return new Response($content);
    }

    /**
     * @Route("/create/{id}", name="create", defaults={"id": null})
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CarRepository $repository
     * @param null $id
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        CarRepository $repository,
        FileUploader $fileUploader,
        $id = null
    ): Response {
        $car = new Car();

        if (!is_null($id) && ((int)$id) > 0) {
            $car = $repository->find($id) ?? $car;
        }

        $form = $formFactory->create(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carImage = $form->get('image')->getData();

            if ($carImage) {
                $carImageName = $fileUploader->upload($carImage);
                $car->setImgSrc($carImageName);
            }

            if ($car->getId()) {
                $this->flash->add('success', 'Updated was successful.');
            } else {
                $car->setCreatedAt(new DateTime());
                $this->flash->add('success', 'Created was successful.');
            }

            $em->persist($car);
            $em->flush();

            return new RedirectResponse($this->router->generate('app_admin_car_list'));
        }

        $content = $this->twig->render('admin/car/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param CarRepository $repository
     * @param $id
     * @return Response
     */
    public function delete(EntityManagerInterface $em, CarRepository $repository, FileUploader $fileUploader, $id) : Response
    {
        $car = $repository->find($id);

        if ($car) {
            $fileUploader->remove($car->getImgSrc());
            $em->remove($car);
            $em->flush();

            $this->flash->add('success', 'Deleted was successful.');
        } else {
            $this->flash->add('danger', 'Car not found id for ' . $id);
        }

        return new RedirectResponse($this->router->generate('app_admin_car_list'));
    }
}
