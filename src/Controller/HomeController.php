<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\SystemSettings;
use App\Entity\OpeningTime;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Gallary;
use App\Entity\Item;
use App\Entity\Notification;
use App\Form\MessageType;
use App\Form\CommentType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findHomePageCategories();

        $testimonials = $this->getDoctrine()->getRepository(Comment::class)->findTestimonials();

        return $this->render('home/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
            'Categories' => $Categories,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * @Route("/contact-us", name="contact_us", methods = {"GET", "POST"})
     */
    public function contact_us(Request $request)
    {
        $cookieName = $request->cookies->get('comment_username_cookie');
        $cookieEmail = $request->cookies->get('comment_useremail_cookie');

        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $userName = $request->request->get('message')['Name'];
            $message->setName($userName);
            $message->setEmail($request->request->get('message')['Email']);
            $message->setSubject($request->request->get('message')['Subject']);
            $message->setText($request->request->get('message')['Text']);

            $date = new \Datetime();

            $message->setCreatedAt($date);
            $message->setIsSeen(false);

            $entityManager->persist($message);
            $entityManager->flush();

            $notification = new Notification;
            $notification->setType('MESSAGE');
            $notification->setText($userName . ' sent you a message');
            $notification->setInfo($message->getId());
            $notification->setCreatedAt($date);
            $notification->setIsNew(true);

            $entityManager->persist($notification);
            $entityManager->flush();

            $this->addFlash('success', 'Your message was sent successfully.');

            return $this->redirectToRoute('contact_us');
        }


        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        return $this->render('home/contact_us.html.twig', [
            'form' => $form->createView(),
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
            'cookieName' => $cookieName,
            'cookieEmail' => $cookieEmail,
        ]);
    }


    /**
     * @Route("/find-us", name="find_us")
     */
    public function find_us()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        return $this->render('home/find_us.html.twig', [
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
        ]);
    }

    /**
     * @Route("/item-details/{id}", name="item_details", methods = {"GET", "POST"})
     */
    public function item_details(Request $request, $id)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        if ($item == null) {
            $errorMessage = 'The requested resource is not found on the website.';
            $this->addFlash('error', 'Error requesting the resource');
            return $this->render('NotFound.html.twig', [
                'SystemSettings' => $SystemSettings,
                'OpeningTimes' => $OpeningTimes,
                'errorMessage' => $errorMessage
            ]);
        }

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $userName = $request->request->get('comment')['User_name'];
            $comment->setUserName($userName);
            $comment->setUserEmail($request->request->get('comment')['User_email']);
            $comment->setText($request->request->get('comment')['Text']);

            $date = new \Datetime();

            $comment->setCreatedAt($date);

            $comment->setItem($item);
            $comment->setIsTestimonial(false);

            $entityManager->persist($comment);


            $notification = new Notification;
            $notification->setType('COMMENT');
            $notification->setText($userName . ' commented on post [ ' . $item->getTitle() . ' ]');
            $notification->setInfo($item->getId());
            $notification->setCreatedAt($date);
            $notification->setIsNew(true);

            $entityManager->persist($notification);

            $entityManager->flush();

            $saveuser = $request->request->get('saveuser');

            if ($saveuser) {
                $cookieName = new Cookie(
                    'comment_username_cookie',
                    $comment->getUserName(),
                    time() + (2 * 365 * 24 * 60 * 60)
                );

                $cookieEmail = new Cookie(
                    'comment_useremail_cookie',
                    $comment->getUserEmail(),
                    time() + (2 * 365 * 24 * 60 * 60)
                );

                $response = new Response();
                $response->headers->setcookie($cookieName);
                $response->headers->setcookie($cookieEmail);
                $response->send();
            }

            $this->addFlash('success', 'Comment added successfully');

            return $this->redirectToRoute('item_details', ['id' => $item->getId()]);
        }

        $cookieName = $request->cookies->get('comment_username_cookie');
        $cookieEmail = $request->cookies->get('comment_useremail_cookie');

        return $this->render('home/item_details.html.twig', [
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
            'item' => $item,
            'UserName' => $cookieName,
            'UserEmail' => $cookieEmail,
            'formNewComment' => $form->createView(),
        ]);
    }

    /**
     * @Route("/menu", name="menu")
     */
    public function menu()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findNotDeleted();

        $menu1 = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Title' => 'Menu 1']);
        $menu2 = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Title' => 'Menu 2']);
        $menu4 = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Title' => 'Menu 4']);
        $menu3 = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['Title' => 'Menu 3']);

        return $this->render('home/menu.html.twig', [
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
            'Categories' => $Categories,
            'menu1' => $menu1,
            'menu2' => $menu2,
            'menu4' => $menu4,
            'menu3' => $menu3,
        ]);
    }

    /**
     * @Route("/comment/delete", name="delete_comment")
     */
    public function delete_comment(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-comment', $token)) {
            $userName = $request->request->get('UserName');
            $userEmail = $request->request->get('UserEmail');
            $commentId = $request->request->get('commentId');

            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
            $itemId = $comment->getItem()->getId();

            if ($userName == $comment->getUserName() && $userEmail == $comment->getUserEmail()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($comment);
                $entityManager->flush();

                $this->addFlash('success', 'Comment deleted successfully');

                return $this->redirectToRoute('item_details', ['id' => $itemId]);
            } else {
                $this->addFlash('error', 'Cannot delete comment');
                return $this->redirectToRoute('item_details', ['id' => $itemId]);
            }
        }
        $this->addFlash('error', 'Cannot delete comment');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function gallery()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        $photos = $this->getDoctrine()->getRepository(Gallary::class)->findBy(['isSlide' => 0]);



        return $this->render('home/gallary.html.twig', [
            'SystemSettings' => $SystemSettings,
            'OpeningTimes' => $OpeningTimes,
            'photos' => $photos,
        ]);
    }
}
