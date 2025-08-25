<?php

declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * Service for generating and verifying secure, time-limited URLs.
 * It uses an HMAC signature to prevent tampering.
 */
class LinkSigningService
{
    private string $secret;

    public function __construct(string $secret = null)
    {
        $this->secret = $secret ?? $_ENV['LINK_SECRET'] ?? '';
        if (empty($this->secret)) {
            // In a real application, this should be a more specific exception type.
            throw new Exception('LINK_SECRET environment variable is not configured.');
        }
    }

    /**
     * Generates a signed URL with an expiry timestamp.
     *
     * @param string $baseUrl The base URL to sign (e.g., "http://localhost:8080/app/interview/conv_uuid").
     * @param array $params Additional parameters to include in the query string.
     * @param int $expiryTtlSeconds The number of seconds the link should be valid for.
     * @return string The full, signed URL.
     */
    public function generate(string $baseUrl, array $params = [], int $expiryTtlSeconds = 86400): string
    {
        $params['exp'] = time() + $expiryTtlSeconds;

        // Sort parameters by key to create a canonical representation
        ksort($params);
        $queryString = http_build_query($params);

        $urlToSign = rtrim($baseUrl, '/') . '?' . $queryString;

        $signature = hash_hmac('sha256', $urlToSign, $this->secret);

        return $urlToSign . '&sig=' . $signature;
    }

    /**
     * Verifies if a given URL signature is valid and not expired.
     *
     * @param string $baseUrl The base URL that was originally signed.
     * @param array $queryParams The query parameters from the incoming URL request (e.g., $_GET).
     * @return bool True if the signature is valid and the link is not expired, false otherwise.
     */
    public function isValid(string $baseUrl, array $queryParams): bool
    {
        if (empty($queryParams['sig']) || empty($queryParams['exp'])) {
            return false;
        }

        if (time() > (int)$queryParams['exp']) {
            return false; // Expired
        }

        $signatureFromUrl = $queryParams['sig'];
        unset($queryParams['sig']);

        // Rebuild the query string in the exact same way it was signed
        ksort($queryParams);
        $queryStringToVerify = http_build_query($queryParams);
        $urlToVerify = rtrim($baseUrl, '/') . '?' . $queryStringToVerify;

        $expectedSignature = hash_hmac('sha256', $urlToVerify, $this->secret);

        return hash_equals($expectedSignature, $signatureFromUrl);
    }
}
