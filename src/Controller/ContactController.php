<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Model\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact( Contact $contact, MailerInterface $mailer, GmailSmtpTransport $smtp )
    {
        $form = $this->createForm( ContactType::class, $contact );

        if( $form->isSubmitted() AND $form->isValid() ) {
            $transport = new GmailSmtpTransport("winterisgaming2020", "WinterIsGaming2020");
            $mailer = new Mailer($transport);

            $email = (new Email())
                ->from("winterisgaming2020@gmail.com")
                ->to("winterisgaming2020@gmail.com")
                ->replyTo($form->email)
                ->subject($form->topic)
                ->text($form->message);
                
            $mailer->send($email);
        }

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
            'isNew' => false,
        ]);
    }
}