<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project/index", methods={"GET"}, name="project-list")
     */
    public function index()
    {
        $id = 22;

        $userRepository = $this->getDoctrine()->getRepository(\App\Entity\User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException("No user found for id {$id}");
        }

        return $this->render('product/index.html.twig', ['user' => $user]);
    }
}
