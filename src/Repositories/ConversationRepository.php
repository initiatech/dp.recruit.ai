<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Conversation;
use App\Utils\Database;
use App\Utils\Uuid;
use PDO;

class ConversationRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(string $id): ?Conversation
    {
        $stmt = $this->db->prepare("SELECT * FROM conversations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function create(Conversation &$conversation): bool
    {
        // Business Rule: Prevent opening a second "active" one.
        $findActive = $this->db->prepare(
            "SELECT id FROM conversations
             WHERE candidate_id = :candidate_id
               AND job_id = :job_id
               AND status IN ('new', 'in_interview')"
        );
        $findActive->execute([
            'candidate_id' => $conversation->candidate_id,
            'job_id' => $conversation->job_id,
        ]);
        if ($findActive->fetch()) {
            return false; // Found an existing active conversation
        }

        // Assign a new UUID if one is not already set.
        if (empty($conversation->id)) {
            $conversation->id = Uuid::v4();
        }

        $stmt = $this->db->prepare(
            "INSERT INTO conversations (id, candidate_id, job_id, status, started_at)
             VALUES (:id, :candidate_id, :job_id, :status, NOW())"
        );

        return $stmt->execute([
            'id' => $conversation->id,
            'candidate_id' => $conversation->candidate_id,
            'job_id' => $conversation->job_id,
            'status' => $conversation->status ?? 'new',
        ]);
    }

    private function hydrate(array $data): Conversation
    {
        $conversation = new Conversation();
        $conversation->id = $data['id'];
        $conversation->candidate_id = (int)$data['candidate_id'];
        $conversation->job_id = (int)$data['job_id'];
        $conversation->status = $data['status'];
        $conversation->started_at = $data['started_at'];
        $conversation->closed_at = $data['closed_at'];

        return $conversation;
    }
}
