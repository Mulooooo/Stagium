<?php
namespace App\Controllers;
use App\Core\TemplateEngine;
use App\Models\UserModel;

class AuthController {
    public function login() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);
            if ($user != null && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: /');
                exit;
            }
            else {
                $error = "Email ou mot de passe incorrect";
            }
        }
        
        $render = new TemplateEngine;
        $render->render("auth/login.html.twig", ['title' => 'Login', 'error' => $error]);
    }
}