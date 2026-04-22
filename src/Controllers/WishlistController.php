<?php
namespace App\Controllers;
use App\Models\WishlistModel;

class WishlistController extends Controller{
    public function index(){
        $id = $_SESSION['user_id'];
        $wishlistModel = new WishlistModel();
        $offers = $wishlistModel->getSavedOffers($id);
        $this->render("wishlist/index.html.twig", ['offers' => $offers]); 
    }
    public function toggle(){
        $id = $_SESSION['user_id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $offerId = $_POST['offre_id'];
            $wishlistModel = new WishlistModel();
            $wishlistModel->toggle($id, $offerId);
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/offers'));
            exit;
        }
    }
}