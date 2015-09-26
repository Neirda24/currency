<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="list_products")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $productRepo = $this->get('app.repository.product');
        $products    = $productRepo->findAll();

        return [
            'products' => $products,
        ];
    }
}
