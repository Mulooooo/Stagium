<?php
namespace App\Controllers;

use App\Core\TemplateEngine;
use App\Models\OfferModel;

class OfferController {
    public function index() {
        $offerModel = new OfferModel();
        $offers = $offerModel->getActiveOffers();
        $render = new TemplateEngine;
        $render->render("offers/index.html.twig", ['offers' => $offers]);
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) { 
            header('Location: /offers'); 
            exit;
        }
        $offerModel = new OfferModel();
        $offer = $offerModel->getOfferById($id);
        if (!$offer) {
            http_response_code(404);
            return;
        } else {
            $render = new TemplateEngine;
            $render->render("offers/show.html.twig", ['offer' => $offer]);
        }
    }
}