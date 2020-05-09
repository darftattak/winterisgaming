<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use App\Form\ModifyType;
use App\Form\RegisterType;
use App\Form\EmailModifyType;
use App\Form\NewPasswordType;
use App\Service\MediaService;

use App\Form\PasswordModifyType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\TokenRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
    public function register( Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, MailerInterface $mailer )
    {
       ;
        if ($this->getUser()) {
            return $this->redirectToRoute( 'main_home' );
        }   

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
            "user" => $this->getUser()
        ));
    }

    /**
     * @Route("/login", name="user_login")
     */
    public function login( AuthenticationUtils $authUtils ){
        return $this->render( 'user/login.html.twig', array(
            'lastUsername' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
            "user" => $this->getUser()
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

    /**
     * @Route("/user/interface", name="user_interface")
     */
    public function index()
    {
        
        $user = $this->getUser();
        if(!($user)) {
        return $this->redirectToRoute('user_login');
        }
        return $this->render('user_interface/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
        ]);
    }


    // Route de suivi des commandes

    /**
     * @Route("/products/follow", name="products_follow")
     */
    public function follow()
    {
        $user = $this->getUser();
        $orders = $user->getOrders();
        return $this->render('user_interface/follow.html.twig', [
            'controller_name' => 'ProductsFollow',
            'user' => $user,
            'orders' => $orders,
        ]);
    }


    // Route de suivi d'une seule commande

    /**
     * @Route("/product/single/{id}", name="product_single")
     */
    public function singleOrder($id, OrderRepository $orderRepository )
    {
        $order =  $orderRepository->find( $id );
        $orderStatus = $order->getStatus();
        $orderHasProducts = $order->getOrderHasProducts();
        return $this->render('user_interface/singleProduct.html.twig', [
            'controller_name' => 'ProductsFollow',
            'orderHasProducts' => $orderHasProducts,
            'order' => $order,
            'orderStatus' => $orderStatus,
            'user' => $this->getUser(),
        ]);
    }


    // Formulaire de modification des infos basiques.

    /**
     * @Route("/user/modify/{id}", name="user_modify")
     */
    public function update( User $user, Request $request, EntityManagerInterface $em )
    {
        if( $this->getUser() !== $user ){
            return $this->redirectToRoute( 'main_home' );
        }

        $form = $this->createForm( ModifyType::class, $user );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){


            $file = $user->getAvatarFile();
            if( !empty( $file ) ){
                $filename = $this->mediaService->upload( $file );
                $user->setAvatar( $filename );
            }

            $em->flush();

            $this->addFlash( 'success', "Vos informations ont bien été modifiées, ". $user->getUsername() );
            return $this->redirectToRoute( 'user_interface', array(
                'id' => $user->getId(),
            ));
        }

        return $this->render( 'user_interface/update.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
            'user' => $user,
        ));
    }


    // Formulaire de modification des infos sensibles.


    // Mofification du mot de passe
    /**
     * @Route("/password/modify/{id}", name="password_modify")
     */
    public function updatePass( User $user, Request $request,UserPasswordEncoderInterface $encoder, EntityManagerInterface $em )
    {
        if( $this->getUser() !== $user ){
            return $this->redirectToRoute( 'main_home' );
        }

        $form = $this->createForm( PasswordModifyType::class, $user );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            
            //Password 
            $plain = $user->getPlainPassword();
            $password = $encoder->encodePassword( $user, $plain );
            $user->setPassword( $password );

            $em->flush();

            $this->addFlash( 'success', "Votre mot de passe a bien été modifié ".$user->getUsername() );
            return $this->redirectToRoute( 'user_interface', array(
                'id' => $user->getId(),
            ));
        }

        return $this->render( 'user_interface/password.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
            'user' => $user,
        ));
    }


    // Réinitialisation du mot de passe
    /**
     * @Route("/password/retrieve/{token}", name="password_retrieve")
     */
    public function retrievePass($token, TokenRepository $tokenRepository, Request $request,UserPasswordEncoderInterface $encoder, EntityManagerInterface $em )
    {
        $validToken = $tokenRepository->findOneByToken($token);
        if( !$validToken ) {
            return $this->redirectToRoute( 'main_home' );
        }

        $date = new DateTime;
        $now = $date->setTimestamp(strtotime('now'));

        if($validToken->getEntAt()< $now ) {
            return $this->redirectToRoute( 'main_home' );
        }

        $user = $validToken->getUser();
        $form = $this->createForm( NewPasswordType::class, $user );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            
            //Password 
            $plain = $user->getPlainPassword();
            $password = $encoder->encodePassword( $user, $plain );
            $user->setPassword( $password );
            $now = $date->setTimestamp(strtotime('now'));
            $validToken->setEntAt($now);    

            $em->flush();

            $this->addFlash( 'success', "Votre mot de passe a bien été réinitialisé" );
            return $this->redirectToRoute( 'user_login', array(
                
            ));
        }

        return $this->render( 'user_interface/passwordretrieve.html.twig', array(
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ));
    }


    // Mofification de l'email
     /**
     * @Route("/email/modify/{id}", name="email_modify")
     */
    public function updateMail( User $user, Request $request,UserPasswordEncoderInterface $encoder, EntityManagerInterface $em )
    {
        if( $this->getUser() !== $user ){
            return $this->redirectToRoute( 'main_home' );
        }

        $form = $this->createForm( EmailModifyType::class, $user );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            
            $em->flush();

            $this->addFlash( 'success', "Votre email \"" . $user->getUsername() . "\" a bien été modifié" );
            return $this->redirectToRoute( 'user_interface', array(
                'id' => $user->getId(),
            ));
        }

        return $this->render( 'user_interface/email.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
            'user' => $user,
        ));
    }
    

        // Route de la liste d'envies


        //Lors de l'ajout à la wishlist
    /**
     * @Route("/wishlist/{id}", name="wishlist", requirements={"id"="\d+"})
     */
    public function wishList($id, ProductRepository $productRepository, EntityManagerInterface $em )
    {
        $user = $this->getUser();
        $product =  $productRepository->find( $id );
        $user->addWishlist($product);
        $em->flush();
        return $this->redirectToRoute('wishlist_view');
            
    }
        
        //Lors de la suppression depuis la wishlist
    /**
     * @Route("/wishlist/remove/{id}", name="wishlist_remove", requirements={"id"="\d+"})
     */
    public function wishListRemove($id, ProductRepository $productRepository, EntityManagerInterface $em )
    {
        $user = $this->getUser();
        $product =  $productRepository->find( $id );
        $user->removeWishlist($product);
        $em->flush();
        return $this->redirectToRoute('wishlist_view');
            
    }


        // Depuis le UserInterface
    /**
     * @Route("/wishlist/view", name="wishlist_view")
     */
    public function wishListView()
    {
        $user = $this->getUser();
        $products = $user->getWishlist();
        foreach ($products as $product) {
            $photos = $product->getPhotos();
            foreach ($photos as $photo) {
                $photo->setPicturePath();
            }
        }
        return $this->render('user_interface/wishlistview.html.twig', [
            'controller_name' => 'Wishlistview',
            'products' => $products,
            'user' => $this->getUser(),
                
        ]);

    }



}