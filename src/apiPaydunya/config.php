<?php 
namespace App\apiPayndunya;
use Paydunya\Checkout;
use Paydunya\CustomData;
use Paydunya\Paydunya;
use Paydunya\Setup;
use Paydunya\Utilities;

Setup::setMasterKey("sO4bJTB1-VGno-uwPH-HRMT-6C5H4kNFu8Qn");
Setup::setPrivateKey("live_private_542NI1K1OydZQRPB1mE2uWFgraY");
Setup::setToken("9tIQowOQQxlNEhQaVIzl");
Setup::setMode("live");
Setup::setPublicKey("live_public_eGHyRPJlnkWZpCRWoeaqorkgTcM");
//Configuration des informations de votre service/entreprise
//Setup::setMasterKey("sO4bJTB1-VGno-uwPH-HRMT-6C5H4kNFu8Qn");
//Setup::setPublicKey("test_public_tzTd3klc9WYzradGceuoHmaPvKR");
//Setup::setPrivateKey("test_private_NHEmeiZn3nUAyq0sYprqRhJlYxH");
//Setup::setToken("FWqNtl4ydmUdpsCcSlKa");
//Setup::setMode("test"); // Optionnel. Utilisez cette option pour les paiements tests.
Store::setLogoUrl("https://nasrulex.com/assets/img/Nasurlex-logo.png");
Store::setName("NASRULEX"); // Seul le nom est requis
Store::setTagline("Plateforme de Documentation Juridique");
Store::setPhoneNumber("+221 77 377 77 66");
Store::setWebsiteUrl("http://nasrulex.com");

//Store::setReturnUrl("http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/confirm.php");

