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
        $this->render("companies/index.html.twig", ['offers' => $offers]);
    }
    public function create(){

    }
    public function edit(){

    }
    public function delete(){

    }
}