<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(VoitureRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        {
            $rechercheVoiture = new RechercheVoiture();
            $form =$this->createForm(RechercheVoitureType::class,$rechercheVoiture);
            $form->handleRequest($request);
    
            $voitures = $paginatorInterface->paginate(
                $repo->findAllWithPagination($rechercheVoiture), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                6 /*limit per page*/
            );
            return $this->render('voiture/voitures.html.twig', [
                'voitures' => $voitures,
                "form" => $form->createView(),
                'admin'=>true
            ]);
        }
    }
    #[Route('/admin/creation', name: 'creationVoiture')]
    #[Route('/admin/{id}', name: 'modifVoiture', methods: 'GET|POST')]
    public function modification(Voiture $voiture = null,Request $request, EntityManagerInterface $ent  ): Response
    {
        if(!$voiture){
            $voiture = new Voiture();
        }
        $form = $this->createForm(VoitureType::class,$voiture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ent->persist($voiture);
            $ent->flush();
            $this->addFlash('success',"L'action a été effectué");
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/modification.html.twig', [
            'voiture' => $voiture,
            "form" => $form->createView(),
        
        ]);
}


#[Route('/admin/{id}', name: 'supVoiture', methods: 'SUP')]
    public function suppression (Voiture $voiture = null,Request $request, EntityManagerInterface $ent  ): Response
    {
        if ($this->isCsrfTokenValid("SUP".$voiture->getId(), $request->get("_token"))){
          $ent->remove($voiture);
          $ent->flush();
          $this->addFlash('success',"L'action a été effectué");
            return $this->redirectToRoute("admin");

        }
}
}