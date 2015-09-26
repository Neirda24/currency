<?php

namespace AppBundle\Caller;

use AppBundle\Client\JsonRatesClient;
use AppBundle\Entity\Currency;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RatesCaller
 *
 * Used to retrieve the rate between two currencies.
 *
 * @author Adrien Schaegis <adrien@iron-mail.net>
 */
class RatesCaller
{
    /**
     * @var JsonRatesClient
     */
    protected $jsonRatesClient;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor.
     *
     * @param JsonRatesClient $jsonRatesClient
     * @param string          $apiKey
     */
    public function __construct(JsonRatesClient $jsonRatesClient, $apiKey)
    {
        $this->jsonRatesClient = $jsonRatesClient;
        $this->apiKey          = $apiKey;
    }

    /**
     * Retrieve the rate for the given currencies.
     * Return false if something went wrong.
     *
     * @param Currency $from
     * @param Currency $to
     *
     * @return float|bool
     */
    public function getRateFor(Currency $from, Currency $to)
    {
        $uri      = '/get/?access_key=' . $this->apiKey . '&from=' . $from->getCode() . '&to=' . $to->getCode();
        $response = $this->jsonRatesClient->get($uri);

        $reponseStatus = (int)$response->getStatusCode();
        $responseBody  = $response->getBody()->getContents();

        if (Response::HTTP_OK === $reponseStatus) {
            $rate = (float)json_decode($responseBody, true);

            return $rate;
        }

        return false;
    }
}
