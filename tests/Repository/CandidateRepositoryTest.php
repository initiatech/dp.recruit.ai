<?php

declare(strict_types=1);

namespace Tests\Repository;

use App\Models\Candidate;
use App\Repositories\CandidateRepository;
use Tests\DatabaseTestCase;

class CandidateRepositoryTest extends DatabaseTestCase
{
    private CandidateRepository $candidateRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->candidateRepo = new CandidateRepository();
    }

    public function testCreateAndFindCandidate(): void
    {
        $candidate = new Candidate();
        $candidate->full_name = 'מועמד לבדיקה';
        $candidate->email = 'test@example.com';
        $candidate->phone = '123456';
        $candidate->cv_text = 'קורות חיים בעברית.';
        $candidate->source = 'בדיקה';

        $result = $this->candidateRepo->create($candidate);

        $this->assertTrue($result);
        $this->assertNotNull($candidate->id, "Candidate ID should be set after creation.");
        $this->assertGreaterThan(0, $candidate->id);

        $foundCandidate = $this->candidateRepo->find($candidate->id);

        $this->assertInstanceOf(Candidate::class, $foundCandidate);
        $this->assertEquals($candidate->id, $foundCandidate->id);
        $this->assertEquals('מועמד לבדיקה', $foundCandidate->full_name, "UTF-8 name should match.");
        $this->assertEquals('קורות חיים בעברית.', $foundCandidate->cv_text, "UTF-8 CV text should match.");
        $this->assertEquals('test@example.com', $foundCandidate->email);
    }

    public function testFindNonExistentCandidate(): void
    {
        $foundCandidate = $this->candidateRepo->find(999);
        $this->assertNull($foundCandidate);
    }
}
