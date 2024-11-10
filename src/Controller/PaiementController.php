<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\DetteRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaiementController extends AbstractController
{
    #[Route('/paiements/dette/{idDette}', name: 'paiement.index')]
    public function index($idDette,Request $request,PaiementRepository $paiementRepository,DetteRepository $detteRepository,EntityManagerInterface $entityManagerInterface): Response
    {

        $dette=$detteRepository->find($idDette);

        $page = $request->query->getInt('page',1);
        $limit=5;
        $paiements=$paiementRepository->findByDette($idDette,$page,$limit);
        $count=$paiements->count();

        $nbrePages=ceil($count/$limit);
        
        
        $paiement=new Paiement();
        $form=$this->createForm(PaiementType::class,$paiement);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $montantRestant=$dette->getMontant()-$dette->getMontantVerse();
            if ($paiement->getMontant()>$montantRestant) {
                $this->addFlash('error', 'Le montant du paiement doit etre inferieur au montant restant!');
                
            }else {
                $dette->setMontantVerse($dette->getMontantVerse()+$paiement->getMontant());
                $paiement->setDette($dette);
                $entityManagerInterface->persist($paiement);
                $entityManagerInterface->flush();
            }
         

            return $this->redirectToRoute('paiement.index',[
                "idDette"=>$idDette
            ]);
            
        }

        return $this->render('paiement/index.html.twig', [
            'paiements'=>$paiements,
            'nbrePages'=>$nbrePages,
            'page'=>$page,
            'dette'=>$dette,
            'form'=>$form,
            'disabled'=>$dette->getMontant()==$dette->getMontantVerse()
        ]);


    }


    

}