<?php

namespace AppBundle\Controller;

use AppBundle\Caller\CurrencyCaller;
use AppBundle\Manager\BasketManager;
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
        /** @var BasketManager $basketManager */
        $basketManager = $this->get('app.manager.basket');

        $result = $basketManager->getBasket();

        echo '<pre>';
        var_dump($result);
        die;
    }
}
