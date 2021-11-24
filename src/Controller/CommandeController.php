<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Employe;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\Tables;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{


    /**
     * @Route("/", name="home_caisse")
     */
    public function index(): Response
    {
        return $this->render('home_caisse/index.html.twig', [
            'controller_name' => 'HomeCaisseController',
        ]);
    }



     /**
     * @Route("/factures", name="factures")
     */
    public function factures(): Response
    {
        $cmd = $this->getDoctrine()->getRepository('App:Commande')->findAll();


        return $this->render('commande/factures.html.twig',['cmd'=>$cmd]
             );
    }


    /**
     * @Route("/commande", name="commande")
     */
    public function Commander(Request $request, SessionInterface $session): Response
    {
        $tables = $this->getDoctrine()->getRepository('App:Tables')->findAll();

        return $this->render('commande/table.html.twig', [
            'tables' => $tables

        ]);
    }


    /**
     * @Route("/commande/{numTable}", name="ProduitBytable")
     */
    public function ProduitBytable(Request $request, $numTable, SessionInterface $session): Response
    {
        $categories = $this->getDoctrine()->getRepository('App:Categorie')->findAll();
        $sc = $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();
        $tables = $this->getDoctrine()->getRepository('App:Tables')->findAll();


        $produits = [];
        $dataPanier = [];
        $total = 0;

        $panier = $session->get($numTable, []);

        foreach ($panier  as $id => $quantite) {
            $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);

            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPu() * $quantite;
        }

        return $this->render('commande/index.html.twig', [
            'categories' => $categories, 'sc' => $sc, 'produits' => $produits, 'total' => $total, 'dataPanier' => $dataPanier,
            'tables' => $tables, 'numTable' => $numTable

        ]);
    }


    /**
     * @Route("/commande/{numTable}/{nomSC}", name="find_bySousCategorie")
     */
    public function productBySousCat(Request $request, $nomSC, SessionInterface $session, $numTable): Response
    {
        $categories = $this->getDoctrine()->getRepository('App:Categorie')->findAll();
        $sc = $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();
        $dataPanier = $session->get($numTable);
        $total = 0;

        $sousCategorie = $this->getDoctrine()->getRepository(SousCategorie::class)->findOneBy(['nomSouscat' => $nomSC]);
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy(array('sousCategorie' => $sousCategorie));
        return $this->render(
            'commande/index.html.twig',
            [
                'categories' => $categories, 'sc' => $sc, 'dataPanier' => $dataPanier, 'total' => $total,
                'numTable' => $numTable
            ]
        );
    }


    /**
     * @Route("/confirmationCmd/{numTable}", name="cmd_confirmer")
     */
    public function confirmerCmd(SessionInterface $session, $numTable): Response
    {

        $dataPanier = [];
        $total = 0;
        $panier = $session->get($numTable, []);

        foreach ($panier as $id => $quantite) {
            $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);

            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite

            ];
            $total += $product->getPu() * (1 - $product->getRemise() / 100) * $quantite;
        }
        return $this->render('commande/facture.html.twig', ['dataPanier' => $dataPanier, 'total' => $total, 'numTable' => $numTable]);
    }


    /**
     * @Route("/commande/{numTable}", name="retour_panier")
     */
    public function Retourpanier($numTable): Response
    {

        return $this->redirectToRoute('ProduitBytable', ['numTable' => $numTable]);
    }


    /**
     * @Route("/Submitcommande/{numTable}", name="submit_commande")
     */
    public function SubmitCommande(Request $request, SessionInterface $session, $numTable): Response
    {
        $commande = new Commande();
        $Tables = $this->getDoctrine()->getRepository(Tables::class)->find($numTable);
        // a modifier
        $Emp = $this->getDoctrine()->getRepository(Employe::class)->find(10);
        $date = new \DateTime('@' . strtotime('now'));

        $commande->setDateCmd($date);
        $commande->setPrixTot((float) $request->get('tot'));
        $commande->setEmploye($Emp);
        $commande->setNumTable($Tables);

        $panier = $session->get($numTable, []);

        foreach ($panier as $id => $quantite) {
            $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);
            $commande->addIdProd($product);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();

        $session->remove($numTable);


        return $this->redirectToRoute('factures');
    }
}
