<?php
// Active l'affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarre la session
session_start();

// Définit les chemins de base
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');

// Autoloader simple (optionnel, tu peux aussi require manuellement)
spl_autoload_register(function ($class) {
    $paths = [
        APP . '/core/',
        APP . '/controllers/',
        APP . '/models/'
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Charge le Router
require_once APP . '/core/Router.php';
require_once APP . '/core/Migration.php';

Migration::run();

// Exécute le routeur
$router = new Router();
$router->run();
