<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ContactForm;

final class AboutController extends AbstractController
{
    #[Route('/about', name: 'about')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('home');
        }

        return $this->render('about/about.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
