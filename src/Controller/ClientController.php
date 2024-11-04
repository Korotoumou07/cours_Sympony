<?php

namespace App\Controller;

// use Pagerfanta\Pagerfanta;
// use Pagerfanta\Doctrine\ORM\QueryAdapter as DoctrineORMAdapter;
use App\Entity\Client;
use App\Form\ClientType;
// use App\Repository\DetteRepository;
use App\Entity\ClientFormSearch;
use App\Form\ClientFormSearchType;
// use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ClientController extends AbstractController
{

    


    // #[Route('/cl', name: 'client')]
    // public function index(ClientRepository $clientRepository,Request $request): Response
    // {
    //     $clientFormSearch=new ClientFormSearch;
    //     $searchClientForm=$this->createForm(ClientFormSearchType::class,$clientFormSearch);
    //     $searchClientForm->handleRequest($request);
    //     $clients = $clientRepository->findAll();
    //     if ($searchClientForm->isSubmitted()) {
    //         $surname=$clientFormSearch->getSurname();
    //         $telephone=$clientFormSearch->getTelephone();
    //         $statut=$clientFormSearch->getStatut();
    //         if ($surname!="") {
    //             $clients = $clientRepository->findBy(['surname'=>$surname]);
    //         }
    //         if ($telephone!="") {
    //             $clients = $clientRepository->findBy(['telephone'=>$telephone]);
    //         }
    //         if ($statut!="Tout") {
    //             $clients = $clientRepository->findClientsWithUserAccount($statut);
    //         }
    //     }


    //     $currentPage = $request->query->getInt('page', 1);
    //     $limit = 10; 

    //     $offset = ($currentPage - 1) * $limit;

    //     $paginatedClients = array_slice($clients, $offset, $limit);

    //     $maxPages = ceil(count($clients) / $limit);

    //     return $this->render('client/index.html.twig', [
    //                     'clients' => $paginatedClients,
    //                     'currentPage' => $currentPage,
    //                     'maxPages' => $maxPages,
    //                     'searchClientForm'=>$searchClientForm
    //                 ]);


        
    // }
    

    #[Route('/client-form', name: 'app_client_add')]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupère la liste des clients
        $client = new Client();
        $form=$this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($client);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('client');
            
        }

        return $this->render('client/add.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
    #[Route('/client/{id}/dettes', name: 'app_client_dettes')]
    public function dettes(int $id, ClientRepository $clientRepository,DetteRepository $detteRepository, Request $request): Response
    {
        // Récupérer le client
        $client = $clientRepository->find($id);
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }
            // Calculer le montant total des dettes restantes pour toutes les dettes du client
        $totalMontantRestant = $detteRepository->findByTotalMontantRestant($client);
        // Création de la requête pour récupérer les dettes du client
        $dettes = $detteRepository->findByDettesByClient($client);
        // Définir la page actuelle et la limite
        $currentPage = $request->query->getInt('page', 1);
        $limit = 2; // Nombre d'éléments par page

        // Calculer l'offset pour la pagination
        $offset = ($currentPage - 1) * $limit;

        // Découper la liste des clients pour obtenir seulement ceux de la page courante
        $paginatedDettes = array_slice($dettes, $offset, $limit);

        // Calculer le nombre total de pages
        $maxPages = ceil(count($dettes) / $limit);

        return $this->render('client/dette.html.twig', [
                        'client' => $client,
                        'totalMontantRestant' => $totalMontantRestant,
                        'dettes' => $paginatedDettes,
                        'currentPage' => $currentPage,
                        'maxPages' => $maxPages,
                        
                    ]);


                }



    #[Route('/cl', name: 'client')]
    public function index(ClientRepository $clientRepository,Request $request): Response
    {
        $clients = $clientRepository->findAll();
        $currentPage = $request->query->getInt('page', 1);
        $limit = 10; 

        $offset = ($currentPage - 1) * $limit;

        $paginatedClients = array_slice($clients, $offset, $limit);

        $maxPages = ceil(count($clients) / $limit);

        return $this->render('client/index.html.twig', [
                        'clients' => $paginatedClients,
                        'currentPage' => $currentPage,
                        'maxPages' => $maxPages,
                    ]);


        
    }



//     // Constructeur pour injecter le ClientRepository
//     private ClientRepository $clientRepository;
//     private DetteRepository $detteRepository; // Ajoutez ceci

