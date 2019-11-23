<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="list_messages")
     */
    public function index()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $messages = $this->getDoctrine()->getRepository(Message::class)->findOrdered();
        $messagesCount = count($this->getDoctrine()->getRepository(Message::class)->findOrdered());
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        return $this->render('admin/message/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'messages' => $messages,
            'messagesCount' => $messagesCount,
        ]);
    }

    /**
     * @Route("/messages/details/{id}", name="message_details")
     */
    public function message_details(Message $message)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $entityManager = $this->getDoctrine()->getManager();

        $message->SetIsSeen(true);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('admin/message/message_details.html.twig', [
            'SystemSettings' => $SystemSettings,
            'message' => $message,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);
    }

    /**
     * @Route("/messages/mark-unseen/{id}", name="mark_unseen")
     */
    public function mark_unseen(Message $message)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $entityManager = $this->getDoctrine()->getManager();

        $message->SetIsSeen(0);

        $entityManager->persist($message);
        $entityManager->flush();

        $this->addFlash('notice', 'Message marked as unseen');

        return $this->redirectToRoute('list_messages');
    }

    /**
     * @Route("/messages/delete-message", name="delete_message")
     */
    public function delete_message(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        
        $token = $request->request->get('token');

        $msgId = $request->request->get('msgId');
        
        $message = $this->getDoctrine()->getRepository(Message::class)->find($msgId);

        if ($this->isCsrfTokenValid('delete-message', $token)) {

            if ($message == null) {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            } else {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($message);
                $entityManager->flush();

                $this->addFlash('notice', 'Message deleted successfully');
                return $this->redirectToRoute('list_messages');
            }
        }

        $this->addFlash('error', 'Token error');
      
        return $this->redirectToRoute('list_messages');

    }

    /**
     * @Route("/messages/create", name="create_message")
     */
    public function create_message(Request $request, \Swift_Mailer $mailer)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('send-email', $token)) 
        {
            $to = $request->get('email');
            $subject = $request->get('subject');
            $body = $request->get('message');

            $email = (new \Swift_Message($subject))
                    ->setFrom('no-reply@lafontaine.com.au')
                    ->setTo($to)
                    ->setBody($body);

            $mailer->send($email);

            $this->addFlash('success', 'Your email was sent successfully');
            return $this->redirectToRoute('dashboard');
        }
        else
        {
            $this->addFlash('Error', 'Error sending email');
        }

        return $this->render('admin/message/create_message.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
        ]);

    }

    /**
     * @Route("/messages/reply/{id}", name="reply_message")
     */
    public function reply_message(Message $message, Request $request, \Swift_Mailer $mailer)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('send-email', $token)) 
        {
            $to = $request->get('email');
            $subject = $request->get('subject');
            $body = $request->get('message');

            $email = (new \Swift_Message($subject))
                    ->setFrom('no-reply@lafontaine.com.au')
                    ->setTo($to)
                    ->setBody($body);

            $mailer->send($email);

            $this->addFlash('success', 'Your email was sent successfully');
            return $this->redirectToRoute('dashboard');
        }
        else
        {
            $this->addFlash('Error', 'Error sending email');
        }

        return $this->render('admin/message/reply_message.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'email' => $message->getEmail()
        ]);

    }

}
