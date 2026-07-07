<?php

class Controller {
    protected function render($view, $data = [], $scripts = []) {
        extract($data);
        require_once APP . '/views/layout/header.php';

        // TODO: ajouter 'gallery' et 'edit' ici une fois ces pages routées (TICKET-D4, TICKET-C1)
        $viewsWithNavbar = ['home', 'profile'];
        if (in_array($view, $viewsWithNavbar)) {
            require_once APP . '/views/layout/navbar.php';
            echo '<script type="module" src="js/navbar.js" defer></script>';
        }

        $viewsWithBackground = ['home', 'signup', 'login'];
        if (in_array($view, $viewsWithBackground)) {
            require_once APP . '/views/partials/background-square.php';
        }

        require_once APP . '/views/partials/toast-container.php';
        require_once APP . '/views/pages/' . $view . '.php';
        require_once APP . '/views/layout/footer.php';

        foreach ($scripts as $script) {
            echo '<script type="module" src="js/' . htmlspecialchars($script) . '" defer></script>';
        }
    }
}
