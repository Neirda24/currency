<?php

namespace AppBundle\Controller;

use AppBundle\Form\ChooseCurrencyType;
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

    /**
     * @Route("/settings", name="edit_settings")
     * @Template()
     */
    public function settingsAction(Request $request)
    {
        $form = $this->createForm(new ChooseCurrencyType(), null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $currency = $data['currency'];

            $basketManager = $this->get('app.manager.basket');
            $basketManager->setCurrencyWanted($currency);
            $basketManager->saveBasketInSession();

            return $this->redirectToRoute('list_products');
        }

        return [
            'choose_currency_form' => $form->createView(),
        ];
    }
}
