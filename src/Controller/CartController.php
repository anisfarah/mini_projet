<?php

namespace App\Controller;

use App\Entity\CommandeAnnule;
use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{

    /**
     * @Route("/commande/{numTable}/{nomSC}", name="index")
     */
    public function index(SessionInterface $session, $nomSC, $numTable)
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
            'categories' => $categories, 'sc' => $sc, 'produits' => $produits, 'numTable' => $numTable
        ]);
    }


    /**
     * @Route("/add/{id}/{numTable}", name="cart_add")
     */
    public function add(SessionInterface $session, $id, $numTable, Request $request)
    {

        // On récupère le panier actuel
        $panier = $session->get($numTable, []);
        $table = $this->getDoctrine()->getRepository('App:Tables')->find($numTable);

        $em = $this->getDoctrine()->getManager();
        $table->setDisponibilite(0);
        $em->persist($table);
        $em->flush();


        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set($numTable, $panier);


        $sessionCuisine = new Session();
        $sessionBar = new Session();
        $cat = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nomCategorie' => ["cuisine", "pizza"]]);

        $souscategorie = $this->getDoctrine()->getRepository(SousCategorie::class)->findby(["categorie" => [
            $cat[0]->getIdCategorie(),
            $cat[1]->getIdCategorie()
        ]]);
        $produit = $this->getDoctrine()->getRepository('App:Produit')->find($id);

        $scbyproduct = $this->getDoctrine()->getRepository(SousCategorie::class)->find(($produit->getSousCategorie())->getIdSouscat());

        if (in_array($scbyproduct, $souscategorie)) {
            $sessionCuisine->getFlashBag()->add('notice', $produit->getNomProd() . ' a été ajouté');
        } else {
            $sessionBar->getFlashBag()->add('noticebar', $produit->getNomProd() . ' a été ajouté');
        }

        //sessionbar
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/remove/{numTable}/{id}", name="cart_remove")
     */
    public function remove(Produit $product, Request $request, SessionInterface $session, $numTable)
    {
        // On récupère le panier actuel
        $panier = $session->get($numTable, []);
        $id = $product->getIdProduit();
        $table = $this->getDoctrine()->getRepository('App:Tables')->find($numTable);


        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
        if (count($panier) == 0) {
            $em = $this->getDoctrine()->getManager();
            $table->setDisponibilite(1);
            $em->persist($table);
            $em->flush();
        }

        // On sauvegarde dans la session
        $session->set($numTable, $panier);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/delete/{numTable}/{id}", name="cart_delete")
     */
    public function delete(Produit $product, Request $request, SessionInterface $session, $numTable)
    {
        // On récupère le panier actuel
        $panier = $session->get($numTable, []);
        $id = $product->getIdProduit();
        $table = $this->getDoctrine()->getRepository('App:Tables')->find($numTable);


        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }


        $em = $this->getDoctrine()->getManager();
        $table->setDisponibilite(1);
        $em->persist($table);
        $em->flush();




        // On sauvegarde dans la session
        $session->set($numTable, $panier);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @Route("/delete/{numTable}", name="cart_delete_all")
     */
    public function deleteAll(SessionInterface $session, Request $request, $numTable)
    {

        $date = new \DateTime('@' . strtotime('now'));
        $table = $this->getDoctrine()->getRepository('App:Tables')->find($numTable);



        $em = $this->getDoctrine()->getManager();
        $commandeAnnule = new CommandeAnnule();
        $commandeAnnule->setDateAnnulation($date);
        $commandeAnnule->setPrixTot((float) $request->get('tot'));
        $table->setDisponibilite(1);
        $em->persist($commandeAnnule);
        $em->persist($table);
        $em->flush();
        $session->remove($numTable);


        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
