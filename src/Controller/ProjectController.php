<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/project/update", name="project-update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $project = $manager->getRepository(Project::class)->find($request->request->get('project_id'));
        if (!$project) {
            throw $this->createNotFoundException('No project found for id ' . $request->request->get('project_id'));
        }

        $project->setName($request->request->get('project_name'));
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/project/delete/{id}", name="project-delete", methods={"GET"})
     */
    public function delete(int $id)
    {
        $manager = $this->getDoctrine()->getManager();

        $project = $manager->getRepository(Project::class)->find($id);
        if (!$project) {
            throw $this->createNotFoundException('No project found for id ' . $id);
        }

        $manager->remove($project);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
