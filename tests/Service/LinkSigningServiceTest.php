<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Services\LinkSigningService;
use PHPUnit\Framework\TestCase;
use Exception;

class LinkSigningServiceTest extends TestCase
{
    private const TEST_SECRET = 'test-secret-key';
    private const BASE_URL = 'http://localhost/interview/conv123';

    public function testGenerateReturnsUrlWithSignatureAndExpiry(): void
    {
        $service = new LinkSigningService(self::TEST_SECRET);
        $signedUrl = $service->generate(self::BASE_URL, [], 60);

        $this->assertStringContainsString(self::BASE_URL, $signedUrl);
        $this->assertStringContainsString('?exp=', $signedUrl);
        $this->assertStringContainsString('&sig=', $signedUrl);
    }

    public function testValidSignatureIsValid(): void
    {
        $service = new LinkSigningService(self::TEST_SECRET);
        $signedUrl = $service->generate(self::BASE_URL, ['user' => 'abc'], 60);

        parse_str(parse_url($signedUrl, PHP_URL_QUERY), $queryParams);

        $isValid = $service->isValid(self::BASE_URL, $queryParams);
        $this->assertTrue($isValid);
    }

    public function testTamperedUrlIsInvalid(): void
    {
        $service = new LinkSigningService(self::TEST_SECRET);
        $signedUrl = $service->generate(self::BASE_URL, ['user' => 'abc'], 60);

        // Tamper with the URL by adding a parameter
        $tamperedUrl = $signedUrl . '&admin=true';
        parse_str(parse_url($tamperedUrl, PHP_URL_QUERY), $queryParams);

        $isValid = $service->isValid(self::BASE_URL, $queryParams);
        $this->assertFalse($isValid);
    }

    public function testExpiredUrlIsInvalid(): void
    {
        $service = new LinkSigningService(self::TEST_SECRET);
        // Create a link that expired 1 second ago
        $signedUrl = $service->generate(self::BASE_URL, [], -1);

        parse_str(parse_url($signedUrl, PHP_URL_QUERY), $queryParams);

        $isValid = $service->isValid(self::BASE_URL, $queryParams);
        $this->assertFalse($isValid);
    }

    public function testMissingSignatureIsInvalid(): void
    {
        $service = new LinkSigningService(self::TEST_SECRET);
        $queryParams = ['exp' => time() + 60]; // No 'sig'

        $isValid = $service->isValid(self::BASE_URL, $queryParams);
        $this->assertFalse($isValid);
    }

    public function testMissingSecretThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('LINK_SECRET environment variable is not configured.');

        new LinkSigningService('');
    }
}
