<?php

class Controller {
    protected function render($view, $data = [], $scripts = []) {
        extract($data);
        require_once APP . '/views/header.php';

        $viewsWithBackground = ['home', 'signup', 'login'];
        if (in_array($view, $viewsWithBackground)) {
            require_once APP . '/views/backgroundSquare.php';
        }

        require_once APP . '/views/' . $view . '.php';
        require_once APP . '/views/footer.php';

        foreach ($scripts as $script) {
            echo '<script type="module" src="js/' . htmlspecialchars($script) . '" defer></script>';
        }
    }
}
