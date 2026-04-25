<?php
namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends Controller{
    public function login() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mot_de_passe = $_POST['mot_de_passe'];
            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);
            if ($user != null && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_prenom'] = $user['prenom'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: /');
                exit;
            }
            else {
                $error = "Email ou mot de passe incorrect";
            }
        }
        
        $this->render("auth/login.html.twig", ['title' => 'Login', 'error' => $error]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}