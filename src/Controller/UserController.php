<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[Route('/user', name: 'user')]
    public function index(UserRepository $userRepository,Request $request): Response
    {
        // Récupère la liste des clients
        // $users = $userRepository->findAll();
        $page = $request->query->getInt('page',1);
        $limit=5;
        $users=$userRepository->findAllUsers($page,$limit);
        $count=$users->count();

        $nbrePages=ceil($count/$limit);
        

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'nbrePages'=>$nbrePages,
            'page'=>$page
        ]);
    }

    #[Route('/user-form', name: 'app_user_add')]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupère la liste des clients
        $user = new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('user');
            
        }

        return $this->render('user/add.html.twig', [
            'formUser' => $form->createView(),
        ]);
    }
}


// class UserController extends AbstractController
// {

//     private UserRepository $userRepository;

//     // Constructeur pour injecter le UserRepository
//     public function __construct(UserRepository $userRepository)
//     {
//         $this->userRepository = $userRepository;
//     }    
//     #[Route('/user', name: 'app_user')]
//     public function index(): Response
//     {
//         // Récupérer tous les utilisateurs triés par nom
//         $users = $this->userRepository->findAllUsers(); 
//         return $this->render('user/index.html.twig', [
//             'users' => $users,
//         ]);
//     }
//     #[Route('/user/add', name: 'app_user_add')]
// public function addUser(Request $request, EntityManagerInterface $entityManager): Response
// {
//     if ($request->isMethod('POST')) {
//         $nom = $request->request->get('nom');
//         $prenom = $request->request->get('prenom');
//         $login = $request->request->get('login');
//         $password = $request->request->get('password'); // Make sure to hash this password

//         // Basic validation to ensure all fields are filled
//         if (!empty($nom) && !empty($prenom)&& !empty($login) && !empty($password)) {
//             // Create new User object
//             $user = new User();
//             $user->setNom($nom);
//             $user->setPrenom($prenom);
//             $user->setLogin($login);
//             $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Hash the password

//             // Save to database
//             $entityManager->persist($user);
//             $entityManager->flush();

//             // Redirect to the user list or a confirmation page
//             return $this->redirectToRoute('app_user'); // Change to your user list route
//         } else {
//             $this->addFlash('error', 'Tous les champs sont requis.');
//         }
//     }

//     return $this->render('user/add.html.twig'); // Adjust your template path
// }

// }
