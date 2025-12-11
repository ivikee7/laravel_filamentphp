<?php

namespace App\Services\FaceRecognition;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class CompreFaceService
{

    protected $baseUrl;
    protected $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.compreface.host') . ':' . config('services.compreface.port') . '/api/v1/recognition';
        $this->apiKey = config('services.compreface.api_key');
    }

    public function addFace($subjectName, UploadedFile $image)
    {
        $url = "{$this->baseUrl}/subjects/{$subjectName}";

        return Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ])->attach(
            'file',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post($url);
    }

    public function recognizeFace(UploadedFile $image)
    {
        $url = "{$this->baseUrl}/recognize";

        return Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ])->attach(
            'file',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post($url);
    }
}
