<?php

namespace App\Pharmaciegros\Service;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Location;
use App\Repository\StockmovementRepository;

class StockManager
{
    private StockmovementRepository $movementRepo;

    public function __construct(StockmovementRepository $movementRepo)
    {
        $this->movementRepo = $movementRepo;
    }

    /**
     * Calcule le stock réel d’un produit dans un lieu donné
     */
    public function getCurrentStock(Product $product, Location $location): int
    {
        $movements = $this->movementRepo->findBy([
            'product' => $product,
            'location' => $location,
        ]);

        $total = 0;
        foreach ($movements as $mvt) {
            $sign = in_array($mvt->getType(), ['RECEPTION', 'TRANSFER_IN']) ? +1 : -1;
            $total += $sign * $mvt->getQuantity();
        }

        return $total;
    }
}
