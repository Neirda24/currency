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
        ];
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function formatPrice(Product $product)
    {
        return $this->productManager->formatPrice($product);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product_extension';
    }
}
