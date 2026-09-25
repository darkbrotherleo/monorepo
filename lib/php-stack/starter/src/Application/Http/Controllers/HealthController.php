<?php
declare(strict_types=1);

namespace App\Application\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HealthController
{
    public function check(Request $req, Response $res): Response
    {
        $res->getBody()->write(json_encode(['status' => 'ok', 'php' => PHP_VERSION]));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
