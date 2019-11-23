<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
     /**
     * @Route("/login", name = "login")
     */
    public function login(AuthenticationUtils $helper)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        if($SystemSettings->getIsFirstRun() == 0)
        {
            return $this->redirectToRoute('register');
        }

        return $this->render('admin/account/login.html.twig',[
            'error' => $helper->getLastAuthenticationError(),
            'SystemSettings' => $SystemSettings
        ]);
    }

     /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $password_encoder)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        if($SystemSettings->getIsFirstRun() == 1)
        {
            return $this->redirectToRoute('login');
        }

        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setFirstName($request->request->get('user')['first_name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);

            $password = $password_encoder->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);

            $isAdmin = $request->request->get('user')['isAdmin'];

            if($isAdmin)
            {
                $user->setIsAdmin($isAdmin);
                $user->setRoles(['ROLE_ADMIN']);
            }

            $SystemSettings->setIsFirstRun(1);
            $entityManager->persist($SystemSettings);

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('login');
        }

        return $this->render('/security/register.html.twig', [
            'SystemSettings' => $SystemSettings,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name = "logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached !!');
    }



}
