<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController{

    // On utilise l'injection afin d'enregistrer nos données en bdd
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

    /**
     * @Route("/biens", name="property.index") 
     * @return Response
     */
    public function index(): Response{


        /*
        // Indique a doctrine quel repository charger
        $repository=$this->getDoctrine()->getRepository(Property::class);
        dump($repository);

        // Envoi de données manuellement pour remplir bdd et insertion en bdd
        
        $property = new Property();
        $property->setTitle('Mon premier bien')
                ->setPrice(200000)
                ->setRooms(4)
                ->setBedrooms(3)
                ->setDescription('Une petite description')
                ->setSurface(60)
                ->setFloor(4)
                ->setHeat(1)
                ->setCity('Lille')
                ->setAddress('20 rue du 14 Juillet')
                ->setPostalCode('59000');

        $em = $this->getDoctrine()->getManager();  // On crée un entity manager afin de pouvoir envoyer nos données en bdd, on rècupère une instance de ObjectManager 
        $em->persist($property);  // on lui demande de persister notre entity
        $em->flush();   // Envoi en bdd*/
         
        // On traite le repository il existe find findOneBy voir doc
        // Ici on souhaite recuperer la liste des biens en vente donc avec sold = false
        /*
        $property=$this->repository->findAllVisible();
       
        $property[0]->setSold(true); // Detecte automatiquement les changements et les enregistre dans la bdd
        $this->em->flush();
        */
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }
};