<?php

namespace AppBundle\Controller;

use AppBundle\Caller\CurrencyCaller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var CurrencyCaller $currencyCaller */
        $currencyCaller = $this->get('app.caller.list_currencies');

        $result = $currencyCaller->getCurrencies();

        echo '<pre>';
        print_r($result);
        die;
    }
}
