<?php

namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact( MailerInterface $mailer, Request $request )
    {
        $contact = new Contact;
        $form = $this->createForm( ContactType::class, $contact );

        $form->handleRequest( $request);

        if( $form->isSubmitted() AND $form->isValid() ) {
            if($contact->getOrderNumber()) {
                $order = $contact->getOrderNumber();
            }

            $user = $this->getUser();
            
            //Gère le message et le réécrit pour une meilleure clareté, un meilleur traitement, et au cas où replyTo ne serait pas pris en charge. 
            $message = $contact->getFirstname() ." ". $contact->getLastname(). " nous a contacté sur le sujet suivant : ";
            if($contact->getOrderNumber()) {
                $message .= $contact->getTopic() . ", commande numéro : ". $contact->getOrderNumber();
            }else{
                $message .= $contact->getTopic();
            }
            $message .= "\n";
            if($this->getUser()) {
                $message .= "Pseudo : " . $user->getUsername() ."\n"; 
            }
            
            $message .= $contact->getMessage();
            $message .= "\n";
            $message .= "Voici son mail : " . $contact->getEmail();
            //Fin du traitement

            $email = (new Email())
                ->from("winterisgaming2020@gmail.com")
                ->to("winterisgaming2020@gmail.com")
                ->replyTo($contact->getEmail())
                ->subject($contact->getTopic())
                ->text($message);

            $mailer->send($email);
        }
        

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
            "user" => $this->getUser(),
            
        ]);
    }
}