<?php

require_once __DIR__ . '/../config/database.php'; //pdo

class AuthController {
    public static function register($data) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'received' => $data
        ]);
    }

    public static function login($data) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'received' => $data
        ]);
    }

    public static function logout($data) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'received' => $data
        ]);
    }
}