//     public function __construct(ClientRepository $clientRepository, DetteRepository $detteRepository)
//     {
//         $this->clientRepository = $clientRepository;
//         $this->detteRepository = $detteRepository; // Initialisez le repository
//     }
//     #[Route('/client-liste', name: 'app_client')]
// public function index(Request $request): Response
// {
//     // Créer la requête pour récupérer les clients
//     $queryBuilder = $this->clientRepository->createQueryBuilder('c')
//         ->orderBy('c.id', 'ASC'); // Trier par ordre croissant

//     // Configuration de la pagination avec Pagerfanta
//     $adapter = new DoctrineORMAdapter($queryBuilder);
//     $pagerfanta = new Pagerfanta($adapter);

//     // Récupérer la page actuelle depuis la requête
//     $currentPage = $request->query->getInt('page', 1);
//     $pagerfanta->setMaxPerPage(5); // Nombre d'éléments par page

//     // Vérifier si la page demandée existe
//     $maxPages = $pagerfanta->getNbPages();
//     if ($currentPage > $maxPages) {
//         // Si la page demandée dépasse le nombre de pages, définir la page à la dernière page disponible
//         $currentPage = $maxPages;
//     } elseif ($currentPage < 1) {
//         // Si la page demandée est inférieure à 1, définir la page à la première page
//         $currentPage = 1;
//     }

//     // Définir la page actuelle
//     $pagerfanta->setCurrentPage($currentPage);

//     return $this->render('client/index.html.twig', [
//         'clients' => $pagerfanta,
//     ]);
// }


    
//     #[Route('/client/add', name: 'app_client_add')]
//     public function add(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         if ($request->isMethod('POST')) {
//             $surname = $request->request->get('surname');
//             $telephone = $request->request->get('telephone');
//             $adresse = $request->request->get('adresse');

//             // Validation basique pour vérifier que tous les champs sont remplis
//             if (!empty($surname) && !empty($telephone) && !empty($adresse)) {
//                 // Création du nouvel objet Client
//                 $client = new Client();
//                 $client->setSurname($surname);
//                 $client->setTelephone($telephone);
//                 $client->setAdresse($adresse);

//                 // Enregistrement dans la base de données
//                 $entityManager->persist($client);
//                 $entityManager->flush();

//                 // Rediriger vers la liste des clients ou une autre page de confirmation
//                 return $this->redirectToRoute('app_client');
//             } else {
//                 $this->addFlash('error', 'Tous les champs sont requis.');
//             }
//         }

//         // Afficher le formulaire d'ajout
//         return $this->render('client/add.html.twig');
//     }
    
// #[Route('/client/{id}/dettes', name: 'app_client_dettes')]
// public function dettes(int $id, Request $request): Response
// {
//     // Récupérer le client
//     $client = $this->clientRepository->find($id);
//     if (!$client) {
//         throw $this->createNotFoundException('Client non trouvé');
//     }

//     // Calculer le montant total des dettes restantes pour toutes les dettes du client
//     $totalMontantRestant = $this->detteRepository->createQueryBuilder('d')
//         ->select('SUM(d.montant - d.montantVerser) as total')
//         ->where('d.client = :client')
//         ->setParameter('client', $client)
//         ->getQuery()
//         ->getSingleScalarResult();

//     // Création de la requête pour récupérer les dettes du client
//     $queryBuilder = $this->detteRepository->createQueryBuilder('d')
//         ->where('d.client = :client')
//         ->setParameter('client', $client)
//         ->orderBy('d.createAt', 'ASC');

//     // Création de l'adaptateur pour Pagerfanta
//     $adapter = new DoctrineORMAdapter($queryBuilder);
//     $pagerfanta = new Pagerfanta($adapter);

//     // Nombre d'éléments par page
//     $itemsPerPage = 2;
//     $pagerfanta->setMaxPerPage($itemsPerPage);

//     // Récupérer la page actuelle depuis la requête
//     $currentPage = $request->query->getInt('page', 1);
//     $totalPages = $pagerfanta->getNbPages();
//     if ($currentPage < 1 || $currentPage > $totalPages) {
//         $currentPage = 1;
//     }
//     $pagerfanta->setCurrentPage($currentPage);

//     // Rendu de la vue
//     return $this->render('client/dette.html.twig', [
//         'client' => $client,
//         'dettes' => $pagerfanta,
//         'totalMontantRestant' => $totalMontantRestant,
//     ]);
}





    
