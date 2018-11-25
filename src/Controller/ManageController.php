<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\Common\Persistence\ObjectManager;

// Include GeoIp2 Classes
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;


use App\Entity\User;
use App\Entity\Country;
use App\Repository\UserRepository;
use App\Form\ArticleType;


class ManageController extends AbstractController
{
    /**
     * @Route("/create", name="user_create")
     */
    public function index(Request $request, ObjectManager $manager, \Swift_Mailer $mailer)
    {
        // Chemin vers le fichier de geolite
      /*  $GeoLiteDatabasePath = $this->get('kernel')->getProjectDir() . '/../geoloc/GeoLite2-City.mmdb';
        
        $reader = new Reader($GeoLiteDatabasePath);        
        try{
            // Récupération de l'IP
            $ip = $request->getClientIp();

            // Minnesota grâce à l'IP
            $location = $reader->city('128.101.101.101');
            
        } catch (AddressNotFoundException $ex) {
            // Aucune adresse trouvée
            return new Response("It wasn't possible to retrieve information about the providen IP");
        }
        
        dump($location);*/
        
        
        
    
        $user = new User();

        $form = $this->createFormBuilder($user)
                     ->add('surname')
                     ->add('name')
                     ->add('birthDate')
                     ->add('email')
                     ->add('gender', ChoiceType::class, array(
                        'choices'  => array(
                            'Femme' => 'Femme',
                            'Homme' => 'Homme',
                        ),
                    ))               
                     ->add('country', EntityType::class, [
                         'class' => Country::class,
                         'choice_label' => 'nom_fr_fr'
                     ])
                     ->add('profession', ChoiceType::class, array(
                        'choices'  => array(
                            'Cadre' => 'Cadre',
                            'Employé de la fonction publique' => 'Employé de la fonction publique',
                        ),
                    ))          
                     ->getForm();

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
                ->setTo('f_nouvion@orange.fr')
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

        /**
     * @Route("/connexion", name="connexion")
     */
    public function connection(){
        return $this->render('manage/connection.html.twig');
    }
}
