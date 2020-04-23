<?php

namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;

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

            $message = $contact->getFirstname() ." ". $contact->getLastname(). " nous a contacté sur le sujet suivant : ";
            if($contact->getOrderNumber()) {
                $message .= $contact->getTopic();
            }else{
                $message .= $contact->getTopic();
            }
            $message .= "\n";
            $message .= $contact->getMessage();
            $message .= "\n";
            $message .= "Voici son mail : " . $contact->getEmail();

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
            'isNew' => false,
        ]);
    }
}