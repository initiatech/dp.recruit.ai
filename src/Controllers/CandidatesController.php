<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class CandidatesController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        // Logic to create a candidate will be implemented in a later step.
        // For now, return a placeholder response.
        $data = $request->getBody();

        // Basic validation example
        if (empty($data['full_name']) || empty($data['email'])) {
            return Response::error('Full name and email are required.', 400);
        }

        return Response::json(['message' => 'Candidate created successfully (placeholder)', 'data' => $data], 201);
    }
}
