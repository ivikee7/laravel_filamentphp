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
     * Create a GoogleService instance.
     *
     * @param string|null $subject Email to impersonate (domain-wide delegation)
     * @param array $scopes Optional scopes to set on the client
     */
    public function __construct(string $subject = null, array $scopes = [])
    {
        $jsonPath = config('services.google.service_account_json') ?? null;

        if (! $jsonPath || ! file_exists($jsonPath)) {
            throw new \RuntimeException("Google service account JSON not found at {$jsonPath}");
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

