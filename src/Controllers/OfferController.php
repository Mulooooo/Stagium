<?php
namespace App\Controllers;
use App\Controllers\Controller;
use App\Models\OfferModel;

class OfferController extends Controller{
    public function index() {
        $offerModel = new OfferModel();
        $offers = $offerModel->getActiveOffers();
        $this->render("offers/index.html.twig", ['offers' => $offers]);
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
            $this->render("offers/show.html.twig", ['offer' => $offer]);
        }
    }
}