<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Models\Candidate;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Repositories\CandidateRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\JobRepository;
use App\Repositories\MessageRepository;
use PDO;
use Tests\DatabaseTestCase;

class WorkflowTest extends DatabaseTestCase
{
    private CandidateRepository $candidateRepo;
    private JobRepository $jobRepo;
    private ConversationRepository $convRepo;
    private MessageRepository $msgRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->candidateRepo = new CandidateRepository();
        $this->jobRepo = new JobRepository();
        $this->convRepo = new ConversationRepository();
        $this->msgRepo = new MessageRepository();
    }

    protected function getDbConnection(): PDO
    {
        // A helper to get the PDO instance for direct queries in tests.
        global $pdo; // This is a bit of a hack for simplicity.
        return self::$pdo;
    }

    public function testCreateFullConversationFlow(): void
    {
        // 1. Create Candidate
        $candidate = new Candidate();
        $candidate->full_name = 'מועמד זרימה';
        $this->candidateRepo->create($candidate);
        $this->assertNotNull($candidate->id);

        // 2. Create Job
        $job = new Job();
        $job->title = 'משרת בדיקה';
        $job->required_fields_json = '[]';
        $this->jobRepo->create($job);
        $this->assertNotNull($job->id);

        // 3. Create Conversation
        $conversation = new Conversation();
        $conversation->candidate_id = $candidate->id;
        $conversation->job_id = $job->id;
        $this->convRepo->create($conversation);
        $this->assertNotEmpty($conversation->id, "Conversation ID should be a UUID string.");

        $foundConv = $this->convRepo->find($conversation->id);
        $this->assertInstanceOf(Conversation::class, $foundConv);
        $this->assertEquals($candidate->id, $foundConv->candidate_id);

        // 4. Add Message
        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->sender = 'ai';
        $message->content_text = 'שלום, זהו מבחן.';
        $this->msgRepo->create($message);
        $this->assertNotNull($message->id);

        $messages = $this->msgRepo->findByConversation($conversation->id);
        $this->assertCount(1, $messages);
        $this->assertEquals('שלום, זהו מבחן.', $messages[0]->content_text);
    }

    public function testCandidateDeletionCascadesConversation(): void
    {
        // 1. Setup
        $candidate = new Candidate();
        $candidate->full_name = 'מועמד למחיקה';
        $this->candidateRepo->create($candidate);

        $job = new Job();
        $job->title = 'משרה זמנית';
        $job->required_fields_json = '[]';
        $this->jobRepo->create($job);

        $conversation = new Conversation();
        $conversation->candidate_id = $candidate->id;
        $conversation->job_id = $job->id;
        $this->convRepo->create($conversation);

        // 2. Action: Delete the candidate directly
        $stmt = $this->getDbConnection()->prepare("DELETE FROM candidates WHERE id = :id");
        $stmt->execute(['id' => $candidate->id]);
        $this->assertEquals(1, $stmt->rowCount());

        // 3. Assertion
        $foundConv = $this->convRepo->find($conversation->id);
        $this->assertNull($foundConv, "Conversation should be deleted by cascade when candidate is deleted.");
    }
}
