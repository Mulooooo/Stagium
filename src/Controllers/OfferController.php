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
}