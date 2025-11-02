<?php

require_once __DIR__ . '/../core/Database.php'; //pdo

class AuthController extends Controller {
    // --- VUES ---
    public function loginForm() {
        $this->render('login', ['title' => 'Login'], ['backgroundSquare.js', 'auth/login.js']);
    }

    public function signupForm() {
        $this->render('signup', ['title' => 'Signup'], ['backgroundSquare.js', 'auth/signup.js']);
    }


    // --- API ---
    public static function register($data)
    {
        // 1️⃣ Validation de base
        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email et mot de passe requis']);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        // 2️⃣ Vérifier si l'utilisateur existe déjà
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Cet utilisateur existe déjà']);
            return;
        }

        // 3️⃣ Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 4️⃣ Insérer en base
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $hashedPassword]);

        http_response_code(201);
        echo json_encode(['message' => 'Utilisateur créé avec succès']);
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