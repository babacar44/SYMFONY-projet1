<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Personnel;
use Symfony\Component\HttpFoundation\Response;

class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel", name="personnel_list")
     * @Method({"GET"})
     */
    public function index()
    {

        $personnes= $this->getDoctrine()->getRepository(Personnel::class)->findAll();
        return $this->render('personnel/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }

/**
 * @Route("/personnel/{id}", name="personnel_show")
 */

    public function show($id){
        $personne =$this->getDoctrine()->getRepository(Personnel::class)->find($id);

        require $this->render('personnel/show.html.twig',[
            'personne' => $personne,
        ]);
    }








    // /**
    //  * @Route("/personnel/save")
    //  */
    // public function save(){
    //     $entitymanager = $this->getDoctrine()->getManager();

    //     $personne = new Personnel();
    //     $personne->setNomComplet("Aliou");
    //     $personne->setMatricule("mat46");
    //     $personne->setDateNaissance(new \DateTime());
    //     $personne->setSalaire(500000);

    //     $entitymanager->persist($personne);

    //     $entitymanager->flush();

    //     return new Response('Saves a person with the id of '.$personne->getId());

    // }
}
