<?php

namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact( MailerInterface $mailer, Request $request, ParameterBagInterface $params)
    {
        $contact = new Contact;
        $form = $this->createForm( ContactType::class, $contact );

        $form->handleRequest( $request);

        if( $form->isSubmitted() AND $form->isValid() ) {

            $user = $this->getUser();
            
            //Gère le message et le réécrit pour une meilleure clareté, un meilleur traitement, et au cas où replyTo ne serait pas pris en charge. 
            if($contact->getTopic() == "Commande") {
                $topic = $contact->getTopic() . ", commande numéro : ". $contact->getOrderNumber();
            }else{
                $topic = $contact->getTopic();
            }
            $pseudo = "";
            if($this->getUser()) {
                $pseudo =  $user->getUsername(); 
            }
            
            $message = $contact->getMessage();
            $replyMail = $contact->getEmail();
            //Fin du traitement

            $email = (new TemplatedEmail())
                ->from($params->get('mail'))
                ->to($params->get('mail'))
                ->replyTo($contact->getEmail())
                ->subject($contact->getTopic())
                ->htmlTemplate('email/contactmail.html.twig')
                ->context(array(
                    'pseudo' => $pseudo,
                    'topic' => $topic,
                    'message' => $message,
                    'replymail' => $replyMail,
                ));

            $mailer->send($email);

            $this->addFlash( 'success', "Votre message a bien été envoyé. Nous tâcherons d'y répondre dans les meilleurs délais." );

            return $this->redirectToRoute( 'home' );
        }
        

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
            "user" => $this->getUser(),
            
        ]);
    }
}