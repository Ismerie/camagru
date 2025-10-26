<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require_once APP . '/views/header.php';

        $viewsWithBackground = ['home', 'signup', 'login'];
        if (in_array($view, $viewsWithBackground)) {
            require_once APP . '/views/background.php';
        }

        require_once APP . '/views/' . $view . '.php';
        require_once APP . '/views/footer.php';
    }
}
