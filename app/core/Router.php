<?php
class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function __construct() {
        
        // --------------------------
        // Routes HTML (MVC)
        // --------------------------
        $this->get('/', 'HomeController', 'index');
        $this->get('login', 'AuthController', 'loginForm');
        $this->get('signup', 'AuthController', 'signupForm');
        $this->get('profile', 'UserController', 'profile');

        // --------------------------
        // Routes API (AJAX)
        // --------------------------
        $this->post('api/register', 'AuthController', 'register');
        $this->post('api/login', 'AuthController', 'login');
        $this->post('api/logout', 'AuthController', 'logout');

        $this->post('api/like', 'ApiController', 'like');
        $this->post('api/comment', 'ApiController', 'comment');
        $this->post('api/upload', 'ApiController', 'upload');
    }

    public function get($path, $controller, $action) {
        $path = trim($path, '/'); // supprime les slashs début/fin
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }
    
    public function post($path, $controller, $action) {
        $path = trim($path, '/');
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        //var_dump($uri, $this->routes['GET']); // debug

        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $controllerName = $route['controller'];
            $action = $route['action'];

            require_once APP . '/controllers/' . $controllerName . '.php';
            $controller = new $controllerName();

            if (str_starts_with($uri, 'api/')) {
                header('Content-Type: application/json');
                echo json_encode($controller->$action());
            } else {
                $controller->$action();
            }
        } else {
            http_response_code(404);
            echo "404 - Page not found";
        }
    }
}
