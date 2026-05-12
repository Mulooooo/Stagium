<?php
namespace App\Controllers;
use App\Models\PilotModel;

class PilotController extends Controller {
    public function index(){
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $pilotModel = new PilotModel();
        $pilots = $pilotModel->getAll($page, $limit);
        $total = $pilots['total'];
        $items = $pilots['items'];
        $totalPages = ceil($total / $limit);
        $this->render("pilots/index.html.twig", ['pilots' => $items, 'total_pages' => $totalPages, 'current_page' => $page]);
    }
    public function show(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /pilots'); 
            exit;
        }
        $pilotModel = new PilotModel();
        $pilot = $pilotModel->findById($id);
        $this->render("pilots/show.html.twig", ['pilot' => $pilot]);
    }
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;
            $pilotModel = new PilotModel();
            if (!\App\Core\Csrf::verify()) {
                $error = "Jeton CSRF invalide";
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email invalide.";
            } elseif ($pilotModel->emailExists($_POST['email'])) {
                $error = 'Cet email est déjà utilisé.';
            } elseif (empty($_POST['nom'])) {
                $error = "Le nom est obligatoire.";
            } elseif (empty($_POST['prenom'])) {
                $error = "Le prénom est obligatoire.";
            } elseif (!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 8) {
                $error = "Le mot de passe doit faire au moins 8 caractères.";
            }
            if ($error) {
                $this->render('pilots/create.html.twig', ['error' => $error]);
                return;
            }
            
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT)
            ];
            $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $pilotModel->create($data);
            header('Location: /pilots');
            exit;
        }
        $this->render("pilots/create.html.twig");
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /pilots');
            exit;
        }
        $pilotModel = new PilotModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;
            $pilotModel = new PilotModel();
            if (!\App\Core\Csrf::verify()) {
                $error = "Jeton CSRF invalide";
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email invalide.";
            } elseif ($pilotModel->emailExistsForOther($_POST['email'], $id)) {
                $error = 'Cet email est déjà utilisé.';
            } elseif (empty($_POST['nom'])) {
                $error = "Le nom est obligatoire.";
            } elseif (empty($_POST['prenom'])) {
                $error = "Le prénom est obligatoire.";
            } elseif (!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 8) {
                $error = "Le mot de passe doit faire au moins 8 caractères.";
            }
            if ($error) {
                $pilot = $pilotModel->findById($id);
                $this->render('pilots/edit.html.twig', ['pilot' => $pilot, 'error' => $error]);
                return;
            }
            
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'mot_de_passe' => empty($_POST['mot_de_passe']) ? $studentModel->findById($id)['mot_de_passe'] : password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT)
            ];
            if (empty($data['mot_de_passe'])) {
                $pilot = $pilotModel->findById($id);
                $data['mot_de_passe'] = $pilot['mot_de_passe'];
            } else {
                $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            }
            $pilotModel->update($id, $data);
            header('Location: /pilots');
            exit;
        }
        $pilot = $pilotModel->findById($id);
        $this->render("pilots/edit.html.twig", ['pilot' => $pilot]);
    }
    public function delete(){
        $pilotModel = new PilotModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('pilots/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $pilotModel->delete($_POST['id']);
            header('Location: /pilots');
            exit;
        }
    }
}