<?php

namespace App\Controller;

use App\Entity\Task;
use App\Exception\FormValidationException;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @var TaskService
     */
    private $taskService;
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(TaskService $taskService, TaskRepository $taskRepository)
    {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/", methods="GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $taskId = $request->query->get('task_id');
        if (!preg_match ("/^([0-9]+)$/", $taskId)) {
            throw new BadRequestHttpException('Invalid taskId', null, Response::HTTP_BAD_REQUEST);
        }

        /** @var Task $task */
        $task = $this->taskRepository->find($taskId);
        if(empty($task)) {
            throw new NotFoundHttpException('Task ' . $taskId . ' not found');
        }

        return $this->json(
            $task,
            $task->getStatus() === Task::STATUS_RECEIVED || $task->getStatus() === Task::STATUS_WAIT ? Response::HTTP_ACCEPTED : Response::HTTP_OK,
            [],
            ['groups' => 'Default']
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/", methods="POST")
     *
     * @return JsonResponse
     * @throws FormValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $taskPresent = false;
        try {
            $task = $this->taskService->create($request, $taskPresent);
            if ($taskPresent) {
                return $this->json(
                    $task,
                    $task->getStatus() === Task::STATUS_RECEIVED || $task->getStatus() === Task::STATUS_WAIT ? Response::HTTP_ACCEPTED : Response::HTTP_OK,
                    [],
                    ['groups' => 'Default']
                );
            } else {
                return $this->json(
                    $task,
                    Response::HTTP_CREATED,
                    [],
                    ['groups' => 'Default']
                );
            }
        } catch(FormValidationException $exception) {
            return $this->json(
                $exception->getForm(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

}
