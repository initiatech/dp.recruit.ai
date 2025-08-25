<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Services\LinkSigningService;
use App\Utils\Uuid;

class ConversationsController extends Controller
{
    private LinkSigningService $linkSigner;

    public function __construct()
    {
        $this->linkSigner = new LinkSigningService();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $data = $request->getBody();

        if (empty($data['candidate_id']) || empty($data['job_id'])) {
            return Response::error('candidate_id and job_id are required.', 400);
        }

        // In a real implementation, we would:
        // 1. Create a conversation record in the database.
        // 2. Get the new conversation's UUID.

        // For now, we'll generate a dummy UUID.
        $conversationId = Uuid::v4();

        // Generate the signed link for the candidate to start the interview.
        $appBaseUrl = $_ENV['APP_URL'] ?? 'http://localhost:8080';
        $interviewUrl = "{$appBaseUrl}/app/interview/{$conversationId}";

        $signedUrl = $this->linkSigner->generate($interviewUrl, ['conv_id' => $conversationId]);

        return Response::json([
            'message' => 'Conversation created successfully (placeholder)',
            'conversation_id' => $conversationId,
            'interview_url' => $signedUrl
        ], 201);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getOne(Request $request): Response
    {
        $conversationId = $request->getRouteParam('id');

        // Logic to fetch conversation state from DB will be added later.

        return Response::json([
            'id' => $conversationId,
            'status' => 'new',
            'messages' => [],
            'note' => 'This is a placeholder response.'
        ]);
    }
}
