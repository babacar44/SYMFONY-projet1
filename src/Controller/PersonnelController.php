<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Personnel;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CategoryService;
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
     * @Route("/personnel/new", name="ajout_personne")
     * @Route("/personnel/{id}/edit", name="edit_personne")
     */
    public function form(Personnel $personnes=null, Request $request, ObjectManager $manager){
       
        if (!$personnes) {
            $personnes = new Personnel();
        }
       
       $form = $this->createFormBuilder($personnes);
                   $form->add('nomComplet');
                   $form->add('matricule');
                   $form->add('dateNaissance', DateType::class, [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                ]);
                   $form->add('salaire');
                   $form->add('category',EntityType::class, [
                    'class' => CategoryService::class,
                    'choice_label' => 'description']);
                   $form=$form->getForm();

        $form->handleRequest($request);  
        
        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($personnes);
            $manager->flush();
           
            return $this->redirectToRoute('personnel_list', ['id' => $personnes->getId()]);
        }

            return $this->render('personnel/create.html.twig', [
                'formPersonnel' => $form->createView(),
                'editMode' => $personnes->getId() !== null
            ]);
} 
            /**
             * @Route("/personnel/delete/{id}", name="employe_delete")
             * 
             * @return Response
             */


            public function delete(Personnel $personnes){

                $manager = $this->getDoctrine()->getManager();

                $manager->remove($personnes);
                $manager->flush();

                return new Response('Employe supprimé');
            }
                /**
                 * @Route("/", name="show")
                 */
                public function show(){
                    return $this->render('/personnel/show.html.twig');
                }
}
