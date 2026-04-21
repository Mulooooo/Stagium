<?php
namespace App\Controllers;
use App\Models\StudentModel;

class StudentController extends Controller {
    public function index(){
        $studentModel = new StudentModel();
        $students = $studentModel->getAll();
        $this->render("students/index.html.twig", ['students' => $students]); 
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
            $data = $_POST;
            $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $studentModel = new StudentModel();
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
            $data = $_POST;
            if (empty($data['mot_de_passe'])) {
                unset($data['mot_de_passe']);
            } else {
                $data['mot_de_passe'] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            }
            $offer = $studentModel->update($id, $data);
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