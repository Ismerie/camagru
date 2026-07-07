<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController extends Controller {
    public function profile() {
        Auth::requireLoginOrRedirect();

        $user = UserModel::findById(Auth::id());

        $this->render('profile', [
            'title' => 'Profile',
            'username' => $user['username'],
            'email' => $user['email'],
        ]);
    }
}
