<?php

namespace AppBundle\Caller;

use AppBundle\Client\JsonRatesClient;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CurrencyCaller
 *
 * Used to retrieve the list of currencies available.
 *
 * @author Adrien Schaegis <adrien@iron-mail.net>
 */
class CurrencyCaller
{
    /**
     * @var JsonRatesClient
     */
    protected $jsonRatesClient;

    /**
     * Constructor.
     *
     * @param JsonRatesClient $jsonRatesClient
     */
    public function __construct(JsonRatesClient $jsonRatesClient)
    {
        $this->jsonRatesClient = $jsonRatesClient;
    }

    /**
     * Retrieve all the currencies available.
     * Return false if something went wrong.
     *
     * @return array|bool
     */
    public function getCurrencies()
    {
        $response = $this->jsonRatesClient->get('currencies.json');

        $reponseStatus = (int)$response->getStatusCode();
        $responseBody  = $response->getBody()->getContents();

        if (Response::HTTP_OK === $reponseStatus) {
            $listCurrencies = json_decode($responseBody, true);

            return $listCurrencies;
        }

        return false;
    }
}
