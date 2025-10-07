<?php
require_once __DIR__ . '/../controllers/authController.php';

function registerAuthRoutes($router) {
    $router->add('POST', '/register', function() {
        $data = json_decode(file_get_contents("php://input"), true);
        AuthController::register($data);
    });

    $router->add('POST', '/login', function() {
        $data = json_decode(file_get_contents("php://input"), true);
        AuthController::login($data);
    });

    $router->add('POST', '/logout', function() {
        $data = json_decode(file_get_contents("php://input"), true);
        AuthController::logout($data);
    });
}
