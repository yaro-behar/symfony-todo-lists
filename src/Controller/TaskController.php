<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Task;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/create", name="task-create", methods={"POST"})
     */
    public function create()
    {
        // TODO: continue
        $request = Request::createFromGlobals();
        $projectId = $request->query->get('project_id');
        $taskName = $request->query->get('task_name');

        return new JsonResponse(['project_id' => $projectId, 'task_name' => $taskName], Response::HTTP_OK);
    }

    /**
     * @Route("/task/delete/{id}", name="task-delete", methods={"GET"})
     */
    public function delete(int $id)
    {
        $manager = $this->getDoctrine()->getManager();

        $task = $manager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('No task found for id ' . $id);
        }

        $manager->remove($task);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
