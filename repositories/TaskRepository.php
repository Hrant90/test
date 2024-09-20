<?php

namespace Repositories;

use PDO;
use Services\Database;

readonly class TaskRepository implements TaskRepositoryInterface {
    public function __construct(private Database $db) {}

    public function getAllTasks(int $limit, int $offset, string $search = null): array {
        $sql = "SELECT id, title, date FROM tasks";

        if ($search) {
            $sql .= " WHERE title LIKE :search";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        if ($search) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskById(int $id): ?array {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getCount(string $search = null): int
    {
        $sql = "SELECT COUNT(*) AS count FROM tasks";

        if ($search) {
            $sql .= " WHERE title LIKE :search";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['count'];
    }
}
