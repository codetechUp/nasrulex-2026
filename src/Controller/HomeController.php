<?php
namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Contact;
use App\Form\ResetPassType;
use App\Repository\EnvRepository;
use FOS\UserBundle\Mailer\Mailer;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Message;
use App\Repository\DocumentRepository;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class HomeController extends AbstractController
{   private $date;
    public $categories;
    function __construct(CategorieRepository $rep){
    $this->categories=$rep->findAll();
    $this->date=date_format(new \DateTime(),"Y")  ;
    }

    /**
     * @Route("", name="home")
     */
    public function index(\Swift_Mailer $mailer,CategorieRepository $cat,DocumentRepository $doc,Request $request)
    {
       // if($this->getUser()!=null){
     //       dd($this->getUser());
     //   }
     $sms="non";
     $entityManager = $this->getDoctrine()->getManager();

     

     $categories=$cat->findAll();
     $jur=$cat->findBy(['libelle'=> 'Juridique']);
     $juridique=$doc->findCount($jur)[0][1];
     $jur=$cat->findBy(['libelle'=> 'Fiscal']);
     $fiscal=$doc->findCount($jur)[0][1];
     $jur=$cat->findBy(['libelle'=> 'Foncier']);
     $foncier=$doc->findCount($jur)[0][1];
     $jur=$cat->findBy(['libelle'=> 'Social']);
     $social=$doc->findCount($jur)[0][1];
     $jur=$cat->findBy(['libelle'=> 'Banques']);
     $banque=$doc->findCount($jur)[0][1];
     $jur=$cat->findBy(['libelle'=> 'Affaires']);
     $affaire=$doc->findCount($jur)[0][1];

     if ($request->request->count()>2) {
        $contact = new Contact();
        
       if(($request->request->get('nomComplet')==null) || ($request->request->get('username')==null) || ($request->request->get('telephone')==null) || ($request->request->get('message')==null)){
        $this->addFlash("dangerContact","Veuillez remplir tous les champs");
        //return $this->redirectToRoute('home');
        echo '<script language="javascript">';
        echo 'alert("Veuillez remplir tous les champs ")';
        echo '</script>';

       }else{
           
        $contact->setNomComplet($request->request->get('nomComplet'))
        ->setUsername($request->request->get('username'))
        ->setTelephone($request->request->get('telephone'))
        ->setMessage($request->request->get('message'));
     
        $message = (new \Swift_Message('Nasrulex Message'))
                 // l'expéditeur
                 ->setFrom([$request->request->get('username') => $request->request->get('nomComplet')])
                 // le destinataire
                 ->setTo("papasa97@gmail.com")
                 // On crée le texte avec la vue
                 ->setBody(
                     $this->renderView(
                         'email/emailuser.html.twig', ['message' =>$request->request->get('message')]
                     ),
                     'text/html'
                 )
             ;
             $mailer->send($message);
             $sms="yes";
             $entityManager->persist($contact);
        $entityManager->flush();
          echo '<script language="javascript">';
         echo 'alert("Message envoyé avec succés ")';
         echo '</script>';

       //  $this->addFlash("success","Message envoyé avec succés ,");
        // return $this->redirectToRoute('home');
       }}

 
     
     //dd($categories);
        return $this->render('home/index.html.twig', [
            "contact"=> $sms,
            "categories"=> $categories,
            "date"=>date_format(new \DateTime(),"Y"),
            "home"=>true,
            "juridique"=> $juridique,
            "social"=> $social,
            "banque"=> $banque,
            "foncier"=> $foncier,
            "fiscal"=> $fiscal,
            "affaire"=> $affaire,
        ]);
    }
      /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $user)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $user->findOneBy(['ActivationToken' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('success', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        if($this->getUser()){
            return $this->redirectToRoute('profil');
        }
        return $this->redirectToRoute('login');
    }
     /**
     * @Route("/reinspass", name="resetpass")
     */
    public function resetpass(CategorieRepository $cat,Request $req,UserRepository $repuser,TokenGeneratorInterface $tk,\Swift_Mailer $mailer)
    {
        
        $categories=$cat->findAll();
        $form=$this->createForm(ResetPassType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $data=$form->getData();
            
            $user=$repuser->findOneBy(["email"=> $data["Email"]]);
            if(!$user){
                $this->addFlash("danger","L 'utilisateur n'existe pas .Inscrivez-vous! ");

                return $this->redirectToRoute('inscription');
            }
            $token=$tk->generateToken();
    

            // On essaie d'écrire le token en base de données
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('modipasse', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message= new \Swift_Message(" MOTS DE PASSE OUBLIÉ");
                    $message->setFrom(["admin@nasrulex.com" => "NASRULEX"])
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView("email/resetpass.html.twig",[ "url"=> $url])
                        ,"text/html");
                        $mailer->send($message);
                        $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');
                        return $this->redirectToRoute('login');
        }
        return $this->render('home/reset.html.twig', [
            'form' => $form->createView(),
             'categories'=>$categories,
             "date"=>date_format(new \DateTime(),"Y")            
        ]);
    }
    /**
     * @Route("modipasse/{token}", name="modipasse")
     */
    public function mp($token,Request $request,UserPasswordEncoderInterface $encoder){
        
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            
            $p=$request->request->get('password');
            $rp=$request->request->get('rpassword');
           
                // On chiffre le mot de passe

           
           $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
           $user->setResetToken(null);
            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('success', 'Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('home/reset_password.html.twig', ['token' => $token,"categories"=>$this->categories,"date"=>$this->date ]);
        }

    }
     /**
     * @Route("renderpdf/{id}", name="render")
     */
    public function inx($id,DocumentRepository $repDoc,CategorieRepository $cat,EnvRepository $rEnv)
    {
        $user=$this->getUser();
        $jdate=new DateTime();
        $env=$rEnv->findAll();
        
        if(!empty($env)){
            if($env[0]->getFree()==true){

                $categories=$cat->findAll();
            $doc=$repDoc->find($id);
            $pdfblob=stream_get_contents($doc->getFichier());
            $pdf = base64_encode(($pdfblob));
            $doc->setFichier($pdf);
        return $this->render('home/pdf.html.twig', [
            "doc"=>$doc,
            "categories"=> $categories,
            "date"=>date_format(new \DateTime(),"Y") ]);
        

            }
        }

//s'il n'est pas connecté
if(!$user){
    $this->addFlash("success","Veuillez vous connecter ou vous inscrire pour lire un document");
    return $this->redirectToRoute('login');
}else{
    $doc=$repDoc->find($id);
   if($user->getRole()!= "ROLE_ADMIN" && ($doc->getIsFree()!= true )){
    $subs=$user->getSubscriptions();
    if($subs  ){
        if($subs[count($subs)-1]){
           $sub= $subs[count($subs)-1];
           $datefin=$sub->getDateFin();
            if($datefin >= $jdate){
               
                $cats=$doc->getCategorie()->getLibelle();
                $catspack=$sub->getPack()->getCategories();
                foreach($catspack as $catspack){
                    if($catspack->getLibelle()==$cats){
                        $i=true;
                    }
                }
                if(!empty($i) && $i==true){
                    $categories=$cat->findAll();
                    $doc=$repDoc->find($id);
                    $pdfblob=stream_get_contents($doc->getFichier());
                    $pdf = base64_encode(($pdfblob));
                    $doc->setFichier($pdf);
                   return $this->render('home/pdf.html.twig', [
                      "doc"=>$doc,
                      "categories"=> $categories,
                      "date"=>date_format(new \DateTime(),"Y") ]);
                }else{
                    $this->addFlash("success","Votre abonnement ne vous permet pas de consulter ce document ");
                    $this->addFlash("success","Veuillez vous souscrire dans un autre packs! ");

                return $this->redirectToRoute('tarif');
                }
            }
            else{
                $this->addFlash("success","Veuillez vous souscrire dans un de nos packs votre abonnement a expiré");
                return $this->redirectToRoute('tarif');
            }
        }
    }
        $this->addFlash("success","Veuillez vous souscrire dans un de nos packs");
    return $this->redirectToRoute('tarif');
   }else{
    $categories=$cat->findAll();
    $doc=$repDoc->find($id);
    $pdfblob=stream_get_contents($doc->getFichier());
    $pdf = base64_encode(($pdfblob));
    $doc->setFichier($pdf);
   return $this->render('home/pdf.html.twig', [
      "doc"=>$doc,
      "categories"=> $categories,
      "date"=>date_format(new \DateTime(),"Y") ]);
   }
    
    
}

//sinon
       
    }
     /**
     * @Route("recherche", name="recherche")
     */
    public function search(Request $request,DocumentRepository $doc,CategorieRepository $ca,SousCategorieRepository $sca){
        $empty=false;
        $search= $request->request->get('search');
        if ($request->request->count()>0 && !empty($search)) {
            
            if(empty($search)){
                return;
            }

            $cat= $request->request->get('categorie');
            $datepub= $request->request->get('datepub');
           
            if(strstr($cat,'-')){
                
                $ex=explode('-',$cat);
               //On recuppere le categorie
                $categorie=$ca->find($ex[0]);
                //Ici le sous categorie
                $souscategorie=$sca->find($ex[1]);
                
                //Si le champs date de publication n'est pas renseigné
                if(!empty($datepub)){
                    //Search doc est une fonction cree dans le repository des documents
                    
                    $docs = $doc->searchDoc($search,$cat,$souscategorie,$datepub);
                }else{
                    
                    $docs = $doc->searchDoc($search,$cat,$souscategorie);
                    
                }
                
            }else{
                $categorie=$ca->find($cat);
                $docs = $doc->searchDoc($search,$cat);
                if(!empty($datepub)){
                    $docs = $doc->searchDoc($search,$cat,null,$datepub);
                }else{
                    $docs = $doc->searchDoc($search,$cat);
                }
            }

            
           
           $categories=$ca->findAll();
           $juridique=array();
           $foncier=array();
           $assurance=array();
           $affaires=array();
           //banque=comptable
           $banque=array();
           //assurances = social
           $social=array();
           $assurance=array();
           $fiscal=array();
           foreach($docs as $doc){
            if($doc->getCategorie()->getLibelle() =="Assurances"){
                $jur=$doc; 
                $assurance=array_merge(array($jur),$assurance);
            }
            if($doc->getCategorie()->getLibelle() =="Juridique"){
                $jur=$doc;
                $juridique=array_merge(array($jur),$juridique);
             

            }
            if($doc->getCategorie()->getLibelle() =="Affaires"){
                $jur=$doc;
                $affaires=array_merge(array($jur),$affaires);
             

            }
            if($doc->getCategorie()->getLibelle() =="Foncier"){
                $jur=$doc;
                $foncier=array_merge(array($jur),$foncier);
             

            }
            
            
            if($doc->getCategorie()->getLibelle() =="Fiscal"){
                $jur=$doc;
                $fiscal=array_merge(array($jur),$fiscal);
            }
            
            if($doc->getCategorie()->getLibelle() =="Banques"){
                $jur=$doc;
                $banque=array_merge(array($jur),$banque);
            }
            if($doc->getCategorie()->getLibelle() =="Social"){
                $jur=$doc;
                $social=array_merge(array($jur),$social);
            }
            
            
           }
           $jur=array();
            for($i=0;$i<count($juridique);$i++){
               if(!empty($jur[$juridique[$i]->getSouscat()->getId()])){
                
                   array_push($jur[$juridique[$i]->getSouscat()->getId()],($juridique[$i]));
               }else{
                $jur[$juridique[$i]->getSouscat()->getId()] = array($juridique[$i]);
               }
            }
            $soc=array();
            for($i=0;$i<count($social);$i++){
               if(!empty($soc[$social[$i]->getSouscat()->getId()])){
                
                   array_push($soc[$social[$i]->getSouscat()->getId()],($social[$i]));
               }else{
                $soc[$social[$i]->getSouscat()->getId()] = array($social[$i]);
               }
            }
            $fis=array();
            for($i=0;$i<count($fiscal);$i++){
               if(!empty($fis[$fiscal[$i]->getSouscat()->getId()])){
                
                   array_push($fis[$fiscal[$i]->getSouscat()->getId()],($fiscal[$i]));
               }else{
                $fis[$fiscal[$i]->getSouscat()->getId()] = array($fiscal[$i]);
               }
            }
            $fon=array();
            for($i=0;$i<count($foncier);$i++){
               if(!empty($fon[$foncier[$i]->getSouscat()->getId()])){
                
                   array_push($fon[$foncier[$i]->getSouscat()->getId()],($foncier[$i]));
               }else{
                $fon[$foncier[$i]->getSouscat()->getId()] = array($foncier[$i]);
               }
            }
            $aff=array();
            for($i=0;$i<count($affaires);$i++){
               if(!empty($aff[$affaires[$i]->getSouscat()->getId()])){
                
                   array_push($aff[$affaires[$i]->getSouscat()->getId()],($affaires[$i]));
               }else{
                $aff[$affaires[$i]->getSouscat()->getId()] = array($affaires[$i]);
               }
            }
            $ass=array();
            for($i=0;$i<count($assurance);$i++){
               if(!empty($ass[$assurance[$i]->getSouscat()->getId()])){
                
                   array_push($ass[$assurance[$i]->getSouscat()->getId()],($assurance[$i]));
               }else{
                $ass[$assurance[$i]->getSouscat()->getId()] = array($assurance[$i]);
               }
            }
            $ban=array();
            
            for($i=0;$i<count($banque);$i++){
               if(!empty($ban[$banque[$i]->getSouscat()->getId()])){
                
                   array_push($ban[$banque[$i]->getSouscat()->getId()],($banque[$i]));
               }else{
                $ban[$banque[$i]->getSouscat()->getId()] = array($banque[$i]);
               }
            }
            
          
         

        }else{
            $juridique="";
            $datepub="";
            $assurance="";
            $banque="";
            $fiscal="";
            $social="";
            $categories=$ca->findAll();
            $search=" ";
            $foncier="";
            $affaires="";
            $empty=false;
            return $this->render('home/try.html.twig', [
                "mots"=>$search,
                "categories"=>$categories,
                "date"=>date_format(new \DateTime(),"Y"),
               "juri"=>$juridique,
               "assurance"=>$assurance,
               "foncier"=>$foncier,
               "fiscal"=>$fiscal,
               'datepub'=>$datepub,
               "social"=>$social,
               "affaires"=>$affaires,
               "banque"=>$banque,
               'empty'=> $empty
                ]);
        }



        return $this->render('home/try.html.twig', [
            "mots"=>$search,
            'datepub'=>$datepub,
            "cat"=>$cat,
            "categorie"=>$categorie,
            "categories"=>$categories,
            "date"=>date_format(new \DateTime(),"Y"),
            "docs"=> $docs,
           "juri"=>$juridique,
           "assurance"=>$assurance,
           "fiscal"=>$fiscal,
           "foncier"=>$foncier,
           "affaires"=>$affaires,
           "banque"=>$banque,
           "social"=>$social,
           "res"=> $jur,
           "soc"=> $soc,
           "fis"=>$fis,
           "fon"=>$fon,
           "aff"=>$aff,
           "ass"=>$ass,
           "ban"=>$ban,
           'empty'=> $empty
            ]);
    }
}
