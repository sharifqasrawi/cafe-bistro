<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfileType;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use App\Form\ChangePasswordType;
use App\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/users/create-user", name="create_user")
     */
    public function create_user(Request $request, UserPasswordEncoderInterface $password_encoder)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setFirstName($request->request->get('user')['first_name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);

            $password = $password_encoder->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);

            $isAdmin = $request->request->get('user')['isAdmin'];

            if ($isAdmin) {
                $user->setIsAdmin($isAdmin);
                $user->setRoles(['ROLE_ADMIN']);
            }

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/account/create_user.html.twig', [
            'SystemSettings' => $SystemSettings,
            'form' => $form->createView(),
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }

    /**
     * @Route("/users/delete-user", name="delete_user")
     */
    public function delete_user(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-user', $token)) {
            $userId = $request->request->get('userId');
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'User deleted successfully');

            return $this->redirectToRoute('list_users');
        }

        $this->addFlash('error', 'Error deleting user');

        return $this->redirectToRoute('list_users');
    }



    /**
     * @Route("/users/list-users", name="list_users")
     */
    public function list_users()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $users = $this->getDoctrine()->getRepository(User::class)->findBy([], ['first_name' => 'ASC']);

        return $this->render('admin/account/list_users.html.twig', [
            'SystemSettings' => $SystemSettings,
            'users' => $users,
            'usersCount' => count($users),
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, UserInterface $user)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setFirstName($request->request->get('profile')['first_name']);
            $user->setLastName($request->request->get('profile')['last_name']);
            $user->setEmail($request->request->get('profile')['email']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/account/profile.html.twig', [
            'SystemSettings' => $SystemSettings,
            'form' => $form->createView(),
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }


    /**
     * @Route("/change-password", name="change_password")
     */
    public function change_password(Request $request, UserInterface $user, UserPasswordEncoderInterface $password_encoder)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $userdb = $this->getDoctrine()->getRepository(User::class)->find($user->getId());
        $userPwd = $userdb->getPassword();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current_password = $request->request->get('current_password');
            $current_password_encoded = $password_encoder->encodePassword($user, $current_password);

            if ($current_password_encoded == $userdb->getPassword()) {
                $entityManager = $this->getDoctrine()->getManager();

                $password = $password_encoder->encodePassword($user, $request->request->get('change_password')['password']['first']);
                $user->setPassword($password);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password updated successfully');

                return $this->redirectToRoute('dashboard');
            } else {
                $this->addFlash('error', 'Invalid current password');

                return $this->redirectToRoute('change_password');
            }
        }

        return $this->render('admin/account/change_password.html.twig', [
            'SystemSettings' => $SystemSettings,
            'form' => $form->createView(),
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }

     /**
     * @Route("/users/edit-user/{id}", name="edit_user")
     */
    public function edit_user(User $user ,Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setFirstName($request->request->get('edit_user')['first_name']);
            $user->setLastName($request->request->get('edit_user')['last_name']);
            $user->setEmail($request->request->get('edit_user')['email']);

            $isAdmin = $request->request->get('edit_user')['isAdmin'];

            if($isAdmin)
            {
                $user->setIsAdmin($isAdmin);
                $user->setRoles(['ROLE_ADMIN']);
            }
            else
            {
                $user->setIsAdmin(false);
                $user->setRoles([]);
            }

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('list_users');
        }

        return $this->render('admin/account/edit_user.html.twig', [
            'SystemSettings' => $SystemSettings,
            'form' => $form->createView(),
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }
}
