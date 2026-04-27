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
            $studentModel = new StudentModel();
            if ($studentModel->emailExists($_POST['email'])) {
                $this->render("students/create.html.twig", ['error' => 'Cet email est déjà utilisé.']);
                return;
            }
            $data = $_POST;
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
            if ($studentModel->emailExistsForOther($_POST['email'], $id)) {
                $student = $studentModel->findById($id);
                $this->render("students/edit.html.twig", ['student' => $student, 'error' => 'Cet email est déjà utilisé.']);
                return;
            }
            $data = $_POST;
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
            $studentModel->delete($_POST['id']);
            header('Location: /students');
            exit;
        }
    }
}