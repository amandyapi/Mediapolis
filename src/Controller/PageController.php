<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    
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

    public function contact(): Response
    {
        $page = 'contacts';
        $devisUrl = $this->devisBg[10];
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
    
                //return $this->json(true, 200);
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
