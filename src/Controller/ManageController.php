<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\UserType;

use Doctrine\Common\Persistence\ObjectManager;

// Include GeoIp2 Classes
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

use App\Entity\User;
use App\Entity\Country;


class ManageController extends Controller
{
    /**
     * @Route("/create", name="user_create")
     */
    public function index(Request $request, ObjectManager $manager, \Swift_Mailer $mailer)
    {
        // Récupération de l'IP
        $ip = $request->getClientIp();     
        dump($ip);
        
        // get a GeoIP2 City model
        //Exemple IPv4 FR : 5.49.49.225
        $record = $this->get('geoip2.reader')->city('5.49.49.225');
        dump($record->country->name);
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $mailContent = "Vos informations : \n";
            $mailContent .= "Votre nom : " . $user->getSurname() . "\n";
            $mailContent .= "Votre prénom : " . $user->getName() . "\n";
            $mailContent .= "Votre date de naissance : Le " . $user->getBirthDate()->format('d/m/Y') . "\n";
            $mailContent .= "Votre genre : " . $user->getGender() . "\n";
            $mailContent .= "Votre pays : " . $user->getCountry()->getNomFrFr() . "\n";
            $mailContent .= "Votre métier : " . $user->getProfession() . "\n";
            $message = (new \Swift_Message('Informations liée à l\'inscription'))
                ->setFrom('francktestemail@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($mailContent);
            
            $mailer->send($message);
        }

        return $this->render('manage/index.html.twig', [
            'controller_name' => 'ManageController',
            'formUser' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('manage/home.html.twig');
    }

}
