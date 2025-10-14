<?php

class HomeController extends Controller {
    public function index() {
        $this->render('signup', ['title' => 'Bienvenue sur Camagru']);
    }
}
