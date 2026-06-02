<?php

namespace App\Filament\Admin\Resources\WebsitePages\Support;

class WebsitePageBuilder
{
    private const COMMENT_PREFIX = 'PAGE_BUILDER_JSON:';

    public static function package(array $sections): string
    {
        $normalizedSections = self::normalizeSections($sections);
        $json = json_encode($normalizedSections, JSON_UNESCAPED_UNICODE);
        $encoded = base64_encode($json ?: '[]');

        return sprintf("<!--%s%s-->\n%s", self::COMMENT_PREFIX, $encoded, self::render($normalizedSections));
    }

    public static function extract(?string $content): array
    {
        if (! is_string($content) || $content === '') {
            return [];
        }

        if (! preg_match('/<!--' . self::COMMENT_PREFIX . '([A-Za-z0-9+\/=]+)-->/m', $content, $matches)) {
            return [];
        }

        $decoded = base64_decode($matches[1], true);

        if ($decoded === false) {
            return [];
        }

        $sections = json_decode($decoded, true);

        return is_array($sections) ? $sections : [];
    }

    public static function stripMeta(?string $content): string
    {
        if (! is_string($content) || $content === '') {
            return '';
        }

        $stripped = trim((string) preg_replace('/<!--' . self::COMMENT_PREFIX . '[A-Za-z0-9+\/=]+-->/m', '', $content));
        $stripped = trim($stripped, "\n");

        return $stripped === '\\n' || $stripped === '\n' ? '' : $stripped;
    }

    public static function render(array $sections): string
    {
        $html = [];

        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }

            $html[] = self::renderSection($section);
        }

        return trim(implode("\n\n", array_filter($html)));
    }

    private static function normalizeSections(array $sections): array
    {
        $normalized = [];

        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }

            $content = trim((string) ($section['content'] ?? ''));
            if ($content === '') {
                continue;
            }

            $normalized[] = [
                'section_key' => (string) ($section['section_key'] ?? ''),
                'section_title' => (string) ($section['section_title'] ?? ''),
                'section_style' => (string) ($section['section_style'] ?? 'default'),
                'content' => $content,
            ];
        }

        return $normalized;
    }

    private static function renderSection(array $section): string
    {
        $sectionClass = match ($section['section_style'] ?? 'default') {
            'muted' => 'section section-muted',
            'highlight' => 'section section-highlight',
            default => 'section',
        };

        $sectionTitle = trim((string) ($section['section_title'] ?? ''));
        $sectionHeading = $sectionTitle !== '' ? '<h2>' . e($sectionTitle) . '</h2>' : '';
        $content = trim((string) ($section['content'] ?? ''));

        $inner = trim($sectionHeading . "\n" . $content);

        return "<section class=\"{$sectionClass}\"><div class=\"container wpb-section\">{$inner}</div></section>";
    }


    public static function toSimpleSections(array $sections): array
    {
        return array_map(fn (array $section): array => [
            'section_key' => (string) ($section['section_key'] ?? ''),
            'section_title' => (string) ($section['section_title'] ?? ''),
            'section_style' => (string) ($section['section_style'] ?? 'default'),
            'content' => (string) ($section['content'] ?? ''),
        ], $sections);
    }
}

