<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="projects")
     */
    public function index()
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (is_null($user)) {
            throw $this->createNotFoundException("No user found for id {$user->getId()}");
        }

        return $this->render('product/index.html.twig', ['user' => $user]);
    }
}
