<?php
namespace App\Controllers;

class HomeController extends Controller{
    public function index(){
        $this->render("home.html.twig", ['title' => 'Hello World!']);
    }
}