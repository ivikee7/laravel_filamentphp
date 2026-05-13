<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive;
use Google\Service\Classroom;
use Google\Service\Directory;

class GoogleService
{
    protected GoogleClient $client;

    /**
     * @var array<int, string>
     */
    protected array $lastResolvedPathCandidates = [];

    /**
     * Create a GoogleService instance.
     *
     * @param string|null $subject Email to impersonate (domain-wide delegation)
     * @param array $scopes Optional scopes to set on the client
     */
    public function __construct(string $subject = null, array $scopes = [])
    {
        $jsonPath = $this->resolveServiceAccountJsonPath(config('services.google.service_account_json'));

        if (! $jsonPath) {
            $configured = (string) (config('services.google.service_account_json') ?? '');

            throw new \RuntimeException(
                'Google service account JSON not found. Configured GOOGLE_SERVICE_ACCOUNT_JSON="' . $configured . '". '
                . 'Checked paths: ' . implode(' | ', $this->lastResolvedPathCandidates)
            );
        }

        $this->client = new GoogleClient();
        $this->client->setAuthConfig($jsonPath);

        $defaultScopes = [
            Directory::ADMIN_DIRECTORY_USER,
            Drive::DRIVE,
            Classroom::CLASSROOM_COURSES,
            Classroom::CLASSROOM_ROSTERS,
            Classroom::CLASSROOM_COURSEWORK_ME,
        ];

        $this->client->setScopes($scopes ?: $defaultScopes);

        if ($subject) {
            $this->client->setSubject($subject);
        } elseif (config('services.google.admin_impersonate')) {
            $this->client->setSubject(config('services.google.admin_impersonate'));
        }

        $this->client->setApplicationName(config('app.name') . ' - Google API');
    }

    protected function resolveServiceAccountJsonPath(?string $configuredPath): ?string
    {
        $configuredPath = is_string($configuredPath) ? trim($configuredPath) : null;

        if (blank($configuredPath)) {
            $this->lastResolvedPathCandidates = [];

            return null;
        }

        $normalized = str_replace('\\', '/', $configuredPath);

        $candidates = array_values(array_unique(array_filter([
            $normalized,
            '/' . ltrim($normalized, '/'),
            base_path($normalized),
            public_path($normalized),
            storage_path($normalized),
            str_starts_with($normalized, 'storage/') ? base_path($normalized) : null,
            str_starts_with($normalized, 'app/') ? storage_path(substr($normalized, 4)) : null,
            str_starts_with($normalized, 'domains/') ? '/' . ltrim($normalized, '/') : null,
            str_starts_with($normalized, '/domains/') ? '/home/' . ltrim($normalized, '/') : null,
            str_starts_with($normalized, 'public/') ? base_path($normalized) : null,
            str_starts_with($normalized, 'public/') ? public_path(substr($normalized, 7)) : null,
        ])));

        $this->lastResolvedPathCandidates = $candidates;

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_readable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    public function client(): GoogleClient
    {
        return $this->client;
    }

    public function driveService(string $impersonateEmail = null): Drive
    {
        if ($impersonateEmail) {
            $this->client->setSubject($impersonateEmail);
        }

        return new Drive($this->client);
    }

    public function classroomService(string $impersonateEmail = null): Classroom
    {
        if ($impersonateEmail) {
            $this->client->setSubject($impersonateEmail);
        }

        return new Classroom($this->client);
    }

    public function directoryService(string $impersonateEmail = null): Directory
    {
        if ($impersonateEmail) {
            $this->client->setSubject($impersonateEmail);
        }

        return new Directory($this->client);
    }
}

