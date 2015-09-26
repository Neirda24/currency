<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $basketManager = $this->get('app.manager.basket');
            $basket = $basketManager->getBasket();

            foreach ($data as $name => $nb) {
                $id = (int) preg_replace('/nb_/', '', $name);

                $product = $productRepo->find($id);
                if ($product instanceof Product) {
                    $basket->addProduct($product, $nb);
                }
            }

            $basketManager->saveBasketInSession($basket);

            return $this->redirectToRoute('show_basket');
        }

        return [
            'products' => $products,
        ];
    }

    /**
     * @Route("/remove", name="remove_products", methods={"POST"})
     */
    public function removeFromBasketAction(Request $request)
    {
        $productRepo = $this->get('app.repository.product');

        $data = $request->request->all();
        $basketManager = $this->get('app.manager.basket');
        $basket = $basketManager->getBasket();

        foreach ($data as $name => $nb) {
            $id = (int) preg_replace('/nb_/', '', $name);

            $product = $productRepo->find($id);
            if ($product instanceof Product) {
                $basket->removeProduct($product, $nb);
            }
        }

        $basketManager->saveBasketInSession($basket);

        return $this->redirectToRoute('show_basket');
    }


    /**
     * @Route("/basket", name="show_basket")
     * @Template()
     */
    public function basketAction(Request $request)
    {
        $basketManager = $this->get('app.manager.basket');
        $basket = $basketManager->getBasket();

        return [
            'basket' => $basket,
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
