<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\ArticleType;


class ManageController extends AbstractController
{
    /**
     * @Route("/create", name="user_create")
     */
    public function index(Request $request, ObjectManager $manager)
    {
        if($request->request->count() > 0){
            $user = new User();
            $user->setSurname($request->request->get('surname'))
                 ->setName($request->request->get('name'))
                 ->setEmail($request->request->get('surname'))
                 ->setGender($request->request->get('surname'))
                 ->setCountry($request->request->get('surname'))
                 ->setProfession($request->request->get('surname'));

            $manager->persist($user);
            $manager->flush();
        }

       /* $form = $this->createFormBuilder($user)
                     ->add('surname')
                     ->add('name')
                     ->add('birthDate')
                     ->add('email')
                     ->add('gender')
                     ->add('country')
                     ->add('profession')
                     ->getForm();*/

      //  $form = $this->createForm(UserType::class, $user);

      /*  $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('/');
        }*/

        return $this->render('manage/index.html.twig', [
            'controller_name' => 'ManageController',
         //   'formUser' -> $form->createView()
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
