<?php

namespace App\Controller;

use App\Dto\ClientFormSearch;
use App\Entity\Client;
use App\Form\ClientFormSearchType;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client/liste', name: 'client.index')]
    public function index(ClientRepository $clientRepository,Request $request): Response
    {
        $clientFormSearch= new ClientFormSearch;
        $searchClientform=$this->createForm(ClientFormSearchType::class,$clientFormSearch);
        $searchClientform->handleRequest($request);
        $clients=$clientRepository->findAll();
        if ($searchClientform->isSubmitted()){
            $surname=$clientFormSearch->getSurname();
            $telephone=$clientFormSearch->getTelephone();
            $statut=$clientFormSearch->getStatut();
           if ($surname!="") {
           $clients=$clientRepository->findBy(['surname'=>$surname]);
           }
           if ($telephone!="") {
            $clients=$clientRepository->findBy(['telephone'=>$telephone]);
            }
            if ($statut !="Tout") {
                // $user=$statut=="Oui"?:null;
                $clients=$clientRepository->findByClientWithOrUser($statut);
                }

        }
        $page = $request->query->getInt('page',1);
        $limit=10;
        $clients=$clientRepository->findAllClients($page,$limit);
        $count=$clients->count();

        $nbrePages=ceil($count/$limit);
        

        



        return $this->render('client/index.html.twig', [
            
            'dataClients' => $clients,
            'searchClientform'=>$searchClientform,
            'nbrePages'=>$nbrePages,
            'page'=>$page
        ]);
    }

    #[Route('/client/create', name: 'client.create')]
    public function create(Request $request,EntityManagerInterface $entityManagerInterface): Response
    {

        $client=new Client();
        $form=$this->createForm(ClientType::class,$client);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($client);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('client.index');
            
        }

        return $this->render('client/add.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
}