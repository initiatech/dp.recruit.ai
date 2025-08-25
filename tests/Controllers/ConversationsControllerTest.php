<?php

declare(strict_types=1);

namespace Tests\Controllers;

use App\Controllers\ConversationsController;
use App\Core\Request;
use App\Services\LinkSigningService;
use PHPUnit\Framework\TestCase;

class ConversationsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        // Set dummy env vars required by the controller and service
        $_ENV['APP_URL'] = 'http://test.app';
        $_ENV['LINK_SECRET'] = 'a-very-secret-key-for-testing';
    }

    public function testCreateConversationReturnsSignedUrl(): void
    {
        $controller = new ConversationsController();

        // Simulate a request
        $requestBody = ['candidate_id' => 1, 'job_id' => 2];
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($requestBody);

        // Call the controller action
        $response = $controller->create($request);

        // This is a bit of a hack to get the response data without actually sending headers and exiting
        $reflection = new \ReflectionClass($response);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $responseData = $dataProperty->getValue($response);

        $statusCodeProperty = $reflection->getProperty('statusCode');
        $statusCodeProperty->setAccessible(true);
        $statusCode = $statusCodeProperty->getValue($response);

        // Assertions
        $this->assertEquals(201, $statusCode);
        $this->assertArrayHasKey('conversation_id', $responseData);
        $this->assertArrayHasKey('interview_url', $responseData);
        $this->assertStringContainsString('?exp=', $responseData['interview_url']);
        $this->assertStringContainsString('&sig=', $responseData['interview_url']);

        // Verify the signature is valid
        $signer = new LinkSigningService();
        $urlParts = parse_url($responseData['interview_url']);
        parse_str($urlParts['query'], $queryParams);
        $baseUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];

        $this->assertTrue($signer->isValid($baseUrl, $queryParams));
    }

    public function testCreateConversationWithMissingDataReturnsError(): void
    {
        $controller = new ConversationsController();

        // Simulate a request with missing job_id
        $requestBody = ['candidate_id' => 1];
        $request = $this->createMock(Request::class);
        $request->method('getBody')->willReturn($requestBody);

        $response = $controller->create($request);

        $statusCodeProperty = new \ReflectionProperty($response, 'statusCode');
        $statusCodeProperty->setAccessible(true);
        $statusCode = $statusCodeProperty->getValue($response);

        $this->assertEquals(400, $statusCode);
    }
}
