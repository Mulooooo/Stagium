<?php
namespace App\Controllers;

class LegalController extends Controller {
    public function index() {
        $this->render('legal/index.html.twig');
    }
}