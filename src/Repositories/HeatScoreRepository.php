<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\HeatScore;
use App\Utils\Database;
use PDO;

class HeatScoreRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByConversation(string $conversationId): ?HeatScore
    {
        $stmt = $this->db->prepare("SELECT * FROM heat_scores WHERE conversation_id = :conversation_id");
        $stmt->execute(['conversation_id' => $conversationId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function create(HeatScore &$heatScore): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO heat_scores (conversation_id, score, breakdown_json, rubric_json)
             VALUES (:conversation_id, :score, :breakdown_json, :rubric_json)"
        );

        $success = $stmt->execute([
            'conversation_id' => $heatScore->conversation_id,
            'score' => $heatScore->score,
            'breakdown_json' => $heatScore->breakdown_json,
            'rubric_json' => $heatScore->rubric_json,
        ]);

        if ($success) {
            $heatScore->id = (int)$this->db->lastInsertId();
        }

        return $success;
    }

    private function hydrate(array $data): HeatScore
    {
        $heatScore = new HeatScore();
        $heatScore->id = (int)$data['id'];
        $heatScore->conversation_id = $data['conversation_id'];
        $heatScore->score = (int)$data['score'];
        $heatScore->breakdown_json = $data['breakdown_json'];
        $heatScore->rubric_json = $data['rubric_json'];
        $heatScore->calculated_at = $data['calculated_at'];

        return $heatScore;
    }
}
