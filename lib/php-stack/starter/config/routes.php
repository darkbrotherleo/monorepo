<?php
declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Application\Http\Controllers\HealthController;

return function (App $app) {
    $app->get('/', function ($req, $res) {
        $view = $this->get(\League\Plates\Engine::class);
        $res->getBody()->write($view->render('home', ['title' => 'Slim Starter']));
        return $res;
    });

    $app->get('/health', [HealthController::class, 'check']);

    $app->group('/api/v1', function (RouteCollectorProxy $group) {
        $group->get('/ping', function ($req, $res) {
            $res->getBody()->write(json_encode(['status' => 'ok', 'correlation_id' => $req->getAttribute('correlation_id')]));
            return $res->withHeader('Content-Type', 'application/json');
        });
        // Auth/payment routes will be mounted here after lib/auth+payment src is ready
    });
};
