<?php

namespace App\Filament\Admin\Resources\WebsitePages\Support;

class SeoAnalyzer
{
    private const MIN_TITLE_LENGTH = 30;
    private const MAX_TITLE_LENGTH = 60;
    private const MIN_DESCRIPTION_LENGTH = 120;
    private const MAX_DESCRIPTION_LENGTH = 160;

    public static function analyze(string $title, string $description, string $content): array
    {
        $titleLength = mb_strlen($title);
        $descriptionLength = mb_strlen($description);
        $contentLength = mb_strlen(strip_tags($content));
        $wordCount = str_word_count(strip_tags($content));
        $readingTimeMinutes = ceil($wordCount / 200);

        return [
            'title' => [
                'length' => $titleLength,
                'status' => self::getStatus($titleLength, self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH),
                'message' => "Title length: {$titleLength} characters",
            ],
            'description' => [
                'length' => $descriptionLength,
                'status' => self::getStatus($descriptionLength, self::MIN_DESCRIPTION_LENGTH, self::MAX_DESCRIPTION_LENGTH),
                'message' => "Description length: {$descriptionLength} characters",
            ],
            'content' => [
                'length' => $contentLength,
                'word_count' => $wordCount,
                'reading_time' => $readingTimeMinutes,
                'status' => $wordCount < 100 ? 'warning' : 'good',
                'message' => "{$wordCount} words, ~{$readingTimeMinutes} min read",
            ],
            'overall_score' => self::calculateScore($title, $description, $content),
        ];
    }

    private static function getStatus(int $length, int $min, int $max): string
    {
        if ($length === 0) {
            return 'error';
        }
        if ($length < $min || $length > $max) {
            return 'warning';
        }

        return 'good';
    }

    private static function calculateScore(string $title, string $description, string $content): int
    {
        $score = 0;

        $titleLength = mb_strlen($title);
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 25;
        } elseif ($titleLength > 0) {
            $score += 12;
        }

        $descriptionLength = mb_strlen($description);
        if ($descriptionLength >= 120 && $descriptionLength <= 160) {
            $score += 25;
        } elseif ($descriptionLength > 0) {
            $score += 12;
        }

        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount >= 100) {
            $score += 25;
        } elseif ($wordCount >= 50) {
            $score += 12;
        }

        $sentences = preg_match_all('/[.!?]+/', $content);
        $avgWordPerSentence = $sentences > 0 ? $wordCount / $sentences : 0;
        if ($avgWordPerSentence >= 10 && $avgWordPerSentence <= 20) {
            $score += 25;
        } elseif ($avgWordPerSentence > 0) {
            $score += 12;
        }

        return min($score, 100);
    }
}

