<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_default_')]
class DefaultController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(MovieRepository $repository): Response
    {
        $movies = $repository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('default/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        $dto = new ContactDTO();
        $contactForm = $this->createForm(ContactType::class, $dto);

        return $this->renderForm('default/contact.html.twig', [
            'contactForm' => $contactForm,
        ]);
    }
}
