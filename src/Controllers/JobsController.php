<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class JobsController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        // Logic to create a job will be implemented in a later step.
        $data = $request->getBody();

        if (empty($data['title']) || empty($data['required_fields_json'])) {
            return Response::error('Title and required_fields_json are required.', 400);
        }

        return Response::json(['message' => 'Job created successfully (placeholder)', 'data' => $data], 201);
    }
}
