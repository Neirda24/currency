<?php

namespace AppBundle\Model;

use AppBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Basket
{
    /**
     * @var Collection
     */
    protected $products;

    /**
     * @var int
     */
    protected $nbPerProducts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setProducts();
        $this->nbPerProducts = [];
    }

    /**
     * Get Products
     *
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set Products
     *
     * @param Product[]|array $products
     *
     * @return $this
     */
    public function setProducts(array $products = [])
    {
        if (!($this->products instanceof Collection)) {
            $this->products = new ArrayCollection();
        }

        foreach ($products as $product) {
            /** @var Product $product */
            $this->addProduct($product);
        }

        return $this;
    }

    /**
     * Add a product to the basket.
     *
     * @param Product $product
     *
     * @return $this
     */
    public function addProduct(Product $product)
    {
        if (!$this->products->containsKey($product->getId())) {
            $this->products->set($product->getId(), $product);
        }
        $this->increaseNbProduct($product);

        return $this;
    }

    /**
     * Increase the count of a product
     *
     * @param Product $product
     *
     * @return $this
     */
    protected function increaseNbProduct(Product $product)
    {
        if (false === array_key_exists($product->getId(), $this->nbPerProducts)) {
            $this->nbPerProducts[$product->getId()] = 0;
        }

        $this->nbPerProducts[$product->getId()]++;

        return $this;
    }

    /**
     * Remove a product from the basket.
     *
     * @param Product $product
     *
     * @return $this
     */
    public function removeProduct(Product $product)
    {
        if ($this->products->containsKey($product->getId())) {
            $this->products->remove($product->getId());
            $this->decreaseNbProduct($product);
        }

        return $this;
    }

    /**
     * Decrease the count of a product
     *
     * @param Product $product
     *
     * @return $this
     */
    protected function decreaseNbProduct(Product $product)
    {
        if (true === array_key_exists($product->getId(), $this->nbPerProducts)) {
            $this->nbPerProducts[$product->getId()]--;

            if ($this->nbPerProducts[$product->getId()] <= 0) {
                unset($this->nbPerProducts[$product->getId()]);
            }
        }

        return $this;
    }
}
