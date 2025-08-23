<?php

declare(strict_types=1);

namespace Tests;

use PDO;

class SeederTest extends DatabaseTestCase
{
    public function testSeedersRunAndPopulateData(): void
    {
        // The getDependencies() in the seeder should handle the order,
        // but we call them explicitly to be clear. Phinx will still respect dependencies.
        $this->seed('UsersSeeder');
        $this->seed('JobsSeeder');
        $this->seed('CandidatesSeeder');
        $this->seed('ConversationsAndMessagesSeeder');

        /** @var PDO $pdo */
        global $pdo;
        $pdo = self::$pdo;

        $userCount = $pdo->query("SELECT count(*) FROM users")->fetchColumn();
        $this->assertGreaterThan(0, $userCount, "Users table should be populated.");

        $jobCount = $pdo->query("SELECT count(*) FROM jobs")->fetchColumn();
        $this->assertGreaterThan(0, $jobCount, "Jobs table should be populated.");

        $candidateCount = $pdo->query("SELECT count(*) FROM candidates")->fetchColumn();
        $this->assertGreaterThan(0, $candidateCount, "Candidates table should be populated.");

        $conversationCount = $pdo->query("SELECT count(*) FROM conversations")->fetchColumn();
        $this->assertGreaterThan(0, $conversationCount, "Conversations table should be populated.");

        $messageCount = $pdo->query("SELECT count(*) FROM messages")->fetchColumn();
        $this->assertGreaterThan(0, $messageCount, "Messages table should be populated.");

        // Check relation
        $stmt = $pdo->query("SELECT c.id from conversations c JOIN candidates ca ON c.candidate_id = ca.id");
        $this->assertGreaterThan(0, count($stmt->fetchAll()), "Conversations should be linked to candidates.");
    }
}
