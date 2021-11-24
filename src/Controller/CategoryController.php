<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    

      /**
     * @Route("/admin/allCategory", name="liste_categorie")
     */
    public function getAllCategory(): Response
    {
        $allCat = $this->getDoctrine()->getRepository('App:Categorie')->findAll();
        return $this->render('category/allcategory.html.twig',['allCat' => $allCat]);
    }


    /**
     * @Route("/admin/addcategory", name="add_category")
     */
    public function addcategory(): Response
    {
        return $this->render('category/addcategory.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }



     /**
     * @Route("/Postaddcategory", name="Postadd_category")
     */
    public function ajouterCategory(Request $request): Response
    {

        $categorie=new Categorie();
         
        $categorie->setNomCategorie($request->get('cat_name'));
        $em=$this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        $this->addFlash(
            'success',
            'Categorie a été ajouté'
        );

    
        return $this->redirectToRoute('liste_categorie');

    }
    


     /**
     * @Route("/suppCategory/{id}", name="supp_category")
     */
    public function SupprimerCategory(Request $request,$id): Response
    {

       
        $categorie= $this->getDoctrine()->getRepository(Categorie::class)->find($id); 
       
       
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('liste_categorie');
    }




    /**
     * @Route("admin/modifycategory/{id}", name="modify_category")
     */
    public function ModifyCategory($id): Response
    {
        $categorie= $this->getDoctrine()->getRepository(Categorie::class)->find($id); 
        return $this->render('category/modifyCat.html.twig',['id'=>$id,'nom'=> $categorie->getNomCategorie()]); 
    }


    /**
     * @Route("/PosteditCategory/{id}", name="Postedit_category")
     */
    public function PostModifyCategory(Request $request,$id): Response
    {

       
        $categorie= $this->getDoctrine()->getRepository(Categorie::class)->find($id); 
       
        
        $em=$this->getDoctrine()->getManager();
        $categorie->setNomCategorie($request->get('cat_name'));     
        $em->persist($categorie);
        $em->flush();
        
        return $this->redirectToRoute('liste_categorie');
    }








    


}
