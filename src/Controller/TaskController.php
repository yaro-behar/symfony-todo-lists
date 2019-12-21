<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/create", name="task-create", methods={"GET"})
     */
    public function create()
    {

    }

    /**
     * @Route("/task/delete/{id}", name="task-delete", methods={"GET"})
     */
    public function delete(int $id)
    {

    }
}
