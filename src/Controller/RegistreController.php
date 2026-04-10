<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistreController extends AbstractController
{
    /**
     * @Route("/registre", name="inscription")
     */
    
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, CategorieRepository $cat,\Swift_Mailer $mailer)
    {

        $categories=$cat->findAll();
        if($request->request->count()>0){
            $user = new User();
            $hsh=$encoder->encodePassword($user,$request->request->get('password'));
          $user->setPassword($hsh)
              ->setCivilite($request->request->get('civilite'))
              ->setPrenom($request->request->get('prenom'))
              ->setUsername($request->request->get('username'))
             ->setTelephone($request->request->get('telephone'))
             ->setNomS($request->request->get('nom_S'))
             ->setAdresseS($request->request->get('Adresse_S'))
             ->setTelephoneS($request->request->get('telephone_S'))
             ->setNumeroIF($request->request->get('numero_I_F'))
             ->setEmail($request->request->get('email'))
             ->setCode("00")
             ->setRole(("ROLE_USER"));

             $user->setActivationToken(md5(uniqid()));

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
 
             // do anything else you need here, like send an email
             // On crée le message
             $message = (new \Swift_Message('Bienvenue'))
                 // On attribue l'expéditeur
                 ->setFrom(['admin@nasrulex.com'=> " NASRULEX "])
                 // On attribue le destinataire
                 ->setTo($user->getEmail())
                 // On crée le texte avec la vue
                 ->setBody(
                     $this->renderView(
                         'email/activation.html.twig', ['token' => $user->getActivationToken()]
                     ),
                     'text/html'
                 )
             ;
             $mailer->send($message);
 
         $this->addFlash("success","Inscription validé! un message vous a été envoyé sur ".$user->getEmail() ."SI C'EST PAS LE CAS REGARDER DANS VOS SPAMS");
         return $this->redirectToRoute('login');

        }
        return $this->render('registre/registre.html.twig', [
            'connection'=>true,
           // 'form'=>$form->createView(),
            'title'=>'Inscription',
            "date"=>date_format(new \DateTime(),"Y"),
            "categories"=>$categories
        ]);
    }

    /**
     * @Route("/condition", name="condition")
     * 
     */
    public function condition(){
    return $this->render('Condition/index.html.twig', [
        "date"=>date_format(new \DateTime(),"Y")
        ]
        );
}
}
