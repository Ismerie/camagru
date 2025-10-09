<?php

class Controller {
    protected function render($view, $data = []) {
        extract($data);
        require_once APP . '/views/header.php';
        require_once APP . '/views/' . $view . '.php';
        require_once APP . '/views/footer.php';
    }
}
