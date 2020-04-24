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
    public function register( Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em )
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
            $user->setRole( ['ROLE_USER'] );
            $user->setLoyalty( 0 );;

            $em->persist( $user );
            $em->flush();

            $this->addFlash( 'success', "Votre compte à bien été créé" );
           /*  return $this->redirectToRoute( 'event_list' ); */
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