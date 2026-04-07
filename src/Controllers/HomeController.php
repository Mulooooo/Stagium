<?php
namespace App\Controllers;

use App\Core\TemplateEngine;

class HomeController{
    public function index(){
        $render = new TemplateEngine;
        $render->render("home.html.twig", ['title' => 'Hello World!']);
    }
}