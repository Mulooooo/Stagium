<?php
namespace App\Controllers;
use App\Models\OfferModel;

class HomeController extends Controller{
    public function index(){
        $offerModel = new OfferModel();
        $offers = $offerModel->getActiveOffers(1, 3);
        $this->render("home.html.twig", ['latest_offers' => $offers['items']]);
    }
}