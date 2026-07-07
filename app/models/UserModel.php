<?php

class UserModel {
    public static function findByEmail($email) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function findByUsername($username) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($username, $email, $hashedPassword) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $success = $stmt->execute([$username, $email, $hashedPassword]);
        return $success ? (int)$pdo->lastInsertId() : false;
    }

    public static function setVerificationToken($userId, $token) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
        return $stmt->execute([$token, $userId]);
    }

    public static function findByVerificationToken($token) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function markVerified($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}
