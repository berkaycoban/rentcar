<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

/**
 * @Route("/admin/user", name="app_admin_user_")
 */
class UserController
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
     * @param UserRepository $repository
     * @return Response
     */
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();
        $content = $this->twig->render('admin/user/index.html.twig', ['users' => $users]);

        return new Response($content);
    }

    /**
     * @route("/create/{id}", name="create", defaults={"id": null})
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $repository
     * @param null $id
     * @return RedirectResponse|Response
     */
    public function create(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $repository,
        $id = null
    ) {
        $user = new User();

        if (!is_null($id) && ((int)$id) > 0) {
            $user = $repository->find($id) ?? $user;
        }

        $form = $formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $user->getPassword())
                );
            }

            if ($form->get('role')->getData()) {
                $user->setRoles(['ROLE_SUPER_ADMIN']);
            }

            if (!$user->getId()) {
                $user->setCreatedAt(new DateTime());
                $this->flash->add('success', 'Created was successful.');
            } else {
                $this->flash->add('success', 'Updated was successful.');
            }

            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->router->generate('app_admin_user_list'));
        }

        $content = $this->twig->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }


    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param UserRepository $repository
     * @param $id
     * @return Response
     */
    public function delete(EntityManagerInterface $em, UserRepository $repository, $id): Response
    {
        $user = $repository->find($id);

        if ($user) {
            $em->remove($user);
            $em->flush();

            $this->flash->add('success', 'Deleted was successful.');
        } else {
            $this->flash->add('danger', 'User not found id for '.$id);
        }

        return new RedirectResponse($this->router->generate('app_admin_user_list'));
    }
}
