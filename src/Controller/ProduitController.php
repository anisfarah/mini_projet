<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{

  /**
     * @Route("/admin/allproduit", name="liste_produit")
     */
    public function getAllProduit(): Response
    {
        $prods = $this->getDoctrine()->getRepository('App:Produit')->findAll();
        return $this->render('produit/allProduit.html.twig',['prods' => $prods]);
    }





    /**
     * @Route("/admin/addproduit", name="add_prod")
     */
    public function ajouterProduit(): Response
    {
        $allsousCat= $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();

        return $this->render('produit/addProduit.html.twig', [
            'allsousCat'=> $allsousCat
        ]);
    }
 

    /**
     * @Route("/Postaddproduit", name="Postadd_prod")
     */
    public function PostajouterProduit(Request $request): Response
    {
        $sous = $this->getDoctrine()->getRepository(SousCategorie::class)->find($request->get('lissouscat'));

        $produit=new Produit();
         
        $produit->setnomProd($request->get('sousCat_name'));
        $produit->setImage($request->get('myfile')); 
        $produit->setPu($request->get('prod_px'));
        $produit->setRemise($request->get('prod_remise'));
       
        $produit->setSousCategorie($sous);
       
        $em=$this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        

         
        return $this->redirectToRoute('liste_produit');
    }


/**
     * @Route("admin/modifyproduit/{id}", name="modify_produit")
     */
    public function ModifyProd($id): Response
    {
        $produit= $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $allsousCat = $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();


        return $this->render('produit/modifyproduit.html.twig',['id'=>$id,'produit'=> $produit,
        'souscategory'=>$produit->getSousCategorie(),'allsousCat'=>$allsousCat]); 
    }


    /**
     * @Route("/Posteditproduit/{id}", name="Postedit_produit")
     */
    public function PostModifyProd(Request $request,$id): Response
    {

       
        $produit= $this->getDoctrine()->getRepository(Produit::class)->find($id);

        $souscategorie= $this->getDoctrine()->getRepository(SousCategorie::class)->find($request->get('id_souscat'));

       
        
        $em=$this->getDoctrine()->getManager();
        $produit->setNomProd($request->get('prod_name'));
        $produit->setImage($request->get('myfile'));
        $produit->setPu($request->get('prod_px'));
        $produit->setRemise($request->get('prod_remise'));
        $produit->setSousCategorie($souscategorie);
       

        $em->persist($produit);
        $em->flush();
        
        return $this->redirectToRoute('liste_produit');
    }





 /**
     * @Route("/suppProduit/{id}", name="supp_produit")
     */
    public function SupprimerProduit(Request $request,$id): Response
    {

       
        $produit= $this->getDoctrine()->getRepository(Produit::class)->find($id); 
       
       
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute('liste_produit');
    }


}
