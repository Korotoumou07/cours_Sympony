<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetteController extends AbstractController
{
    #[Route('/dette/{idClient}/client', name: 'dette.index')]
    public function index($idClient,DetteRepository $detteRepository,Request $request): Response
    {

        $page = $request->query->getInt('page',1);
        $limit=2;
        $dettes=$detteRepository->findByClient($idClient,$page,$limit);
        $count=$dettes->count();

        $nbrePages=ceil($count/$limit);
        

        return $this->render('dette/index.html.twig', [
            'dettes'=>$dettes,
            'nbrePages'=>$nbrePages,
            'page'=>$page
        ]);
    }

    #[Route('/dettes', name: 'dette.allDettes')]
    public function allDettes(DetteRepository $detteRepository,Request $request): Response
    {

        $page = $request->query->getInt('page',1);
        $limit=8;
        $dettes=$detteRepository->findAllDettes($page,$limit);
        $count=$dettes->count();

        $nbrePages=ceil($count/$limit);
        

        return $this->render('dette/index.html.twig', [
            'dettes'=>$dettes,
            'nbrePages'=>$nbrePages,
            'page'=>$page
        ]);
    }
}