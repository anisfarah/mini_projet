<?php

namespace App\Controller;

use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmpController extends AbstractController
{
    /**
     * @Route("/employe/add", name="emp")
     */
    public function Postemp(): Response
    {
        return $this->render('emp/addemploye.html.twig', []);
    }


  

    /**
     * @Route("/employe/edit/{id}", name="page_edit")
     */
    public function Posteditemp($id): Response
    {
        $emp = $this->getDoctrine()->getRepository(Employe::class)->find($id);
        return $this->render('emp/editemp.html.twig', ['id'=>$id,'emp'=>$emp]);
    }


    /**
     * @Route("/emp/add", name="add_emp")
     */
    public function addEmploye(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $emp = new Employe();
        $emp->setUsername($request->get('username'));

        $encoded = $encoder->encodePassword($emp, $request->get('password'));
        $emp->setPassword($encoded);
        $tab = [];
        $roles = $request->get('roles');
        array_push($tab, $roles);
        $emp->setRoles($tab);
        $em = $this->getDoctrine()->getManager();
        $em->persist($emp);
        $em->flush();

        return $this->redirectToRoute('employe_index');
    }


    /**
     * @Route("/emp/remove/{id}", name="remove_emp")
     */
    public function removeEmploye(Request $request, $id): Response
    {
        $employe = $this->getDoctrine()->getRepository(Employe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($employe);
        $em->flush();
        return $this->redirectToRoute('employe_index');
    }


    /**
     * @Route("/emp/edit/{id}", name="edit_emp")
     */
    public function EditEmploye(Request $request, $id,UserPasswordEncoderInterface $encoder): Response
    {
        $emp = $this->getDoctrine()->getRepository(Employe::class)->find($id);
        $emp->setUsername($request->get('username'));

        $encoded = $encoder->encodePassword($emp, $request->get('password'));
        $emp->setPassword($encoded);
        $tab = [];
        $roles = $request->get('roles');
        array_push($tab, $roles);
        $emp->setRoles($tab);
        $em = $this->getDoctrine()->getManager();
        $em->persist($emp);
        $em->flush();

        return $this->render('emp/editemp.html.twig', ['emp'=>$emp]);
    }


}
