<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Picture;
use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;

class PageController extends AbstractController
{
    protected $em;
    protected $mailer;
    protected $devisBg;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->devisBg = [
                            0 => '21.jpg', 1 => '20.jpg', 2 => '19.jpg', 3 => '18.jpg', 4 => '17.jpg', 5 => '16.jpg', 
                            6 => '14.jpg', 7 => '13.jpg', 8 => '12.jpg', 8 => '11.jpg', 9 => '10.jpg', 10 => '9.jpg'
                        ];
    }

    public function index(): Response
    {
        /*$file = "data/partners.json";
        $data = file_get_contents($file);
        $partners = \json_decode($data);*/
        $partners = [];
        for ($i=1; $i < 35; $i++) { 
            $p = new \stdClass();
            $p->name="$i";
            $p->url="$i.png";
            $partners[] = $p;
        }

        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
            'partners' => $partners
        ]);
    }

    public function agence(): Response
    {
        return $this->render('page/agence.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function equipe(): Response
    {
        return $this->render('page/equipe.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function offreStrategique(): Response
    {
        return $this->render('page/offres-strategique.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    public function offresTechnologiques(): Response
    {
        return $this->render('page/offres-technologiques.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function offresOperationnelles(): Response
    {
        return $this->render('page/offres-operationnelles.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function offresMusicEvent(): Response
    {
        return $this->render('page/offres-music-event.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    public function galerie(): Response
    {
        return $this->render('page/galerie.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function blog(): Response
    {
        return $this->render('page/blog.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function contact(Request $request): Response
    {
        $page = 'contacts';
        $devisUrl = $this->devisBg[10];
        //var_dump($devisUrl);die();

        if(!empty($request->request->get('envoyer')))
        {
            $page = 'prestations';

            $response;
            $entityManager = $this->getDoctrine()->getManager();
            
            $gfiContactMail = 'contact@gfi-co.net';
            $senderFullName = $request->request->get('contact-name');
            $senderMail = $request->request->get('contact-email');
            $senderContact = $request->request->get('contact-phone');
            $title = $request->request->get('objet');
            $content = $request->request->get('contact-messgae');

            try {
                $email = (new TemplatedEmail())
                    ->from(new Address($senderMail, $senderFullName))
                    ->to(new Address($gfiContactMail))
                    ->subject($title)
                    ->htmlTemplate('admin/mails/contact-mail.html.twig')
    
                    ->context([
                        'senderFullName' => $senderFullName,
                        'senderMail' => $senderMail,
                        'senderContact' => $senderContact,
                        'title' => $title,
                        'content' => $content,
                    ]);
    
                $this->mailer->send($email);
    
                $mail = new Mail();
                $mail->setSenderFullName(\strtolower($senderFullName));
                $mail->setSenderMail(\strtolower($senderMail));
                $mail->setSenderContact(\strtolower($senderContact));
                $mail->setTitle(\strtolower($title));
                $mail->setContent(\strtolower($content));
    
                $entityManager->persist($mail);
                $entityManager->flush();
    
                return $this->redirectToRoute('gfi-success-mail', [
                    'lang' => $lang
                ]);
            } catch (\Throwable $th) {
                $response = $th->getMessage();
                
                return $this->redirectToRoute('gfi-error-mail', [
                    'lang' => $lang,
                    'page' => $page
                ]);
            }
        }
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    public function recrutements(): Response
    {
        return $this->render('page/recrutements.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
