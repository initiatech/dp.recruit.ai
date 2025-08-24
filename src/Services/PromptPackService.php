<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads prompt packs from the filesystem.
 * A prompt pack is a directory containing markdown (.md) and YAML (.yml, .yaml) files.
 * This service can load a 'default' pack and override it with a job-specific pack.
 */
class PromptPackService
{
    private string $basePath;

    /**
     * @param string $basePath The base directory where prompt packs are stored.
     */
    public function __construct(string $basePath = null)
    {
        $this->basePath = $basePath ?? realpath(__DIR__ . '/../../config/prompts');
        if (!is_dir($this->basePath)) {
            throw new Exception("Prompt base path does not exist: {$this->basePath}");
        }
    }

    /**
     * Loads a prompt pack.
     *
     * @param string|null $jobId The ID of the job to load a specific pack for. If null, only the default pack is loaded.
     * @return array An associative array where keys are the prompt filenames (without extension) and values are their content.
     * @throws Exception
     */
    public function load(string $jobId = null): array
    {
        $defaultPath = $this->basePath . '/default';
        if (!is_dir($defaultPath)) {
            throw new Exception("Default prompt pack not found at: {$defaultPath}");
        }

        $prompts = $this->loadPromptsFromPath($defaultPath);

        if ($jobId) {
            // Sanitize job ID to prevent directory traversal attacks
            $safeJobId = basename($jobId);
            if ($safeJobId !== $jobId) {
                 throw new Exception("Invalid job ID format.");
            }

            $jobPath = $this->basePath . '/' . $safeJobId;
            if (is_dir($jobPath)) {
                $jobPrompts = $this->loadPromptsFromPath($jobPath);
                // array_merge will overwrite defaults with job-specific prompts
                $prompts = array_merge($prompts, $jobPrompts);
            }
        }

        return $prompts;
    }

    /**
     * Loads all .md and .yaml files from a given directory path.
     *
     * @param string $path The directory path to scan.
     * @return array The loaded prompts.
     */
    private function loadPromptsFromPath(string $path): array
    {
        $prompts = [];
        $files = scandir($path);
        if ($files === false) {
            return [];
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $path . '/' . $file;
            if (!is_file($filePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $filename = pathinfo($filePath, PATHINFO_FILENAME);

            if ($extension === 'md') {
                $prompts[$filename] = file_get_contents($filePath);
            } elseif ($extension === 'yaml' || $extension === 'yml') {
                $prompts[$filename] = Yaml::parseFile($filePath);
            }
        }

        return $prompts;
    }
}
