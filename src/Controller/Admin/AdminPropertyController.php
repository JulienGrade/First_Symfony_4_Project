<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController {

    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    // Methode qui a pour but de récupérer l'ensemble des biens
    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $properties=$this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties')); // On utilise compact pour plus de facilité voir doc
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new") 
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form=$this->createForm(PropertyType::class, $property);  // Ici on passe l'entité qui contient les champs
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);     // Ici on verifie si le form est valid et soumit
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [  // Ici on transmet à la vue les property et le form
            'property' => $property,
            'form' => $form->createView()
            ]);

    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {
        $form=$this->createForm(PropertyType::class, $property);  // Ici on passe l'entité qui contient les champs
        $form->handleRequest($request); // Ici on traite l'ajout du formulaire

        if($form->isSubmitted() && $form->isValid()){     // Ici on verifie si le form est valid et soumit
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
            ]);
    }

    /**
     * @param Property $property
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoudation\RedirectResponse
     */
    public function delete(Property $property, Request $request){
        if($this->isCsrfTokenValid('delete' . $property->getId(),$request->get('_token'))){
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    } 
}