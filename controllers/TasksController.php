<?php

namespace Controllers;

use Repositories\TaskRepositoryInterface;

class TasksController
{
    public function __construct(private readonly TaskRepositoryInterface $taskRepository)
    {
    }

    public function getAllTasks(array $params): void
    {
        $page = $params['page'];
        $limit = $params['limit'];
        $search = $params['search'] ?? null;
        $offset = ($page - 1) * $limit;
        $totalTasks = $this->taskRepository->getCount($search);

        $meta = [
            'total' => $totalTasks,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalTasks / $limit),
        ];
        $this->jsonResponse(['tasks' => $this->taskRepository->getAllTasks($limit, $offset, $search), 'meta' => $meta]);
    }

    public function getTaskById(int $id): void
    {
        $this->jsonResponse($this->taskRepository->getTaskById($id));
    }

    private function jsonResponse(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}


