<?php

declare(strict_types=1);

namespace Tests\Service;

use App\Services\PromptPackService;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Exception;

class PromptPackServiceTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('prompts');

        // Create default prompt pack
        vfsStream::create([
            'default' => [
                'system.md' => 'Default system prompt.',
                'company.yaml' => "name: DefaultCorp\ntone: neutral",
                'override_me.md' => 'This should be overridden.',
            ],
            'job123' => [
                'override_me.md' => 'This is the override.',
                'job_specific.md' => 'This is specific to job123.',
            ],
            'empty_job' => []
        ], $this->root);
    }

    public function testLoadDefaultPack(): void
    {
        $service = new PromptPackService($this->root->url());
        $prompts = $service->load();

        $this->assertCount(3, $prompts);
        $this->assertEquals('Default system prompt.', $prompts['system']);
        $this->assertEquals(['name' => 'DefaultCorp', 'tone' => 'neutral'], $prompts['company']);
        $this->assertEquals('This should be overridden.', $prompts['override_me']);
    }

    public function testLoadPackWithOverrides(): void
    {
        $service = new PromptPackService($this->root->url());
        $prompts = $service->load('job123');

        $this->assertCount(4, $prompts);
        // From default
        $this->assertEquals('Default system prompt.', $prompts['system']);
        // Overridden
        $this->assertEquals('This is the override.', $prompts['override_me']);
        // From job-specific
        $this->assertEquals('This is specific to job123.', $prompts['job_specific']);
    }

    public function testLoadPackWithNonExistentJobId(): void
    {
        $service = new PromptPackService($this->root->url());
        // 'job456' directory does not exist, should fall back to only defaults
        $prompts = $service->load('job456');

        $this->assertCount(3, $prompts);
        $this->assertEquals('Default system prompt.', $prompts['system']);
        $this->assertArrayNotHasKey('job_specific', $prompts);
    }

    public function testLoadPackWithEmptyJobDir(): void
    {
        $service = new PromptPackService($this->root->url());
        // 'empty_job' directory exists but is empty
        $prompts = $service->load('empty_job');

        $this->assertCount(3, $prompts);
        $this->assertEquals('Default system prompt.', $prompts['system']);
    }

    public function testInvalidJobIdThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid job ID format.");

        $service = new PromptPackService($this->root->url());
        $service->load('../../../etc/passwd');
    }

    public function testNonExistentBasePathThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Prompt base path does not exist");

        new PromptPackService('/non/existent/path');
    }
}
