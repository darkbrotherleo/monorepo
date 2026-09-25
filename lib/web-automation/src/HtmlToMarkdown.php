<?php
declare(strict_types=1);

namespace Monorepo\WebAutomation;

/** Minimal html→markdown helper (port of openbrowser turndown idea) */
class HtmlToMarkdown
{
    public static function convert(string $html): string
    {
        $text = strip_tags($html, '<h1><h2><h3><a><p><li>');
        $text = preg_replace('/<h([1-3])[^>]*>(.*?)<\/h\1>/i', '## $2', $text);
        $text = preg_replace('/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/i', '[$2]($1)', $text);
        return trim(html_entity_decode($text));
    }
}
