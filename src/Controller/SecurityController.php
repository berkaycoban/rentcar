<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\CustomerType;
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
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController
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
     * @Route("/login", name="app_login")
     * @param Security $security
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Security $security, AuthenticationUtils $authenticationUtils): Response
    {
        // if user is already logged in, don't display the login page again
        if ($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPER_ADMIN')) {
            return new RedirectResponse($this->router->generate('app_admin_dashboard'));
        } elseif ($security->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->router->generate('home'));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $content = $this->twig->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);

        return new Response($content);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register/{id}", name="app_register", defaults={"id": null})
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $repository
     * @param null $id
     * @return Response
     */
    public function register(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $repository,
        $id = null
    ): Response {
        $user = new User();

        if (!is_null($id) && ((int)$id) > 0) {
            $user = $repository->find($id) ?? $user;
        }

        $form = $formFactory->create(CustomerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $user->getPassword())
                );
            }

            $user->setRoles(['ROLE_USER']);

            if (!$user->getId()) {
                $user->setCreatedAt(new DateTime());
                $this->flash->add('success', 'Your account was created successful..');
            } else {
                $this->flash->add('success', 'Your account was updated successful.');
            }

            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->router->generate('home'));
        }

        $content = $this->twig->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }
}
