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
use phpDocumentor\Reflection\Types\This;


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
             * @Route("/personnel/service", name="service_list")
             * @Route("/personnel/{id}/modifier", name="edit_service")
             */

            public function job(CategoryService $services=null, Request $request, ObjectManager $manager){
                if (!$services) {
                   $services = new CategoryService();
                }

                $form = $this->createFormBuilder($services);
                        $form->add('description');
                $form=$form->getForm();
                
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $manager->persist($services);
                    $manager->flush();

                    return $this->redirectToRoute('/personnel/listservice.html.twig', ['id' => $services->getId()]);
                }

                return $this->render('personnel/service.html.twig', [
                    'formService' => $form->createView(),
                    'editMode' => $services->getId() !== null
                ]);
            }

                 /**
                 * @Route("/personnel/listservice", name="service_list")
                 * @Method({"GET"})
                 */
                public function indexService()
                {

                    $services= $this->getDoctrine()->getRepository(CategoryService::class)->findAll();
                    return $this->render('personnel/listservice.html.twig', [
                        'services' => $services,
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
                
                return $this->redirectToRoute('personnel_list', ['id' => $personnes->getId()]);

            }
                /**
                 * @Route("/", name="show")
                 */
                public function show(){
                    return $this->render('/personnel/show.html.twig');
                }
}
