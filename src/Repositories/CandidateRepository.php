<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Candidate;
use App\Utils\Database;
use PDO;

class CandidateRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?Candidate
    {
        $stmt = $this->db->prepare("SELECT * FROM candidates WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function create(Candidate &$candidate): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO candidates (full_name, email, phone, cv_text, source)
             VALUES (:full_name, :email, :phone, :cv_text, :source)"
        );

        $success = $stmt->execute([
            'full_name' => $candidate->full_name,
            'email' => $candidate->email,
            'phone' => $candidate->phone,
            'cv_text' => $candidate->cv_text,
            'source' => $candidate->source,
        ]);

        if ($success) {
            $candidate->id = (int)$this->db->lastInsertId();
        }

        return $success;
    }

    private function hydrate(array $data): Candidate
    {
        $candidate = new Candidate();
        $candidate->id = (int)$data['id'];
        $candidate->full_name = $data['full_name'];
        $candidate->email = $data['email'];
        $candidate->phone = $data['phone'];
        $candidate->cv_text = $data['cv_text'];
        $candidate->source = $data['source'];
        $candidate->created_at = $data['created_at'];

        return $candidate;
    }
}
