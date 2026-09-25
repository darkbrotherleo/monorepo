<?php
declare(strict_types=1);

namespace Monorepo\Viking;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Thin Guzzle adapter to external OpenViking server.
 * Server runs outside monorepo (docker ghcr.io/volcengine/openviking:latest) — AGPL stays server-side.
 * Mirrors SDK Python/TS: add_resource → poll task, find/search/ls/read.
 */
class VikingClient
{
    private Client $http;

    public function __construct(
        private string $baseUrl,
        private string $apiKey,
        private string $account = 'default',
        private string $user = 'local-dev',
        private string $actorPeerId = 'php-slim',
    ) {
        $this->http = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'timeout'  => 30,
            'headers'  => [
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
                'X-Account'     => $account,
                'X-User'        => $user,
                'X-Actor-Peer'  => $actorPeerId,
            ],
        ]);
    }

    /** @throws GuzzleException */
    public function addResource(string $path, string $wing = 'default'): array
    {
        $res = $this->http->post('resources', ['json' => ['path' => $path, 'wing' => $wing]]);
        return json_decode((string)$res->getBody(), true);
    }

    public function pollTask(string $taskId, int $timeoutSec = 60): array
    {
        $t0 = time();
        do {
            $res = $this->http->get("tasks/{$taskId}");
            $data = json_decode((string)$res->getBody(), true);
            if (($data['status'] ?? '') !== 'running') return $data;
            sleep(1);
        } while (time() - $t0 < $timeoutSec);
        return $data;
    }

    /** viking:// aware — ls/tree */
    public function ls(string $vikingPath = 'viking://'): array
    {
        $res = $this->http->get('fs/ls', ['query' => ['path' => $vikingPath]]);
        return json_decode((string)$res->getBody(), true);
    }

    public function read(string $vikingPath): string
    {
        $res = $this->http->get('fs/read', ['query' => ['path' => $vikingPath]]);
        return (string)$res->getBody();
    }

    /** directory-scoped find vs semantic search */
    public function find(string $query, string $scope = 'viking://', int $limit = 10): array
    {
        $res = $this->http->post('search/find', ['json' => ['query' => $query, 'scope' => $scope, 'limit' => $limit]]);
        return json_decode((string)$res->getBody(), true);
    }

    public function search(string $query, int $limit = 10): array
    {
        $res = $this->http->post('search', ['json' => ['query' => $query, 'limit' => $limit]]);
        return json_decode((string)$res->getBody(), true);
    }
}
