<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Product;
use AppBundle\Manager\BasketManager;
use AppBundle\Manager\ProductManager;

class ProductExtension extends \Twig_Extension
{
    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * Constructor.
     *
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('format_price', [$this, 'formatPrice']),
            new \Twig_SimpleFilter('convert_price', [$this, 'convertPrice']),
        ];
    }

    /**
     * @param $price
     *
     * @return string
     */
    public function formatPrice($price)
    {
        return $this->productManager->formatPrice($price);
    }

    /**
     * @param Product $product
     * @param int     $nbProduct
     *
     * @return string
     */
    public function convertPrice(Product $product, $nbProduct = 1)
    {
        return $this->productManager->convertPrice($product, $nbProduct);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product_extension';
    }
}
