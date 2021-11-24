<?php

namespace App\Controller;

use App\Entity\SousCategorie;
use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CuisineBarController extends AbstractController
{
    /**
     * @Route("/cuisine/bar", name="cuisine_bar")
     */
    public function index(): Response
    {
        return $this->render('cuisine_bar/index.html.twig', [
            'controller_name' => 'CuisineBarController',
        ]);
    }


    /**
     * @Route("/cuisine", name="cuisine")
     */
    public function Cuisine(SessionInterface $session): Response
    {
        $prods = [];
        $cat = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nomCategorie' => ["cuisine", "pizza"]]);

        $souscategorie = $this->getDoctrine()->getRepository(SousCategorie::class)->findby(["categorie" => [
            $cat[0]->getIdCategorie(),
            $cat[1]->getIdCategorie()
        ]]);
        $tables = $this->getDoctrine()->getRepository('App:Tables')->findAll();
        foreach ($tables as $table) {
            $panier = $session->get($table->getNumTable(), []);

            foreach ($panier as $id => $quantite) {

                $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);
                $scbyproduct = $this->getDoctrine()->getRepository(SousCategorie::class)->find(($product->getSousCategorie())->getIdSouscat());

                if (in_array($scbyproduct, $souscategorie)) {
                    $prods[] = [
                        "produit" => $product,
                        "quantite" => $quantite
                    ];
                }
            }
        }
        return $this->render('cuisine_bar/cuisine.html.twig', [
            'prods'=>$prods
        ]);
    }


 /**
     * @Route("/bar", name="bar")
     */
    public function Bar(SessionInterface $session): Response
    {
        $prods = [];
        $cat = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nomCategorie' => ["bar"]]);

        $souscategorie = $this->getDoctrine()->getRepository(SousCategorie::class)->findby(["categorie" => [
            $cat[0]->getIdCategorie()
        ]]);
        $tables = $this->getDoctrine()->getRepository('App:Tables')->findAll();
        foreach ($tables as $table) {
            $panier = $session->get($table->getNumTable(), []);

            foreach ($panier as $id => $quantite) {

                $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);
                $scbyproduct = $this->getDoctrine()->getRepository(SousCategorie::class)->find(($product->getSousCategorie())->getIdSouscat());

                if (in_array($scbyproduct, $souscategorie)) {
                    $prods[] = [
                        "produit" => $product,
                        "quantite" => $quantite
                    ];
                }
            }
        }
        return $this->render('cuisine_bar/bar.html.twig', [
            'prods'=>$prods
        ]);
    }


}
