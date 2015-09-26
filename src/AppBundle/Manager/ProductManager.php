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
        $this->ratesCaller = $ratesCaller;
    }

    /**
     * @param Product  $product
     * @param Currency $currency
     *
     * @return string
     *
     * @todo: convert product current currency to the one wanted.
     */
    public function formatPrice(Product $product, Currency $currency = null)
    {
        if (!($currency instanceof Currency)) {
            $currency = $this->basketManager->getCurrencyWanted();
        }

        $request = $this->requestStack->getCurrentRequest();
        $locale  = $request->getLocale();

        $price = $product->getPrice();

        if ($currency->getCode() !== $product->getCurrency()->getCode()) {
            $rate = $this->ratesCaller->getRateFor($product->getCurrency(), $currency);
            $price = $rate * $price;
        }

        $formater = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $price    = $formater->formatCurrency($price, $currency->getCode());

        return $price;
    }
}
