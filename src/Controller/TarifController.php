<?php

namespace App\Controller;

use Paydunya\Setup;
use Paydunya\Checkout;
use Paydunya\Paydunya;
use Paydunya\Utilities;
use Paydunya\CustomData;
use Paydunya\Checkout\Store;
use App\Repository\PacksRepository;
use Paydunya\Checkout\CheckoutInvoice;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TarifController extends AbstractController
{
    
  
      /**
     * @Route("/tarif", name="tarif")
     */
    public function tarif(CategorieRepository $cat,PacksRepository $repac){
        $categories=$cat->findAll();
        $packs=$repac->findBy(array("principal" => true));
        
        return $this->render('Tarif/index.html.twig',[
            'Home' => true,
            "date"=>date_format(new \DateTime(),"Y"),
            "categories"=>$categories,
            "packs"=>$packs

        ]);

    }
     /**
     * @Route("/paiement/{id}", name="paiement")
     */
    public function paye($id,PacksRepository $repac){
    if(!$this->getUser()){
        $pack=$repac->find($id);
        $idpack=$id;
       // $iduser=$this->getUser()->getId();
 //dump($_SERVER['HTTP_HOST']);
       // dd("http://".$_SERVER['HTTP_HOST']."/status/$idpack/$iduser");
     //Store::setReturnUrl("https://".$_SERVER['HTTP_HOST']."/status/$idpack/$iduser");       
 /*     Store::setReturnUrl("http://127.0.0.1:8000/status/".$idpack."/".$iduser);
      Store::setLogoUrl("https://nasrulex.com/assets/img/Nasurlex-logo.png");
Store::setName("NASRULEX"); // Seul le nom est requis
Store::setTagline("Plateforme de Documentation Juridique");
Store::setPhoneNumber("+221 77 377 77 66");
Store::setWebsiteUrl("https://nasrulex.com");
Setup::setMasterKey("sO4bJTB1-VGno-uwPH-HRMT-6C5H4kNFu8Qn");
//Setup::setPrivateKey("live_private_542NI1K1OydZQRPB1mE2uWFgraY");
//Setup::setToken("9tIQowOQQxlNEhQaVIzl");
//Setup::setMode("live");
//Setup::setPublicKey("live_public_eGHyRPJlnkWZpCRWoeaqorkgTcM");
//Configuration des informations de votre service/entreprise
Setup::setMasterKey("sO4bJTB1-VGno-uwPH-HRMT-6C5H4kNFu8Qn");
Setup::setPublicKey("test_public_tzTd3klc9WYzradGceuoHmaPvKR");
Setup::setPrivateKey("test_private_NHEmeiZn3nUAyq0sYprqRhJlYxH");
Setup::setToken("FWqNtl4ydmUdpsCcSlKa");
Setup::setMode("test"); // Optionnel. Utilisez cette option pour les paiements tests.
Store::setLogoUrl("https://nasrulex.com/assets/img/Nasurlex-logo.png");
Store::setName("NASRULEX"); // Seul le nom est requis
Store::setTagline("Plateforme de Documentation Juridique");
Store::setPhoneNumber("+221 77 377 77 66");
Store::setWebsiteUrl("http://nasrulex.com");*/

           
        $price=$pack->getPrice();
       
        //$invoice = new CheckoutInvoice();
        $total_amount = 0;
        
        //$invoice->addItem("PACK ".$pack->getLibelle(),1,$price,$price);
        
       // $invoice->setTotalAmount($price);
        $testUrl='https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create';
        $proUrl='https://app.paydunya.com/api/v1/checkout-invoice/create';
       
        $client = HttpClient::create([
            'verify_peer' => false, 'verify_host' => false
        ]);
        $response = $client->request('POST', 'https://app.paydunya.com/sandbox-api/v1/checkout-invoice/create', 
            [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => 'application/json',
                    'PAYDUNYA-MASTER-KEY' => 'sO4bJTB1-VGno-uwPH-HRMT-6C5H4kNFu8Qn',
        
                    'PAYDUNYA-PRIVATE-KEY' => 'test_private_NHEmeiZn3nUAyq0sYprqRhJlYxH',
                
                    'PAYDUNYA-TOKEN' => 'FWqNtl4ydmUdpsCcSlKa',
                    
                ],
                
                'body' => json_encode(  [
                    "invoice"=> [
                        "items"=> [
                            "item_0"=> [
                                "name"=> "PACK ".$pack->getLibelle(),
                                "quantity"=> 1,
                                "unit_price"=> "$price",
                                "total_price"=> "$price",
                                "description"=> "Shoes made of genuine crocodile skin that hunts poverty"
                            ],
                
                        ],
                
                        "taxes"=> [
                
                        ],
                
                        "total_amount"=> $price,
                        "description"=> ""
                    ],
                
                    "store"=> [
                        "name"=> "NASRULEX",
                        "tagline"=> "Plateforme de Documentation Juridique",
                        "postal_address"=> "",
                        "phone"=> "+221 77 377 77 66",
                        "logo_url"=> "https://nasrulex.com/assets/img/Nasurlex-logo.png",
                        "website_url"=> "http://nasrulex.com"
                    ],
                
                    "custom_data"=> [
                
                    ],
                
                    "actions"=> [
                        "cancel_url"=> "",
                        "return_url"=> "",
                        "callback_url"=> ""
                    ]
                ])

              
            ]
        );
        
      dd( $response->toArray());
        
        
      /*  if($invoice->create()) {
            
            return $this->redirect($invoice->getInvoiceUrl());
         
        }else{
           dd($invoice->response_text);
        }*/
       }else{
        $this->addFlash("success","Veuillez vous inscrire avant de payer un pack");

           return $this->redirectToRoute('inscription');

       }
    }
}
