<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\OfferModel;
use App\Models\WishlistModel;

class OfferModelTest extends TestCase {

    private OfferModel $offerModel;
    private WishlistModel $wishlistModel;

    protected function setUp(): void {
        $this->offerModel = new OfferModel();
        $this->wishlistModel = new WishlistModel();
    }

    public function testSearchOffers(): void {
        $result = $this->offerModel->searchOffers(['q' => 'PHP', 'location' => ''], 1, 6);
        foreach ($result['items'] as $offer) {
            $titreContientPhp = stripos($offer['titre'], 'PHP') !== false;
            $nomContientPhp = stripos($offer['nom'], 'PHP') !== false;
            $this->assertTrue($titreContientPhp || $nomContientPhp);
        }
    }

    public function testGetOfferById(): void {
        $result = $this->offerModel->getOfferById(1);
        $this->assertArrayHasKey('titre', $result);
        $this->assertArrayHasKey('nom', $result);
        $this->assertArrayHasKey('ville', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('skills', $result);
    }

    public function testCreateAndDeleteOffer(): void {
        $sites = $this->offerModel->getSites();
        $siteId = $sites[0]['id'];

        $data = [
            ':titre' => 'Offre Test PHPUnit',
            ':description' => 'Description test',
            ':gratification' => 500,
            ':date_debut' => '2025-09-01',
            ':duree_semaines' => 12,
            ':site_entreprise_id' => $siteId,
        ];

        $created = $this->offerModel->create($data);
        $this->assertTrue($created);

        $result = $this->offerModel->searchOffers(['q' => 'Offre Test PHPUnit', 'location' => ''], 1, 1);
        $this->assertCount(1, $result['items']);
        $id = $result['items'][0]['id'];

        $deleted = $this->offerModel->delete($id);
        $this->assertTrue($deleted);

        $result = $this->offerModel->searchOffers(['q' => 'Offre Test PHPUnit', 'location' => ''], 1, 1);
        $this->assertCount(0, $result['items']);
    }

    public function testGetStatsTotal(): void {
        $stats = $this->offerModel->getStats();
        $this->assertGreaterThan(0, $stats['total']);
    }

    public function testIsSaved(): void {
        $result = $this->wishlistModel->isSaved(99999, 99999);
        $this->assertFalse($result);
    }
}