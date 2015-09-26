<?php

namespace AppBundle\Manager;

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
     * @param RequestStack  $requestStack
     * @param BasketManager $basketManager
     */
    public function __construct(RequestStack $requestStack, BasketManager $basketManager)
    {
        $this->basketManager = $basketManager;
        $this->requestStack  = $requestStack;
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

        $formater = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $price    = $formater->formatCurrency($product->getPrice(), $currency->getCode());

        return $price;
    }
}
