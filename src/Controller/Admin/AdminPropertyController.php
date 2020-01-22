<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController {

    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
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
     * @Route("/admin/{id}", name="admin.property.edit")
     * @param Property $property
     *@return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property)
    {
        return $this->render('admin/property/edit.html.twig', compact('property'));
    }
}