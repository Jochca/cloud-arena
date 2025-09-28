<?php

declare(strict_types=1);

namespace App\Task\Controller;

use App\Task\Entity\Task;
use App\Task\Repository\TaskRepository;
use App\Task\ValueObject\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskStatusController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    #[Route('/task/{uuid}/update', name: 'task_update_status', methods: ['POST'])]
    public function update(string $uuid, Request $request): Response
    {
        $task = $this->taskRepository->find($uuid);

        if (!$task) {
            return $this->json(['error' => 'Task not found.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return $this->json(['error' => 'Status is required.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $status = TaskStatus::from($data['status']);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid status value.'], Response::HTTP_BAD_REQUEST);
        }

        $task->setStatus($status);

        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            return $this->json(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->json(['message' => 'Task status updated successfully.']);
    }
}
