<?php

namespace App\Controller;

use App\Entity\CommandeAnnule;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{

    /**
     * @Route("/commande/{numTable}/{nomSC}", name="index")
     */
    public function index(SessionInterface $session, $nomSC,$numTable)
    {
        $panier = $session->get($numTable, []); 
        $categories = $this->getDoctrine()->getRepository('App:Categorie')->findAll();
        $sc = $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();
        $sousCategorie = $this->getDoctrine()->getRepository(SousCategorie::class)->findOneBy(['nomSouscat' => $nomSC]);

        $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy(array('sousCategorie' => $sousCategorie));

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);

            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPu() * $quantite;
        }

        return $this->render('commande/index.html.twig', [
            'dataPanier' => $dataPanier, 'total' => $total,
            'categories' => $categories, 'sc' => $sc, 'produits' => $produits,'numTable'=>$numTable
        ]);
    }


    /**
     * @Route("/add/{id}/{numTable}", name="cart_add")
     */
    public function add(SessionInterface $session, $id, $numTable, Request $request)
    {

        // On récupère le panier actuel
        $panier = $session->get($numTable, []); 
        $tables = $this->getDoctrine()->getRepository('App:Tables')->findAll();


        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set($numTable, $panier);


        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/remove/{numTable}/{id}", name="cart_remove")
     */
    public function remove(Produit $product, Request $request, SessionInterface $session,$numTable)
    {
        // On récupère le panier actuel
        $panier = $session->get($numTable, []); 
        $id = $product->getIdProduit();

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set($numTable, $panier);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/delete/{numTable}/{id}", name="cart_delete")
     */
    public function delete(Produit $product, Request $request, SessionInterface $session,$numTable)
    {
        // On récupère le panier actuel
        $panier = $session->get($numTable, []); 
        $id = $product->getIdProduit();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set($numTable, $panier);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/delete/{numTable}", name="cart_delete_all")
     */
    public function deleteAll(SessionInterface $session, Request $request,$numTable)
    {

        $date = new \DateTime('@' . strtotime('now'));


        $em = $this->getDoctrine()->getManager();
        $commandeAnnule = new CommandeAnnule();
        $commandeAnnule->setDateAnnulation($date);
        $commandeAnnule->setPrixTot((float) $request->get('tot'));
        $em->persist($commandeAnnule);
        $em->flush();
        $session->remove($numTable);


        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
