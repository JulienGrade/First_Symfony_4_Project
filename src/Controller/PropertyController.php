<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

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
    public function index(PaginatorInterface $paginator, Request $request): Response
    {


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

        // ICI ON TRAITE LE FORM DES FILTRES RECHERCHES

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handlerequest($request);


        // ICI ON GERE LA PAGINATION

        $properties= $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );


        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @return Response
     */
    public function show(Property $property, string $slug): Response
    {
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301); // IMPORTANT POUR LE REFERENCEMENT PERMET DE REDIRIGER VERS LA PAGE SI INJECTION EN URL GENRE mon-premgdsqeier-bien DETECTE UN NON SLUG 
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
};