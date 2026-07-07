<?php

require_once __DIR__ . '/../core/Database.php'; //pdo
require_once __DIR__ . '/../core/Mailer.php';
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
            $userId = UserModel::create($data['username'], $data['email'], $hashed);

            if (!$userId) {
                throw new Exception("Database insertion failed");
            }

            $token = bin2hex(random_bytes(32));
            UserModel::setVerificationToken($userId, $token);

            $verifyLink = "http://{$_SERVER['HTTP_HOST']}/verify?token={$token}";
            $sent = Mailer::send(
                $data['email'],
                'Confirm your Camagru account',
                '<p>Hi ' . htmlspecialchars($data['username']) . ',</p>'
                . '<p>Click the link below to confirm your Camagru account:</p>'
                . '<p><a href="' . $verifyLink . '">' . $verifyLink . '</a></p>'
            );

            if (!$sent) {
                error_log("[AuthController] Failed to send verification email to {$data['email']}");
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

    public static function verify() {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            header('Location: /login?verified=0');
            exit;
        }

        $user = UserModel::findByVerificationToken($token);

        if (!$user) {
            header('Location: /login?verified=0');
            exit;
        }

        UserModel::markVerified($user['id']);

        header('Location: /login?verified=1');
        exit;
    }

    public static function login() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $errors = Validator::required($data ?? [], ['username', 'password']);
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required']);
            return;
        }

        $user = UserModel::findByUsername($data['username']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid username or password']);
            return;
        }

        if (!$user['is_verified']) {
            http_response_code(401);
            echo json_encode(['error' => 'Please confirm your account via the email we sent you before logging in']);
            return;
        }

        $_SESSION['user_id'] = $user['id'];

        http_response_code(200);
        echo json_encode([
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
            ]
        ]);
    }

    public static function logout() {
        header('Content-Type: application/json');

        $_SESSION = [];
        session_destroy();

        http_response_code(200);
        echo json_encode(['message' => 'Logged out successfully']);
    }
}