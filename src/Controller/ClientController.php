<?php

namespace App\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter as DoctrineORMAdapter;
use App\Entity\Client;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ClientController extends AbstractController
{

    // Constructeur pour injecter le ClientRepository
    private ClientRepository $clientRepository;
    private DetteRepository $detteRepository; // Ajoutez ceci

    public function __construct(ClientRepository $clientRepository, DetteRepository $detteRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->detteRepository = $detteRepository; // Initialisez le repository
    }
    #[Route('/client-liste', name: 'app_client')]
public function index(Request $request): Response
{
    // Créer la requête pour récupérer les clients
    $queryBuilder = $this->clientRepository->createQueryBuilder('c')
        ->orderBy('c.id', 'ASC'); // Trier par ordre croissant

    // Configuration de la pagination avec Pagerfanta
    $adapter = new DoctrineORMAdapter($queryBuilder);
    $pagerfanta = new Pagerfanta($adapter);

    // Récupérer la page actuelle depuis la requête
    $currentPage = $request->query->getInt('page', 1);
    $pagerfanta->setMaxPerPage(5); // Nombre d'éléments par page

    // Vérifier si la page demandée existe
    $maxPages = $pagerfanta->getNbPages();
    if ($currentPage > $maxPages) {
        // Si la page demandée dépasse le nombre de pages, définir la page à la dernière page disponible
        $currentPage = $maxPages;
    } elseif ($currentPage < 1) {
        // Si la page demandée est inférieure à 1, définir la page à la première page
        $currentPage = 1;
    }

    // Définir la page actuelle
    $pagerfanta->setCurrentPage($currentPage);

    return $this->render('client/index.html.twig', [
        'clients' => $pagerfanta,
    ]);
}


    
    #[Route('/client/add', name: 'app_client_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $surname = $request->request->get('surname');
            $telephone = $request->request->get('telephone');
            $adresse = $request->request->get('adresse');

            // Validation basique pour vérifier que tous les champs sont remplis
            if (!empty($surname) && !empty($telephone) && !empty($adresse)) {
                // Création du nouvel objet Client
                $client = new Client();
                $client->setSurname($surname);
                $client->setTelephone($telephone);
                $client->setAdresse($adresse);

                // Enregistrement dans la base de données
                $entityManager->persist($client);
                $entityManager->flush();

                // Rediriger vers la liste des clients ou une autre page de confirmation
                return $this->redirectToRoute('app_client');
            } else {
                $this->addFlash('error', 'Tous les champs sont requis.');
            }
        }

        // Afficher le formulaire d'ajout
        return $this->render('client/add.html.twig');
    }
    
#[Route('/client/{id}/dettes', name: 'app_client_dettes')]
public function dettes(int $id, Request $request): Response
{
    // Récupérer le client
    $client = $this->clientRepository->find($id);
    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé');
    }

    // Calculer le montant total des dettes restantes pour toutes les dettes du client
    $totalMontantRestant = $this->detteRepository->createQueryBuilder('d')
        ->select('SUM(d.montant - d.montantVerser) as total')
        ->where('d.client = :client')
        ->setParameter('client', $client)
        ->getQuery()
        ->getSingleScalarResult();

    // Création de la requête pour récupérer les dettes du client
    $queryBuilder = $this->detteRepository->createQueryBuilder('d')
        ->where('d.client = :client')
        ->setParameter('client', $client)
        ->orderBy('d.createAt', 'ASC');

    // Création de l'adaptateur pour Pagerfanta
    $adapter = new DoctrineORMAdapter($queryBuilder);
    $pagerfanta = new Pagerfanta($adapter);

    // Nombre d'éléments par page
    $itemsPerPage = 2;
    $pagerfanta->setMaxPerPage($itemsPerPage);

    // Récupérer la page actuelle depuis la requête
    $currentPage = $request->query->getInt('page', 1);
    $totalPages = $pagerfanta->getNbPages();
    if ($currentPage < 1 || $currentPage > $totalPages) {
        $currentPage = 1;
    }
    $pagerfanta->setCurrentPage($currentPage);

    // Rendu de la vue
    return $this->render('client/dette.html.twig', [
        'client' => $client,
        'dettes' => $pagerfanta,
        'totalMontantRestant' => $totalMontantRestant,
    ]);
}





    // #[Route('/client-liste', name: 'app_client')]
    // public function index(ClientRepository $clientRepository): Response
    // {
    //     // Récupère la liste des clients
    //     $clients = $clientRepository->findAllClients();

    //     return $this->render('client/index.html.twig', [
    //         'clients' => $clients,
    //     ]);
    // }

}