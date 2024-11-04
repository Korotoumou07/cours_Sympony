<?php

namespace App\Controller;

use App\Entity\Dette;
use App\Form\DetteType;
use App\Repository\ClientRepository;
use Pagerfanta\Pagerfanta;
use App\Repository\DetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter as DoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetteController extends AbstractController
{

    #[Route('/dette', name: 'dette')]
    public function index(DetteRepository $detteRepository): Response
    {
        // Récupère la liste des clients
        $dettes = $detteRepository->findAll();

        return $this->render('dette/index.html.twig', [
            'dettes' => $dettes,
        ]);
    }

    #[Route('/dette-form', name: 'app_dette_add')]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupère la liste des clients
        $dette = new Dette();
        $form=$this->createForm(DetteType::class,$dette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($dette);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('dette');
            
        }

        return $this->render('dette/add.html.twig', [
            'formDette' => $form->createView(),
        ]);
    }

}
// class DetteController extends AbstractController
// {

//     private DetteRepository $detteRepository;
//     private ClientRepository $clientRepository;

//     // Constructeur pour injecter le DetteRepository
//     public function __construct(DetteRepository $detteRepository, ClientRepository $clientRepository)
//     {
//         $this->detteRepository = $detteRepository;
//         $this->clientRepository = $clientRepository;
//     }
//     #[Route('/dette', name: 'dette')]
// public function index(Request $request, EntityManagerInterface $entityManager): Response
// {
//     // Créer la requête pour récupérer toutes les dettes
//     $queryBuilder = $this->detteRepository->createQueryBuilder('d')
//         ->orderBy('d.createAt', 'ASC');

//     // Configuration de la pagination avec Pagerfanta
//     $adapter = new DoctrineORMAdapter($queryBuilder);
//     $pagerfanta = new Pagerfanta($adapter);

//     // Récupérer la page actuelle depuis la requête
//     $currentPage = $request->query->getInt('page', 1);
//     $pagerfanta->setMaxPerPage(5);
//     $pagerfanta->setCurrentPage($currentPage);

//     // Calculer le montant total de toutes les dettes
//     $totalMontantRestant = $entityManager->createQueryBuilder()
//         ->select('SUM(d.montant - d.montantVerser)')
//         ->from(Dette::class, 'd')
//         ->getQuery()
//         ->getSingleScalarResult();

//     return $this->render('dette/index.html.twig', [
//         'dettes' => $pagerfanta,
//         'totalMontantRestant' => $totalMontantRestant,
//     ]);
// }

    
//     #[Route('/client/{id}/dette', name: 'client_dette')]
//     public function showClientDettes(int $id): Response
//     {
//         // Récupérer les dettes pour le client spécifique
//         $dettes = $this->detteRepository->findBy(['client' => $id]);

//         return $this->render('dette/client_dette.html.twig', [
//             'dettes' => $dettes, // Passer les dettes du client à la vue
//         ]);
//     }
//     #[Route('/dette/ajouter', name: 'app_dette_add')]
// public function addDebt(Request $request,EntityManagerInterface $entityManager): Response
// {
//     // Récupérer tous les clients
//     $clients = $this->clientRepository->findAll();

//     // Vérifier si le formulaire a été soumis
//     if ($request->isMethod('POST')) {
//         $clientId = $request->request->get('client_id');
//         $client = $this->clientRepository->find($clientId);

//         if (!$client) {
//             throw $this->createNotFoundException('Client non trouvé');
//         }

//         // Création de la nouvelle dette
//         $dette = new Dette();
//         $montant = $request->request->get('montant');
//         $montantVerser = $request->request->get('montantVerser');
//         $createAt = new \DateTimeImmutable($request->request->get('createAt'));
//         // Remplir les données de la dette
//         $dette->setMontant($montant);
//         $dette->setMontantVerser($montantVerser);
//         $dette->setCreateAt($createAt);
//         $dette->setClient($client); // Associer la dette au client

//         // Sauvegarder la dette dans la base de données
        
//         $entityManager->persist($dette);
//         $entityManager->flush();

//         // Rediriger après la soumission réussie
//         $this->addFlash('success', 'La dette a été ajoutée avec succès.');
//         return $this->redirectToRoute('app_dette');
//     }

//     return $this->render('dette/add.html.twig', [
//         'clients' => $clients, // Passer la liste des clients au template
//     ]);
// }

// }
