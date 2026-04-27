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

    public function render(string $template, array $data = []) {
        $globalData = [
            'is_logged_in' => isset($_SESSION['user_id']),

            'user_prenom' => $_SESSION['user_prenom'] ?? null,
            'user_nom' => $_SESSION['user_nom'] ?? null,
            'user_initials' => isset($_SESSION['user_prenom']) 
                ? strtoupper(substr($_SESSION['user_prenom'], 0, 1) . substr($_SESSION['user_nom'], 0, 1))
                : null,
            
            'user_role' => $_SESSION['user_role'] ?? null,
        ];
        $finalData = array_merge($globalData, $data);
        echo $this->twig->render($template, $finalData);
    }
}