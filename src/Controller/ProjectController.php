<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects", methods={"GET"}, name="projects")
     */
    public function index()
    {
        $id = 4;

        $userRepository = $this->getDoctrine()->getRepository(\App\Entity\User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("No user found for id {$id}");
        }

        return $this->render('product/index.html.twig', ['user' => $user]);
    }
}
