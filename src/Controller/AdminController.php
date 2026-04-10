<?php

namespace App\Controller;

use DateTime;
use App\Entity\Env;
use App\Entity\Packs;
use App\Form\PackType;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Entity\Subscription;
use App\Repository\EnvRepository;
use App\Repository\UserRepository;
use App\Repository\PacksRepository;
use App\Repository\DocumentRepository;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/dashbord", name="dashbord")
     */
    public function dash()
    {
        return $this->render('admin/dash.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/docs", name="addDoc")
     */
    public function addDoc(Request $req, CategorieRepository $cat,SousCategorieRepository $scat)
    {
        $categories=$cat->findAll();
        $doc=new Document();
         //creation formulaire 
         $form=$this->createForm(DocumentType::class,$doc);
         //recuperation des donnees modifies
         $form->handleRequest($req);
         if($form->isSubmitted() && $form->isValid())
         {
             $em=$this->getDoctrine()->getManager();
             $pdf=file_get_contents($doc->getFichier());
             $doc->setFichier($pdf);
             $cts= explode("-",$req->request->get('categorie'));
             $categorie=$cat->find($cts[0]);
             $scategorie=$scat->find($cts[1]);
             $doc->setCategorie($categorie);
             $doc->setSouscat($scategorie);
             //Modification des donnees dans le db
             $em->persist($doc);
             $em->flush();
             //Ajout msg alert de success
             $this->addFlash("success","Ajouter avec success");
             //Redirection
             return $this->redirectToRoute('dashbord');
 
         }
     
         return $this->render('admin/index.html.twig', [
             'form' => $form->createView(),
             'categories'=>$categories
 
         ]);
     }
    /**
     * @Route("/admin/edit/{id}", name="editDoc")
     */
    public function editDoc(Request $req,$id,DocumentRepository $docR, CategorieRepository $cat,SousCategorieRepository $scat)
    {
        $categories=$cat->findAll();
        $doc=$docR->find($id);
        
         //creation formulaire 
         $form=$this->createForm(DocumentType::class,$doc);
         //recuperation des donnees modifies
         $form->handleRequest($req);
         if($form->isSubmitted() && $form->isValid()){
             $em=$this->getDoctrine()->getManager();
             if(!empty($doc->getFichier())){
                $pdf=file_get_contents($doc->getFichier());
                $doc->setFichier($pdf);
             }
             
             $cts= explode("-",$req->request->get('categorie'));
             $categorie=$cat->find($cts[0]);
             $scategorie=$scat->find($cts[1]);
             $doc->setCategorie($categorie);
             $doc->setSouscat($scategorie);
             
             //Modification des donnees dans le db

             $em->flush();
             //Ajout msg alert de success
             $this->addFlash("success","Modifier avec success");

             //Redirection
             return $this->redirectToRoute('dashbord');
 
         }
     
         return $this->render('admin/edit.html.twig', [
             'form' => $form->createView(),
             'categories'=>$categories
 
         ]);
     }
     /**
     * @Route("/admin/remove/{id}", name="removeDoc")
     */
    public function supDoc(Request $req,$id,DocumentRepository $docR, CategorieRepository $cat,SousCategorieRepository $scat)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$docR->find($id);
        $em->remove($doc);
        $em->flush();
        //Ajout msg alert de success
        $this->addFlash("danger","Supprimer avec success");

        //Redirection
        return $this->redirectToRoute('dashbord');
    }
     /**
     * @Route("/admin/rv/{id}", name="removePack")
     */
    public function sup(Request $req,$id,PacksRepository $pk, CategorieRepository $cat,SousCategorieRepository $scat)
    {
        $em=$this->getDoctrine()->getManager();
        $doc=$pk->find($id);
        if($doc->getPrincipal()== true ){
            $this->addFlash("danger","Impossible de Supprimer un pack principal");

        //Redirection
        return $this->redirectToRoute('dashbord');
        }
        $em->remove($doc);
        $em->flush();
        //Ajout msg alert de success
        $this->addFlash("danger","Supprimer avec success");

        //Redirection
        return $this->redirectToRoute('dashbord');
    }


    /**
     * @Route("/admin/addpack", name="addPack")
     */
    public function addpack(Request $req, CategorieRepository $cat,SousCategorieRepository $scat)
    {
        
        $packs=new Packs();
         //creation formulaire 
         $form=$this->createForm(PackType::class,$packs);
         //recuperation des donnees modifies
         $form->handleRequest($req);
         if($form->isSubmitted() && $form->isValid()){
             $em=$this->getDoctrine()->getManager();
             //Modification des donnees dans le db
             $em->persist($packs);
             $em->flush();
             //Ajout msg alert de success
             $this->addFlash("success","Ajouter avec success");

             //Redirection
             return $this->redirectToRoute('dashbord');
 
         }
     
         return $this->render('admin/addpack.html.twig', [
             'form' => $form->createView(),
 
         ]);
     }
/**
     * @Route("/admin/pack", name="Pack")
     */
public function pack(PacksRepository $p){
    $packs=$p->findAll();
    return $this->render('admin/packs.html.twig', [
        "packs"=>$packs

    ]);
}

/**
* @Route("/admin/pack/{id}/edit", name="editPack")
*/
public function editpack(PacksRepository $p,$id,Request $req){
    $packs=$p->find($id);
         //creation formulaire 
         $form=$this->createForm(PackType::class,$packs);
         //recuperation des donnees modifies
         $form->handleRequest($req);
         if($form->isSubmitted() && $form->isValid()){
             $em=$this->getDoctrine()->getManager();
             //Modification des donnees dans le db
             $em->flush();
             //Ajout msg alert de success
             $this->addFlash("success","modifier avec success");

             //Redirection
             return $this->redirectToRoute('Pack');
 
   
}
return $this->render('admin/editpacks.html.twig', [
    'form' => $form->createView(),

]);
}


/**
* @Route("/admin/pack/offrir", name="offrir")
*/
public function offrir(UserRepository $repo,Request $request)
{
    $me=$this->getUser();
    $role=$me->getRoles()[0];
    
    if(!($role=="ROLE_ADMIN" || $role=="ROLE_DEV") ){
        $this->addFlash("danger","Pas autorisé ");
        return $this->redirectToRoute('dashbord');
    }
    $em=$this->getDoctrine()->getManager();
    $form = $this->createFormBuilder(null)
    ->add('email',TextType::class)
    ->add('pack', EntityType::class, [
        'class'=> Packs::class,
        'choice_label' => "libelle",
    ])
    ->add('offrir',SubmitType::class)
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $query=$form->get('email')->getData();
        $pack=$form->get('pack')->getData();
       $user=$repo->findBy(["email"=> $query]);  
            
       if($user){
           
        $date=new DateTime();
            $y=$date->format('Y');
            $d=$date->format('d');
            $m=$date->format('m');
            $datefin=new DateTime(($y+1)."-$m-$d");
            $subs=new Subscription();
            
            $subs->setUser($user[0]);
            
            $subs->setPack($pack)
                ->setDateDebut(new DateTime())
                ->setDateFin($datefin)
                ->setLinkFacture("https://nasrulex.com");
            //dd($subs);

                $em->persist($subs);
                $em->flush();
                $this->addFlash("success","Pack Offert ");
            return $this->redirectToRoute('dashbord');
       }else{
        $this->addFlash("danger","L' email introuvable");

        //Redirection
        return $this->redirectToRoute('offrir');

       }
    }

    return $this->render('admin/offrir.html.twig', [
        'form' => $form->createView(),
    
    ]);
}






 /**
     * @Route("/admin/mode-free", name="modefree")
     */
    
    
    public function mode(EnvRepository $repEnv,UserRepository $repo,Request $request)
{
    $em=$this->getDoctrine()->getManager();
   $me=$this->getUser();
    $role=$me->getRoles()[0];
    $env=$repEnv->findAll();
    if(!empty($env)){
        $myFree=$env[0]->getFree();
        if($myFree==true){
            $free="Activer";
        }else{
            $free="Désactiver";
        }
    }else{
        $newEnv=new Env();
        $newEnv->setFree(false);
        $em->persist($newEnv);
        $em->flush();
        $free="Désactiver";
    }
    
    
    
    $form = $this->createFormBuilder(null)

    ->add('Mode', ChoiceType::class, [
        'choices' => [
            'Activer'=>true,
            'Désactiver.'=>false
        ],
    ])
    ->add('Save',SubmitType::class)
    ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $query=$form->get('Mode')->getData();
        $env[0]->setFree($query);
        $em->flush();
        if($env[0]->getFree()==true){
            $free="Activer";
        }else{
            $free="Désactiver";
        }
        $this->addFlash("success","Mode Gratuit $free");
        return $this->redirectToRoute('dashbord');
       

       }

       return $this->render('admin/modefree.html.twig', [
        'form' => $form->createView(),
        'free'=> $free
    
    ]);
    

   
}
}

