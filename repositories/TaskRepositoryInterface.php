<?php

namespace Repositories;

interface TaskRepositoryInterface {
    public function getAllTasks(int $limit, int $offset, string $search = null): array;
    public function getCount(string $search = null): int;
    public function getTaskById(int $id): ?array;
}
