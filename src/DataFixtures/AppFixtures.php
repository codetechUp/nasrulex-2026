<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Packs;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture


{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $pack1=new Packs();
        $pack2=new Packs();
        $pack3=new Packs();

        $user=new User();
        $user->setRole("ROLE_ADMIN");
        $user->setCivilite("Mr");
        $user->setNomS("nasrulex.com");
        $user->setTelephone("783884597");
        $user->setUsername("ADMIN");
        $user->setPrenom("Moussa");
        $user->setEmail("Admin@nasrulex.com");
        $hsh=$this->encoder->encodePassword($user,"ADMINNASRULEX");
        $user->setPassword(($hsh));
        $manager->persist($user);
        $categorie1=new Categorie();
        $categorie2=new Categorie();
        $categorie3=new Categorie();
        $categorie4=new Categorie();
        $categorie5=new Categorie();
        $categorie6=new Categorie();
        $categorie7=new Categorie();

        $categorie1->setLibelle('Juridique');
        $categorie2->setLibelle('Fiscal');
        $categorie3->setLibelle('Social');
        $categorie4->setLibelle('Foncier');
        $categorie5->setLibelle('Affaires');
        $categorie6->setLibelle('Banques');
        $categorie7->setLibelle('Assurances');

       
       
        $pack1->setPrincipal(true);
        $pack2->setPrincipal(true);
        $pack3->setPrincipal(true);

        $pack1->setLibelle("PLATINIUM")
                ->setPrice(50000)
                ->addCategory($categorie1)
                ->addCategory($categorie2)
                ->addCategory($categorie3)
                ->addCategory($categorie4)
                ->addCategory($categorie5)
                ->addCategory($categorie6)
                ->addCategory($categorie7);

        $pack2->setLibelle("SILVER")
                ->setPrice(35000)
                ->addCategory($categorie1)
                ->addCategory($categorie2);
        $pack3->setLibelle("GOLD")
                ->setPrice(20000)
                ->addCategory($categorie1);
               

        $SousCategorie1=new SousCategorie();
        $SousCategorie2=new SousCategorie();
        $SousCategorie3=new SousCategorie();
        $SousCategorie4=new SousCategorie();
        $SousCategorie5=new SousCategorie();
        $SousCategorie6=new SousCategorie();
        $SousCategorie7=new SousCategorie();
        $SousCategorie8=new SousCategorie();
        $SousCategorie9=new SousCategorie();
        $SousCategorie10=new SousCategorie();
        $SousCategorie11=new SousCategorie();
        $SousCategorie12=new SousCategorie();
        $SousCategorie13=new SousCategorie();
        $SousCategorie14=new SousCategorie();
        $SousCategorie15=new SousCategorie();
        $SousCategorie16=new SousCategorie();
        $SousCategorie17=new SousCategorie();
        $SousCategorie18=new SousCategorie();
        $SousCategorie19=new SousCategorie();
        $SousCategorie20=new SousCategorie();
        $SousCategorie21=new SousCategorie();
        $SousCategorie22=new SousCategorie();
        $SousCategorie23=new SousCategorie();
        $SousCategorie24=new SousCategorie();
        $SousCategorie25=new SousCategorie();
    


        
        



        $SousCategorie1->setLibelle('Lois organiques');
        $SousCategorie2->setLibelle('Lois référendaires');
        $SousCategorie3->setLibelle('Lois ordinaires');
        $SousCategorie4->setLibelle('Ordonnances');
        $SousCategorie5->setLibelle('Traités-Accords');
        $SousCategorie6->setLibelle('Conventions fiscals');
        $SousCategorie7->setLibelle('Lois');
        $SousCategorie8->setLibelle('Decrets');
        $SousCategorie9->setLibelle('Arrêtés');
        $SousCategorie10->setLibelle('Circulaires');
        $SousCategorie11->setLibelle('Doctrines');
        $SousCategorie12->setLibelle(' National');
        $SousCategorie13->setLibelle('Communautaire');
        $SousCategorie14->setLibelle(' International');
        $SousCategorie15->setLibelle(' Assurances');
        $SousCategorie16->setLibelle('Banque');
        $SousCategorie17->setLibelle(' Investissement');
        $SousCategorie18->setLibelle(' Telecommunication');
        $SousCategorie19->setLibelle('Réglements');
        $SousCategorie20->setLibelle('Instructions/Avis');
        $SousCategorie21->setLibelle('Chartes');
        $SousCategorie22->setLibelle('UEMOA/OHADA');
        $SousCategorie23->setLibelle('Lois/Réglements');
        $SousCategorie24->setLibelle('Instructions/Avis');
        $SousCategorie25->setLibelle('Decrets/Arrêtés');




        

        $categorie1->addSousCategorie($SousCategorie1)
                   ->addSousCategorie($SousCategorie2)
                   ->addSousCategorie($SousCategorie3)
                   ->addSousCategorie($SousCategorie4)
                   ->addSousCategorie($SousCategorie8)
                   ->addSousCategorie($SousCategorie9)
                   ->addSousCategorie($SousCategorie10);
    





        $categorie2->addSousCategorie($SousCategorie6)
                    ->addSousCategorie($SousCategorie7)
                    ->addSousCategorie($SousCategorie8)
                    ->addSousCategorie($SousCategorie9)
                    ->addSousCategorie($SousCategorie10)
                    ->addSousCategorie($SousCategorie11);

        $categorie3->addSousCategorie($SousCategorie6)
                    ->addSousCategorie($SousCategorie7)
                    ->addSousCategorie($SousCategorie8)
                    ->addSousCategorie($SousCategorie9);
                    
        $categorie4->addSousCategorie($SousCategorie7)
                    ->addSousCategorie($SousCategorie8)
                    ->addSousCategorie($SousCategorie9);

        $categorie5->addSousCategorie($SousCategorie17)
                    ->addSousCategorie($SousCategorie22);

                    
        $categorie6->addSousCategorie($SousCategorie23)
                    ->addSousCategorie($SousCategorie24);



        $categorie7->addSousCategorie($SousCategorie7)
                    ->addSousCategorie($SousCategorie19)
                    ->addSousCategorie($SousCategorie25);
                    
                    
                    
        
       
        $manager->persist($user);
        $manager->persist($categorie1);
        $manager->persist($categorie2);
        $manager->persist($categorie3);
        $manager->persist($categorie4);
        $manager->persist($categorie5);
        $manager->persist($categorie6);
        $manager->persist($categorie7);

        $manager->persist($SousCategorie1);
        $manager->persist($SousCategorie2);
        $manager->persist($SousCategorie3);
        $manager->persist($SousCategorie4);
        $manager->persist($SousCategorie5);
        $manager->persist($SousCategorie6);
        $manager->persist($SousCategorie7);
        $manager->persist($SousCategorie8);
        $manager->persist($SousCategorie9);
        $manager->persist($SousCategorie10);
        $manager->persist($SousCategorie11);
        $manager->persist($SousCategorie12);
        $manager->persist($SousCategorie13);
        $manager->persist($SousCategorie14);
        $manager->persist($SousCategorie15);
        $manager->persist($SousCategorie16);
        $manager->persist($SousCategorie17);
        $manager->persist($SousCategorie18);
        $manager->persist($SousCategorie19);
        $manager->persist($SousCategorie20);
        $manager->persist($SousCategorie21);
        $manager->persist($SousCategorie22);
        $manager->persist($SousCategorie23);
        $manager->persist($SousCategorie24);
        $manager->persist($SousCategorie25);
       
        $manager->persist($pack1);
        $manager->persist($pack2);
        $manager->persist($pack3);




        $manager->flush();
    }
}
