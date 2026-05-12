<?php
namespace App\Controllers;
use App\Models\StudentModel;

class StudentController extends Controller {
    public function index(){
        $page = $_GET['page'] ?? 1;
        $limit = 6;
        $studentModel = new StudentModel();
        $students = $studentModel->getAll($page, $limit);
        $total = $students['total'];
        $items = $students['items'];
        $totalPages = ceil($total / $limit);
        $this->render("students/index.html.twig", ['students' => $items, 'total_pages' => $totalPages, 'current_page' => $page]);
    }
    public function show(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /students'); 
            exit;
        }
        $studentModel = new StudentModel();
        $student = $studentModel->findById($id);
        $this->render("students/show.html.twig", ['student' => $student]);
    }
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;
            $studentModel = new StudentModel();
            if (!\App\Core\Csrf::verify()) {
                $error = "Jeton CSRF invalide";
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email invalide.";
            } elseif ($studentModel->emailExists($_POST['email'])) {
                $error = 'Cet email est déjà utilisé.';
            } elseif (empty($_POST['nom'])) {
                $error = "Le nom est obligatoire.";
            } elseif (empty($_POST['prenom'])) {
                $error = "Le prénom est obligatoire.";
            } elseif (!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 8) {
                $error = "Le mot de passe doit faire au moins 8 caractères.";
            }

            if ($error) {
                $this->render('students/create.html.twig', ['error' => $error]);
                return;
            }
            
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'mot_de_passe' => password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT)
            ];
            $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $studentModel->create($data);
            header('Location: /students');
            exit;
        }
        $this->render("students/create.html.twig");
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /students');
            exit;
        }
        $studentModel = new StudentModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = null;
            if (!\App\Core\Csrf::verify()) {
                $error = "Jeton CSRF invalide";
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Email invalide.";
            } elseif ($studentModel->emailExistsForOther($_POST['email'], $_POST['id'])) {
                $error = 'Cet email est déjà utilisé.';
            } elseif (empty($_POST['nom'])) {
                $error = "Le nom est obligatoire.";
            } elseif (empty($_POST['prenom'])) {
                $error = "Le prénom est obligatoire.";
            } elseif (!empty($_POST['mot_de_passe']) && strlen($_POST['mot_de_passe']) < 8) {
                $error = "Le mot de passe doit faire au moins 8 caractères.";
            }

            if ($error) {
                $student = $studentModel->findById($id);
                $this->render("students/edit.html.twig", ['student' => $student, 'error' => $error]);
                return;
            }
            
            $data = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'mot_de_passe' => empty($_POST['mot_de_passe']) ? $studentModel->findById($id)['mot_de_passe'] : password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT)
            ];
            if (empty($data['mot_de_passe'])) {
                $student = $studentModel->findById($id);
                $data['mot_de_passe'] = $student['mot_de_passe'];
            } else {
                $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            }
            $studentModel->update($id, $data);
            header('Location: /students');
            exit;
        }
        $student = $studentModel->findById($id);
        $this->render("students/edit.html.twig", ['student' => $student]);
    }
    public function delete(){
        $studentModel = new StudentModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\Csrf::verify()) {
                $this->render('students/show.html.twig', ['error' => 'Jeton CSRF invalide']);
                return;
            }

            $studentModel->delete($_POST['id']);
            header('Location: /students');
            exit;
        }
    }
}