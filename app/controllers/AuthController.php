<?php

require_once __DIR__ . '/../core/Database.php'; //pdo
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller {
    // --- VUES ---
    public function loginForm() {
        $this->render('login', ['title' => 'Login'], ['backgroundSquare.js', 'auth/login.js']);
    }

    public function signupForm() {
        $this->render('signup', ['title' => 'Signup'], ['backgroundSquare.js', 'auth/signup.js']);
    }


    // --- API ---
    public static function register() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!Validator::isValidRegister($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            return;
        }

        try {
            if (UserModel::findByUsername($data['username'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Username already exists']);
                return;
            }
    
            if (UserModel::findByEmail($data['email'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Email already exists']);
                return;
            }

            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $user = UserModel::create($data['username'], $data['email'], $hashed);
        
            if (!$user) {
                throw new Exception("Database insertion failed");
            }
        
            http_response_code(201);
            echo json_encode([
                'message' => 'User registered successfully'
            ]);
        
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
        }
        
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