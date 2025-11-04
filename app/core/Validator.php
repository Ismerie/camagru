<?php
require_once __DIR__ . '/Database.php';

class Validator {
    /**
     * Vérifie que tous les champs requis sont présents et non vides.
     */
    public static function required(array $data, array $fields): array {
        $missing = [];
    
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data) || trim($data[$field]) === '') {
                $missing[] = ucfirst($field) . " is required";
            }
        }
    
        return $missing;
    }

    public static function email(string $email): ?string {
        return filter_var($email, FILTER_VALIDATE_EMAIL)
            ? null
            : "Invalid email format";
    }

    public static function username(string $username): ?string {
        return (strlen($username) >= 3 && strlen($username) <= 10)
            ? null
            : "Username must be between 3 and 10 characters";
    }


    public static function password(string $password): ?string {
        $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=]).{8,}$/';
        return preg_match($regex, $password)
            ? null
            : "Password must be at least 8 characters, contain one letter, one number and one symbol";
    }

    public static function isValidRegister(array $data): bool {
        $errors = self::required($data, ['username', 'email', 'password']);
        if (!empty($errors)) 
            return false;
        if (self::email($data['email'])) 
            return false;
        if (self::username($data['username'])) 
            return false;
        if (self::password($data['password'])) 
            return false;

        return true;
    }
    
}
