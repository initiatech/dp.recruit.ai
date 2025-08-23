<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Job;
use App\Utils\Database;
use PDO;

class JobRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?Job
    {
        $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function create(Job &$job): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO jobs (title, description, required_fields_json, must_have, nice_to_have, block_rules, heat_threshold)
             VALUES (:title, :description, :required_fields_json, :must_have, :nice_to_have, :block_rules, :heat_threshold)"
        );

        $success = $stmt->execute([
            'title' => $job->title,
            'description' => $job->description,
            'required_fields_json' => $job->required_fields_json,
            'must_have' => $job->must_have,
            'nice_to_have' => $job->nice_to_have,
            'block_rules' => $job->block_rules,
            'heat_threshold' => $job->heat_threshold,
        ]);

        if ($success) {
            $job->id = (int)$this->db->lastInsertId();
        }

        return $success;
    }

    private function hydrate(array $data): Job
    {
        $job = new Job();
        $job->id = (int)$data['id'];
        $job->title = $data['title'];
        $job->description = $data['description'];
        $job->required_fields_json = $data['required_fields_json'];
        $job->must_have = $data['must_have'];
        $job->nice_to_have = $data['nice_to_have'];
        $job->block_rules = $data['block_rules'];
        $job->heat_threshold = (int)$data['heat_threshold'];
        $job->created_at = $data['created_at'];

        return $job;
    }
}
