<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MediaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserController extends AbstractController
{
    private $mediaService;

    public function __construct( MediaService $mediaService )
    {
        $this->mediaService = $mediaService;
    }
    /**
     * @Route("/register", name="user_register")
     */
    public function register( Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, MailerInterface $mailer )
    {
        $user = new User();
        $form = $this->createForm( RegisterType::class, $user );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){

            //Password 
            $plain = $user->getPlainPassword();
            $password = $encoder->encodePassword( $user, $plain );
            $user->setPassword( $password );

            //Avatar Upload
            if($user->getAvatarFile()){
                $file = $user->getAvatarFile();
                $filename = $this->mediaService->upload( $file );
                $user->setAvatar($filename);
            }

            //Set Role
            $user->setRoles( ['ROLE_USER'] );
            $user->setLoyalty( 0 );;

            $em->persist( $user );
            $em->flush();

            $this->addFlash( 'success', "Votre compte à bien été créé." );

            //Mail de confirmation

            $message = "Bonjour ". $user->getFirstname() . " " . $user->getLastname() . " et merci d'avoir créé un compte Winter Is Gaming !\n";
            $message .= "Vous pouvez vous connecter dès à présent via votre addresse mail, et le mot de passe que vous avez entré lors de la création de votre compte.\n";
            $message .= "Nous espérons vous voir bientôt commencer à accumuler des points de fidélité chez nous. \n Toute l'équipe de Winter Is Gaming";

            $email = (new Email())
                ->from("winterisgaming2020@gmail.com")
                ->to($user->getEmail())
                ->subject("Création de votre compte Winter Is Gaming")
                ->text($message);

            $mailer->send($email);

            return $this->redirectToRoute( 'home' );
        }

        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/login", name="user_login")
     */
    public function login( AuthenticationUtils $authUtils ){
        return $this->render( 'user/login.html.twig', array(
            'lastUsername' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout(){}

    /**
     * @Route("/login_success", name="user_login_success")
     */
    public function login_success(){
        $this->addFlash( 'success', 'Vous êtes bien connecté' );
        return $this->redirectToRoute( 'main_home' );
    }

    /**
     * @Route("/logout_success", name="user_logout_success")
     */
    public function logout_success(){
        $this->addFlash( 'success', 'Vous êtes bien déconnecté' );
        return $this->redirectToRoute( 'main_home' );
    }


    /**
     * @Route("/", name="main_home")
     */
    public function home()
    {
        return $this->render('base.html.twig');
    }
}