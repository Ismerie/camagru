<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/setup/router.php';

$router = new Router();

registerAuthRoutes($router);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

echo "Camagru backend is running!";