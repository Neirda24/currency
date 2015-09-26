<?php

namespace AppBundle\Manager;

use AppBundle\Caller\RatesCaller;
use AppBundle\Entity\Currency;
use AppBundle\Entity\Product;
use NumberFormatter;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductManager
{
    /**
     * @var BasketManager
     */
    protected $basketManager;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RatesCaller
     */
    protected $ratesCaller;

    /**
     * @param RequestStack  $requestStack
     * @param BasketManager $basketManager
     * @param RatesCaller   $ratesCaller
     */
    public function __construct(RequestStack $requestStack, BasketManager $basketManager, RatesCaller $ratesCaller)
    {
        $this->basketManager = $basketManager;
        $this->requestStack  = $requestStack;
        $this->ratesCaller   = $ratesCaller;
    }

    /**
     * @param int      $price
     * @param Currency $currency
     *
     * @return string
     */
    public function formatPrice($price, Currency $currency = null)
    {
        if (!($currency instanceof Currency)) {
            $currency = $this->basketManager->getCurrencyWanted();
        }

        $request = $this->requestStack->getCurrentRequest();
        $locale  = $request->getLocale();

        $formater = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $price    = $formater->formatCurrency($price, $currency->getCode());

        return $price;
    }

    /**
     * @param Product  $product
     * @param int      $nbProduct
     * @param Currency $currency
     *
     * @return float
     */
    public function convertPrice(Product $product, $nbProduct = 1, Currency $currency = null)
    {
        if (!($currency instanceof Currency)) {
            $currency = $this->basketManager->getCurrencyWanted();
        }
        $price = $product->getPrice() * $nbProduct;

        if ($currency->getCode() !== $product->getCurrency()->getCode()) {
            $rate  = $this->ratesCaller->getRateFor($product->getCurrency(), $currency);
            $price = $rate * $price;
        }

        return $price;
    }
}
