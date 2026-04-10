<?php

namespace App\Controller;

use DateTime;
use Paydunya;
use App\Entity\Subscription;
use App\Repository\UserRepository;
use App\Repository\PacksRepository;
use Paydunya\Checkout\CheckoutInvoice;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    /**
     * @Route("/status/{idpack}/{iduser}", name="status")
     */
    public function index($idpack,$iduser,PacksRepository $repac,UserRepository $repuser,SubscriptionRepository $sub)
    {

        $em=$this->getDoctrine()->getManager();
        $co = new CheckoutInvoice();
        $pack=$repac->find($idpack);
        if ($co->confirm()){
            $date=new DateTime();
            $y=$date->format('Y');
            $d=$date->format('d');
            $m=$date->format('m');
            $datefin=new DateTime(($y+1)."-$m-$d");
            $subs=new Subscription();
            if($this->getUser()){
                $subs->setUser($this->getUser()) ;
            }else{
                $subs->setUser($repuser->find($iduser));
            }
            $allSub=$sub->findBy(["user"=>$this->getUser()]);
            if(!empty($allSub)){
                $lastSub=$allSub[count($allSub)-1];
                $lastPack=$lastSub->getPack()->getLibelle();
                $lastPrice=$lastSub->getPack()->getPrice();
                if($pack->getPrice() <= $lastPrice){
                    $this->addFlash("danger","Vous ne pouvez pas acheter ce pack ,car votre pack actuel a plus de privilèges ");
                    return $this->redirectToRoute('abonnement');
                }
            }
            $subs->setPack($pack)
                ->setDateDebut(new DateTime())
                ->setDateFin($datefin)
                ->setLinkFacture($co->getReceiptUrl());
                $em->persist($subs);
                $em->flush();
                $this->addFlash("success","Votre abonnement est validé avec succés ");
            return $this->redirectToRoute('abonnement');
        }else{
            $this->addFlash("danger","Solde Insufisant ,Recharger votre compte si vous penser que c'est une erreur Contacter nous");
            return $this->redirectToRoute('dashbord');
        }
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }
}
