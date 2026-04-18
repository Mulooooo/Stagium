<?php
namespace App\Controllers;
use App\Models\CompanyModel;

class CompanyController extends Controller{
    public function index(){
        $comanyModel = new CompanyModel();
        $companies = $comanyModel->getAll();
        $this->render("companies/index.html.twig", ['companies' => $companies]);
    }
    public function show(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /companies'); 
            exit;
        }
        $comanyModel = new CompanyModel();
        $company = $comanyModel->findById($id);
        $this->render("companies/show.html.twig", ['company' => $company]);
    }
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $comanyModel = new CompanyModel();
            $company = $comanyModel->create($data);
            header('Location: /companies');
            exit;
        }
        $this->render("companies/create.html.twig");
    }
    public function edit(){
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /companies'); 
            exit;
        }
        $comanyModel = new CompanyModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $company = $comanyModel->update($id, $data);
            header('Location: /companies');
            exit;
        }
        $company = $comanyModel->findById($id);
        $this->render("companies/edit.html.twig", ['company' => $company]);
    }
    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $comanyModel = new CompanyModel();
            $companies = $comanyModel->delete($id);
            header('Location: /companies');
            exit;
        }
    }
}