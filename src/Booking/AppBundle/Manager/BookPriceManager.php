<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 11/08/2017
 * Time: 12:07
 */

namespace Booking\AppBundle\Manager;

use Booking\AppBundle\Entity\Book;

class BookPriceManager {

    private const CP_NULL = -1;
    private const CP_BASE = 0;
    private const CP_ALL = 1;

    /**
     * @var Book
     */
    private $book;

    /**
     * @var int
     */
    private $ttc;

    /**
     * @var int
     */
    private $ht;

    /**
     * @var int
     */
    private $extras;

    /**
     * BookPriceManager constructor.
     * @param Book $book
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
        $this->computed = self::CP_NULL;
        $this->initPrices();
    }

    public function getTotal(bool $force = false) {
        $this->checkCompute($force);
        return $this->extras + $this->ttc;
    }

    public function getHT(bool $force = false) {
        $this->checkCompute($force);
        return $this->ht;
    }

    public function getTTC(bool $force = false) {
        $this->checkCompute($force);
        return $this->ttc;
    }

    public function compute() {
        $this->computeBase();
        $this->computeExtras();
    }

    public function computeBase() {
        $this->initPrices();
        foreach ($this->book->getProducts() as $product) {
            $this->ttc += $product->getProductType()->getPrice()->getAmount(true, $this->book->getClient());
            $this->ht += $product->getProductType()->getPrice()->getAmount(false, $this->book->getClient());
        }
        $this->computed = self::CP_BASE;
    }

    public function computeExtras() {
        if ($this->computed < self::CP_BASE)
            $this->computeBase();
        $this->extras = 0;
        foreach ($this->book->getTaxes() as $tax) {
            $this->extras += $tax->getPrice()->appliedTo($this->ttc);
        }
        $this->computed = self::CP_ALL;
    }

    private function initPrices() {
        $this->ttc = 0;
        $this->ht = 0;
        $this->extras = 0;
    }

    private function checkCompute(bool $force) {
        if ($force || $this->computed == self::CP_NULL)
            $this->compute();
    }

}