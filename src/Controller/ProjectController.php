<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Project;

class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project-list", methods={"GET"})
     */
    public function index()
    {
        return $this->render('product/index.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/project/create", name="project-create", methods={"GET"})
     */
    public function create()
    {
        $project = new Project();
        $project->setName('New Default Project');
        $project->setUser($this->getUser());

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($project);
        $manager->flush();

        return new JsonResponse(
            $this->render('product/create.html.twig', ['project' => $project])->getContent(),
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/project/delete/{id}", name="project-delete", methods={"GET"})
     */
    public function delete(int $id)
    {

    }
}
