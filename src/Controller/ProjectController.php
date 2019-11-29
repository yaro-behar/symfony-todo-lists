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
        echo 'Hello, World!'; die;
    }
}
