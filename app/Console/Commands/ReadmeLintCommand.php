<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ReadmeLintCommand extends Command
{
    protected $signature = 'readme:lint';

    protected $description = 'Validate README policy: only root README.md outside README/ and all README/*.md files are indexed in root README.md';

    public function handle(): int
    {
        $basePath = base_path();
        $rootReadmePath = $basePath . DIRECTORY_SEPARATOR . 'README.md';
        $readmeDir = $basePath . DIRECTORY_SEPARATOR . 'README';

        if (! is_file($rootReadmePath)) {
            $this->error('Missing root README.md');
            return self::FAILURE;
        }

        if (! is_dir($readmeDir)) {
            $this->error('Missing README directory');
            return self::FAILURE;
        }

        $rootReadmeContent = file_get_contents($rootReadmePath) ?: '';
        $errors = [];

        // Rule 1: no project markdown files outside README/ except root README.md.
        foreach ($this->allProjectMarkdownFiles($basePath) as $absolutePath) {
            $relative = str_replace($basePath . DIRECTORY_SEPARATOR, '', $absolutePath);
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);

            if ($relative === 'README.md') {
                continue;
            }

            if (! str_starts_with($relative, 'README/')) {
                $errors[] = "Markdown file outside README directory: {$relative}";
            }
        }

        // Rule 2: every README/*.md file should be referenced in root README.md.
        foreach (glob($readmeDir . DIRECTORY_SEPARATOR . '*.md') ?: [] as $docPath) {
            $relativeDoc = 'README/' . basename($docPath);
            if (! str_contains($rootReadmeContent, $relativeDoc)) {
                $errors[] = "Root README.md does not reference {$relativeDoc}";
            }
        }

        // Rule 3: README.md references to README/*.md should exist.
        if (preg_match_all('/README\/[A-Za-z0-9_\-.]+\.md/', $rootReadmeContent, $matches)) {
            $referenced = array_unique($matches[0]);
            foreach ($referenced as $relativeDoc) {
                if (str_contains($relativeDoc, 'FEATURE_X_')) {
                    continue;
                }

                $absoluteDoc = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeDoc);
                if (! is_file($absoluteDoc)) {
                    $errors[] = "Root README.md references missing file: {$relativeDoc}";
                }
            }
        }

        if (! empty($errors)) {
            $this->error('README lint failed:');
            foreach ($errors as $error) {
                $this->line("- {$error}");
            }

            return self::FAILURE;
        }

        $this->info('README lint passed.');
        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    protected function allProjectMarkdownFiles(string $basePath): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            if (strtolower($file->getExtension()) !== 'md') {
                continue;
            }

            $path = $file->getPathname();
            $normalized = str_replace('\\', '/', $path);

            if (
                str_contains($normalized, '/vendor/') ||
                str_contains($normalized, '/node_modules/') ||
                str_contains($normalized, '/.git/')
            ) {
                continue;
            }

            $files[] = $path;
        }

        return $files;
    }
}

