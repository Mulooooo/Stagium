<?php
namespace App\Controllers;
use App\Core\TemplateEngine;

class Controller {
    protected $twig;

    public function __construct() {
        $this->twig = new TemplateEngine;
    }

    public function render(string $template, array $data = []) {
        $this->twig->render($template, $data);
    }
}