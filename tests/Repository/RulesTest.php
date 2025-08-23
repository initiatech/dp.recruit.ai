<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Models\Candidate;
use App\Models\Conversation;
use App\Models\HeatScore;
use App\Models\Job;
use App\Repositories\CandidateRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\HeatScoreRepository;
use App\Repositories\JobRepository;
use PDO;
use PDOException;
use Tests\DatabaseTestCase;

class RulesTest extends DatabaseTestCase
{
    private CandidateRepository $candidateRepo;
    private JobRepository $jobRepo;
    private ConversationRepository $convRepo;
    private HeatScoreRepository $heatScoreRepo;
    private PDO $pdo;

    public function setUp(): void
    {
        parent::setUp();
        $this->candidateRepo = new CandidateRepository();
        $this->jobRepo = new JobRepository();
        $this->convRepo = new ConversationRepository();
        $this->heatScoreRepo = new HeatScoreRepository();

        global $pdo; // a bit of a hack
        $this->pdo = self::$pdo;
    }

    public function testCannotCreateDuplicateActiveConversation(): void
    {
        // 1. Setup
        $candidate = new Candidate();
        $candidate->full_name = 'מועמד';
        $this->candidateRepo->create($candidate);
        $job = new Job();
        $job->title = 'משרה';
        $job->required_fields_json = '[]';
        $this->jobRepo->create($job);

        // 2. Create first conversation (should succeed)
        $conv1 = new Conversation();
        $conv1->candidate_id = $candidate->id;
        $conv1->job_id = $job->id;
        $conv1->status = 'new';
        $result1 = $this->convRepo->create($conv1);
        $this->assertTrue($result1, "First active conversation should be created successfully.");

        // 3. Try to create a second active one (should fail)
        $conv2 = new Conversation();
        $conv2->candidate_id = $candidate->id;
        $conv2->job_id = $job->id;
        $conv2->status = 'in_interview';
        $result2 = $this->convRepo->create($conv2);
        $this->assertFalse($result2, "Should not be able to create a second active conversation.");
    }

    public function testCanCreateSecondConversationIfFirstIsClosed(): void
    {
        // 1. Setup (as above)
        $candidate = new Candidate();
        $candidate->full_name = 'מועמד';
        $this->candidateRepo->create($candidate);
        $job = new Job();
        $job->title = 'משרה';
        $job->required_fields_json = '[]';
        $this->jobRepo->create($job);
        $conv1 = new Conversation();
        $conv1->candidate_id = $candidate->id;
        $conv1->job_id = $job->id;
        $this->convRepo->create($conv1);

        // 2. "Close" the first conversation
        $stmt = $this->pdo->prepare("UPDATE conversations SET status = 'completed' WHERE id = :id");
        $stmt->execute(['id' => $conv1->id]);
        $this->assertEquals(1, $stmt->rowCount());

        // 3. Try to create a second one (should now succeed)
        $conv2 = new Conversation();
        $conv2->candidate_id = $candidate->id;
        $conv2->job_id = $job->id;
        $conv2->status = 'new';
        $result2 = $this->convRepo->create($conv2);
        $this->assertTrue($result2, "Should be able to create a new conversation after the first is closed.");
    }

    public function testCannotCreateDuplicateHeatScore(): void
    {
        // 1. Setup
        $this->seed('UsersSeeder');
        $this->seed('CandidatesSeeder');
        $this->seed('JobsSeeder');
        $this->seed('ConversationsAndMessagesSeeder');

        // 2. Create first score
        $hs1 = new HeatScore();
        $hs1->conversation_id = 'a1b2c3d4-e5f6-7890-1234-567890abcdef'; // From seeder
        $hs1->score = 80;
        $hs1->breakdown_json = '{}';
        $this->heatScoreRepo->create($hs1);

        // 3. Expect an exception on the second insert
        $this->expectException(PDOException::class);
        // In SQLite, this will be a 'UNIQUE constraint failed' error.

        $hs2 = new HeatScore();
        $hs2->conversation_id = 'a1b2c3d4-e5f6-7890-1234-567890abcdef';
        $hs2->score = 90;
        $hs2->breakdown_json = '{}';
        $this->heatScoreRepo->create($hs2);
    }
}
