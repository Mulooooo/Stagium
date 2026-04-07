<?php

namespace App\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateEngine{
    private Environment $twig;

    public function __construct() {
        $loader = new FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $this->twig = new Environment($loader);
    }

    public function render(string $template, array $data) {
        echo($this->twig->render($template, $data));
    }
}