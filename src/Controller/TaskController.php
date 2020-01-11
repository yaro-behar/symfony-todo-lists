<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Project;
use App\Entity\Task;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/create", name="task-create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $project = $manager->getRepository(Project::class)->find($request->request->get('project_id'));
        if (!$project) {
            throw $this->createNotFoundException('No project found for id ' . $request->request->get('project_id'));
        }

        $task = new Task();
        $task->setName($request->request->get('task_name'));
        $task->setProject($project);

        $manager->persist($task);
        $manager->flush();

        return new JsonResponse(
            $this->render('task/create.html.twig', ['task' => $task])->getContent(),
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    /**
     * @Route("/task/update", name="task-update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $task = $manager->getRepository(Task::class)->find($request->request->get('task_id'));
        if (!$task) {
            throw $this->createNotFoundException('No task found for id ' . $request->request->get('task_id'));
        }

        if (!$task->isStatusActive()) {
            return new JsonResponse(['status' => 'inactive'], Response::HTTP_OK);
        }

        if (!empty($request->request->get('task_name'))) {
            $task->setName($request->request->get('task_name'));
        }
        if (!empty($request->request->get('task_deadline'))) {
            $deadline = \DateTime::createFromFormat('Y-m-d', $request->request->get('task_deadline'));
            $task->setDeadline($deadline);
        }
        $manager->flush();

        return new JsonResponse(['status' => 'active'], Response::HTTP_OK);
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

    /**
     * @Route("/task/inactivate/{id}", name="task-inactivate", methods={"GET"})
     */
    public function inactivate(int $id)
    {
        $manager = $this->getDoctrine()->getManager();

        $task = $manager->getRepository(Task::class)->find($id);
        if (!$task) {
            throw $this->createNotFoundException('No task found for id ' . $id);
        }

        $task->setStatus(Task::STATUS_INACTIVE);
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * @Route("/task/reorder", name="task-reorder", methods={"POST"})
     */
    public function reorder(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $tasks = array_flip(explode(',', $request->request->get('task_order')));

        $prioritizedTasks = [];
        $i = 0;
        foreach ($tasks as $key => $value) {
            $prioritizedTasks[(int)$key] = count($tasks) - $i;
            $i++;
        }
        unset($tasks);

        $tasks = $manager->getRepository(Task::class)->findBy(['project' => $request->request->get('project_id')]);
        foreach ($tasks as $task) {
            $task->setPriority($prioritizedTasks[$task->getId()]);
        }
        $manager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
