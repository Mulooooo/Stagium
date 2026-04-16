<?php
namespace App\Controllers;
use App\Core\TemplateEngine;

class AuthController {
    public function login() {
        $render = new TemplateEngine;
        $render->render("auth/login.html.twig", ['title' => 'Login']);
    }
}