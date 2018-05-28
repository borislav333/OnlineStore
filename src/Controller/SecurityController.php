<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\Users;
use App\Form\RegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new Users();
        $form = $this->createForm(RegisterType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles($this->getDoctrine()->getRepository(Roles::class)->findOneBy(['name'=>'ROLE_USER']));
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request,AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();


        return $this->render('security/login.html.twig', array(
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout",name="logout")
     *
     */
    public function logout(){

    }

    /**
     * @Route("/admin" ,name="admin")
     */
    public function admin(AuthorizationCheckerInterface $authChecker){

        if ((true === $authChecker->isGranted('ROLE_ADMIN')))
        {
            echo 'bravo';
        }
        else{
            echo 'losho';
        }

        return $this->render('admin/admin.html.twig');
    }
}
