<?php

namespace App\Controller;
use DateTime;
use App\Entity\Token;
use App\Form\TokenType;
use App\Model\TokenContact;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TokenController extends AbstractController
{
     // Reset du mot de passe
    /**
     * @Route("/password/reset", name="password_reset")
     */
    public function resetPass( Request $request, EntityManagerInterface $em, UserRepository $userRepository, MailerInterface $mailer, ParameterBagInterface $params )
    {
        $tokenContact = new TokenContact;
        $form = $this->createForm( TokenType::class, $tokenContact );

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            
            $user = $userRepository->findOneByEmail($tokenContact->getEmail());
            if($user) {

            //Token generator
            $random = md5(random_bytes(10));
            $email = (new TemplatedEmail())
                ->from($params->get('mail'))
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('email/tokenmail.html.twig')
                ->context(array('username' => $user->getUsername(), 'link'=> 'localhost:8080/password/retrieve/'.$random));

            $mailer->send($email);
            $token = new Token;
            $token->setToken($random);
            $token->setUser($user);
            $date = new DateTime;
            $end = $date->setTimestamp(strtotime('+1 day'));
            $token->setEntAt($end);
            $em->persist($token);
            $em->flush();
            }
          

            $this->addFlash( 'success', "Un mail de réinitialisation a été envoyé à l'adresse indiquée" );
            return $this->redirectToRoute("home");
        }

        return $this->render( 'user_interface/forgottenpassword.html.twig', array(
            'form' => $form->createView(),
            'isNew' => false,
            'user' => $this->getUser(),
        ));
    }

}
