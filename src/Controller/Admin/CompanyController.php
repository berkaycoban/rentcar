<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * @Route("/admin/company", name="app_admin_company_")
 */
class CompanyController
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
     * @param CompanyRepository $repository
     * @return Response
     */
    public function index(CompanyRepository $repository): Response
    {
        $companies = $repository->findAll();
        $content = $this->twig->render('admin/company/index.html.twig', ['companies' => $companies]);

        return new Response($content);
    }

    /**
     * @Route("/create/{id}", name="create", defaults={"id": null})
     * @param FormFactoryInterface $formFactory
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CompanyRepository $repository
     * @param null $id
     * @return RedirectResponse|Response
     */
    public function create(
        FormFactoryInterface $formFactory,
        Request $request,
        EntityManagerInterface $em,
        CompanyRepository $repository,
        $id = null
    ) {
        $company = new Company();

        if (!is_null($id) && ((int)$id) > 0) {
            $company = $repository->find($id) ?? $company;
        }

        $form = $formFactory->create(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$company->getId()) {
                $company->setCreatedAt(new DateTime());
                $this->flash->add('success', 'Created was successful.');
            } else {
                $this->flash->add('success', 'Updated was successful.');
            }

            $em->persist($company);
            $em->flush();

            return new RedirectResponse($this->router->generate('app_admin_company_list'));
        }

        $content = $this->twig->render('admin/company/create.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }


    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @param CompanyRepository $repository
     * @param $id
     * @return Response
     */
    public function delete(EntityManagerInterface $em, CompanyRepository $repository, $id): Response
    {
        $company = $repository->find($id);

        if ($company) {
            $em->remove($company);
            $em->flush();

            $this->flash->add('success', 'Deleted was successful.');
        } else {
            $this->flash->add('danger', 'Company not found id for '.$id);
        }

        return new RedirectResponse($this->router->generate('app_admin_company_list'));
    }
}
