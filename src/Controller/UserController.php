<?php

namespace App\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
   /**
     * @Route("/profil", name="profil")
     */
    public function dash()
    {
        return $this->render('admin/dash2.html.twig', [
            "categories"=>array(),
           "date"=> "07878"
        ]);
    }
    /**
     * @Route("/profil/modipass", name="passe")
     */
    public function passe(Request $req,UserPasswordEncoderInterface $encoder)
    {
        $ancienpass=$req->request->get('ancienpass');
        $newpass=$req->request->get('newpass');
        $rnewpass=$req->request->get('rnewpass');
        $user=$this->getUser();
        if($req->request->count()>0){
            if($ancienpass && $newpass && $rnewpass){
                $hsh=$encoder->isPasswordValid($user,$ancienpass); 
            ;
            if($hsh)
            {
                if($newpass == $rnewpass){
                    $pass=$encoder->encodePassword($user,$newpass);
                    $user->setPassword($pass) ;
                    $em=$this->getDoctrine()->getManager();
                    $em->flush();
                    $this->addFlash("success","Mots de Passe Modifier avec success !");
                    //Redirection
                    if($user->getRole()=="ROLE_ADMIN"){
                        return $this->redirectToRoute('dashbord');
                    }
                    return $this->redirectToRoute('profil');
                }else{
                    //Ajout msg alert de success
                 $this->addFlash("danger","Les  mots de passes sont pas semblables !");
                 //Redirection
                 return $this->redirectToRoute('passe');
                }
            }else
            {
                //Ajout msg alert de success
             $this->addFlash("danger","votre ancien mots de passe est incorrect !");
             //Redirection
             return $this->redirectToRoute('passe');
            }
            }else{
                $this->addFlash("danger","Remplissez toutes les champs !");
                //Redirection
                return $this->redirectToRoute('passe');
            }
        }

        return $this->render('admin/passe.html.twig', [
            
        ]);
    }
    /**
     * @Route("/profil/abonnement", name="abonnement")
     */
    public function abonnement()
    {
        $user=$this->getUser();
        $subs=$user->getSubscriptions();
        return $this->render('admin/abonnement.html.twig', [
            "subs"=>$subs
        ]);
    }
   

}
