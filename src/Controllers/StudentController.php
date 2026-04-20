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
}