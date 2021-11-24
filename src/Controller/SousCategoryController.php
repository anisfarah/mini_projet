<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class SousCategoryController extends AbstractController
{


  /**
     * @Route("/admin/allsousCategory", name="liste_souscategorie")
     */
    public function getAllSousCategory(): Response
    {
        $allsousCat = $this->getDoctrine()->getRepository('App:SousCategorie')->findAll();
        $allCat = $this->getDoctrine()->getRepository('App:Categorie')->findAll();

        return $this->render('sous_category/allSousCat.htmt.twig',['allsousCat' => $allsousCat,'allCat'=>$allCat]);
    }



    /**
     * @Route("admin/souscategory", name="add_sousCat")
     */
    public function AjouterSC(): Response
    {
        $allCat = $this->getDoctrine()->getRepository('App:Categorie')->findAll();
        return $this->render('sous_category/addSous.html.twig', [
            'allCat'=> $allCat
        ]);
    }




       /**
     * @Route("admin/addSouscategory", name="Postadd_Souscat")
     */
    public function PostAjouterSC(Request $request): Response
    {

        $cat = $this->getDoctrine()->getRepository(Categorie::class)->find($request->get('liscat'));
        $Souscategorie=new SousCategorie();
         
        $Souscategorie->setNomSouscat($request->get('sousCat_name'));
        $Souscategorie->setCategorie($cat);
       
        $em=$this->getDoctrine()->getManager();
        $em->persist($Souscategorie);
        $em->flush();


        return $this->redirectToRoute('liste_souscategorie');
    }



     



    /**
     * @Route("admin/modifySouscategory/{id}", name="modify_souscategory")
     */
    public function ModifySC($id): Response
    {
        $souscategorie= $this->getDoctrine()->getRepository(SousCategorie::class)->find($id);
        $allCat = $this->getDoctrine()->getRepository('App:Categorie')->findAll();


        return $this->render('sous_category/modifySC.html.twig',['id'=>$id,'nomSousCat'=> $souscategorie->getNomSouscat(),
        'category'=>$souscategorie->getCategorie(),'allCat'=>$allCat]); 
    }


    /**
     * @Route("/PosteditSouscategory/{id}", name="Postedit_souscategory")
     */
    public function PostModifySC(Request $request,$id): Response
    {

       
        $souscategorie= $this->getDoctrine()->getRepository(SousCategorie::class)->find($id);

        $categorie= $this->getDoctrine()->getRepository(Categorie::class)->find($request->get('id_cat'));

       
        
        $em=$this->getDoctrine()->getManager();
        $souscategorie->setNomSouscat($request->get('souscat_name'));
        $souscategorie->setCategorie($categorie);     
        $em->persist($souscategorie);
        $em->flush();
        
        return $this->redirectToRoute('liste_souscategorie');
    }




    /**
     * @Route("/suppSousCategory/{id}", name="supp_souscategory")
     */
    public function SupprimerCategory(Request $request,$id): Response
    {

       
        $souscategorie= $this->getDoctrine()->getRepository(SousCategorie::class)->find($id); 
       
       
        $em=$this->getDoctrine()->getManager();
        $em->remove($souscategorie);
        $em->flush();

        return $this->redirectToRoute('liste_souscategorie');
    }




}
