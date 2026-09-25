<?php
declare(strict_types=1);

namespace Monorepo\WebAutomation;

use GuzzleHttp\Client;

/**
 * Thin bridge tới Scrapling sidecar — mirror VikingClient pattern
 * Gọi scrapling extract hoặc MCP HTTP khi sidecar chạy
 */
class ScraplingClient
{
    private Client $http;

    public function __construct(private string $baseUrl = 'http://127.0.0.1:3001') {
        $this->http = new Client(['timeout' => 30]);
    }

    /** One-shot fetch via sidecar (fallback: CLI scrapling extract) */
    public function fetch(string $url, ?string $css = null): array
    {
        try {
            $res = $this->http->post($this->baseUrl . '/fetch', ['json' => ['url' => $url, 'css' => $css]]);
            return json_decode((string)$res->getBody(), true);
        } catch (\Throwable $e) {
            // CLI fallback
            $cmd = 'scrapling extract get ' . escapeshellarg($url) . ' - --css-selector ' . escapeshellarg($css ?? 'body');
            $out = shell_exec($cmd) ?: '';
            return ['html' => $out, 'error' => $e->getMessage()];
        }
    }

    public function markdown(string $url): string
    {
        // RAG-ready markdown không cần LLM
        $data = $this->fetch($url);
        return $data['markdown'] ?? strip_tags($data['html'] ?? '');
    }
}
