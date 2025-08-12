<?php

namespace App\Pharmaciegros\Service;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Stockmovement;
use App\Pharmaciegros\Entity\Transfer;
use App\Repository\StockmovementRepository;
use App\Shared\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;

class StockManager
{
    private StockmovementRepository $movementRepo;
    private EntityManagerInterface $em;

    public function __construct(StockmovementRepository $movementRepo, EntityManagerInterface $em)
    {
        $this->movementRepo = $movementRepo;
        $this->em = $em;
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

    public function applyTransfert(Transfer $transfert): void
    {
        if ($transfert->getStatus() !== 'Reçu') {
            return;
        }

        $source = $transfert->getSourceLocation();
        $destination = $transfert->getDestinationLocation();
        $now = new \DateTimeImmutable();

        foreach ($transfert->getLigne() as $ligne) {
            $produit = $ligne->getProduct();
            $quantite = $ligne->getQuantity();

            // SORTIE
            $sortie = new Stockmovement();
            $sortie->setProduct($produit);
            $sortie->setQuantity($quantite);
            $sortie->setType('SORTIE');
            $sortie->setLocation($source);
            $this->em->persist($sortie);

            // ENTREE
            $entree = new Stockmovement();
            $entree->setProduct($produit);
            $entree->setQuantity($quantite);
            $entree->setType('ENTREE');
            $entree->setLocation($destination);
            $this->em->persist($entree);
        }

        $transfert->setStatus('Terminé');
        $this->em->persist($transfert);
        $this->em->flush();
    }

    public function applyRequest(Transfer $transfert): void
    {
        if ($transfert->getStatus() !== 'Reçu') {
            return;
        }

        $source = $transfert->getSourceLocation();
        $destination = $transfert->getDestinationLocation();
        $now = new \DateTimeImmutable();

        foreach ($transfert->getLigne() as $ligne) {
            $produit = $ligne->getProduct();
            $quantite = $ligne->getQuantity();

            // SORTIE
            $sortie = new Stockmovement();
            $sortie->setProduct($produit);
            $sortie->setQuantity($quantite);
            $sortie->setType('ENTREE');
            $sortie->setLocation($source);
            $this->em->persist($sortie);

            // ENTREE
            $entree = new Stockmovement();
            $entree->setProduct($produit);
            $entree->setQuantity($quantite);
            $entree->setType('SORTIE');
            $entree->setLocation($destination);
            $this->em->persist($entree);
        }

        $transfert->setStatus('Terminé');
        $this->em->persist($transfert);
        $this->em->flush();
    }

    /**
     * Calcule le stock actuel d’un produit dans une location donnée
     */
    public function getStockActuel(Product $product, Location $location): int
    {
        $repo = $this->em->getRepository(Stockmovement::class);

        $entrees = $repo->createQueryBuilder('m')
            ->select('SUM(m.quantity)')
            ->where('m.product = :product')
            ->andWhere('m.location = :location')
            ->andWhere('m.type = :type')
            ->setParameter('product', $product)
            ->setParameter('location', $location)
            ->setParameter('type', 'ENTREE')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        $sorties = $repo->createQueryBuilder('m')
            ->select('SUM(m.quantity)')
            ->where('m.product = :product')
            ->andWhere('m.location = :location')
            ->andWhere('m.type = :type')
            ->setParameter('product', $product)
            ->setParameter('location', $location)
            ->setParameter('type', 'SORTIE')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        return (int)$entrees - (int)$sorties;
    }

}
