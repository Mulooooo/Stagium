<?php
namespace App\Controllers;
use App\Models\UserModel;

class ProfileController extends Controller {

    public function index() {
        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);
        $this->render('profile/index.html.twig', ['user' => $user]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('profile/index.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $userModel = new UserModel();
            $userModel->update($_SESSION['user_id'], $_POST);
            $_SESSION['user_prenom'] = $_POST['prenom'];
            $_SESSION['user_nom'] = $_POST['nom'];
            $_SESSION['user_initials'] = strtoupper(substr($_POST['prenom'], 0, 1) . substr($_POST['nom'], 0, 1));
            header('Location: /profile');
            exit;
        }
    }
}