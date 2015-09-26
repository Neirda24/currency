<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Currency;
use AppBundle\Model\Basket;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class BasketManager
 *
 * Help to manage the basket through session.
 *
 * @author Adrien Schaegis <adrien@iron-mail.net>
 */
class BasketManager
{
    /**
     * @const SESSION_KEY_BASKET
     */
    const SESSION_KEY_BASKET = 'basket.serialized';

    /**
     * @const SESSION_KEY_CURRENCY_WANTED
     */
    const SESSION_KEY_CURRENCY_WANTED = 'currency.wanted';

    /**
     * @var Basket
     */
    protected $basket;

    /**
     * @var Currency
     */
    protected $currencyWanted;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var EntityRepository
     */
    protected $currencyRepository;

    /**
     * Constructor.
     *
     * @param Session           $session
     * @param ProductRepository $productRepository
     * @param EntityRepository  $currencyRepository
     */
    public function __construct(
        Session $session,
        ProductRepository $productRepository,
        EntityRepository $currencyRepository
    ) {
        $this->session            = $session;
        $this->productRepository  = $productRepository;
        $this->basket             = null;
        $this->currencyWanted     = null;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Get the current basket
     *
     * @return Basket|null
     */
    public function getBasket()
    {
        if (!($this->basket instanceof Basket)) {
            $basketFromSession = $this->retrieveBasketFromSession();

            if ($basketFromSession instanceof Basket) {
                $this->basket = $basketFromSession;
            } else {
                $this->basket = new Basket();
            }
        }

        return $this->basket;
    }

    /**
     * Try to retrieve the basket from the session.
     * Return false if not found or an error occured while unserializing.
     *
     * @return Basket|bool
     */
    public function retrieveBasketFromSession()
    {
        $basket           = false;
        $basketSerialized = $this->session->get(static::SESSION_KEY_BASKET, null);
        if (null !== $basketSerialized) {
            $basket = $this->unserializeBasket($basketSerialized);
        }

        return $basket;
    }

    /**
     * Unserialize the string.
     * Return false if an error occur. Check the symfony logs.
     *
     * @param string $serializedBasket
     *
     * @return Basket|bool
     */
    public function unserializeBasket($serializedBasket)
    {
        $basket = new Basket();
        $data   = unserialize($serializedBasket);

        if (true === array_key_exists('products', $data)) {
            $products = $this->productRepository->retrieveByIdList($data['products']);
            $basket->setProducts($products);
        } else {
            //@todo: log error
            return false;
        }

        if (true === array_key_exists('nbPerProduct', $data)) {
            foreach ($data['nbPerProduct'] as $productId => $nb) {
                $basket->setNbProductByProductId($productId, $nb);
            }
        } else {
            //@todo: log error
            return false;
        }

        return $basket;
    }

    /**
     * Save the current basket in session.
     */
    public function saveBasketInSession()
    {
        if ($this->basket instanceof Basket) {
            $serializedBasket = $this->serializeBasket($this->basket);
            $this->session->set(static::SESSION_KEY_BASKET, $serializedBasket);
        }

        if ($this->currencyWanted instanceof Currency) {
            $this->session->set(static::SESSION_KEY_CURRENCY_WANTED, $this->currencyWanted->getId());
        }
    }

    /**
     * @param Basket $basket
     *
     * @return string
     */
    public function serializeBasket(Basket $basket)
    {
        $productIdList = $basket->getProducts()->getKeys();
        $nbPerProduct  = [];
        foreach ($productIdList as $productId) {
            $nbPerProduct[$productId] = $basket->getNbProductByIdProduct($productId);
        }

        $data = [
            'products'     => $productIdList,
            'nbPerProduct' => $nbPerProduct,
        ];

        $serializedData = serialize($data);

        return $serializedData;
    }

    /**
     * @return Currency
     */
    public function getCurrencyWanted()
    {
        if (!($this->currencyWanted instanceof Currency)) {
            $currencyFromSession = $this->retrieveCurrencyFromSession();

            if ($currencyFromSession instanceof Currency) {
                $this->currencyWanted = $currencyFromSession;
            } else {
                $this->currencyWanted = $this->retrieveDefaultCurrency();
            }
        }

        return $this->currencyWanted;
    }

    /**
     * @param Currency $currency
     *
     * @return $this
     */
    public function setCurrencyWanted(Currency $currency = null)
    {
        $this->currencyWanted = $currency;

        return $this;
    }

    /**
     * Return false if currency not found in session.
     *
     * @return Currency|bool
     */
    public function retrieveCurrencyFromSession()
    {
        $currency   = false;
        $currencyId = $this->session->get(static::SESSION_KEY_CURRENCY_WANTED, null);
        if (null !== $currencyId) {
            $currencyFromBdd = $this->currencyRepository->find($currencyId);
            if ($currencyFromBdd instanceof Currency) {
                $currency = $currencyFromBdd;
            }
        }

        return $currency;
    }

    /**
     * Retrieve the default currency.
     * If not found throw an exception.
     *
     * @return Currency
     */
    public function retrieveDefaultCurrency()
    {
        $currency = $this->currencyRepository->findOneBy([
            'code' => Currency::DEFAULT_CURRENCY,
        ]);

        if (!($currency instanceof Currency)) {
            // @todo: throw exception
        }

        return $currency;
    }
}
