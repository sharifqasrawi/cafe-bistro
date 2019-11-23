<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\SystemSettings;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/notifications/{page}", defaults = {"page" : "1"} , name="notifications")
     */
    public function index($page)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAllPaged($page);
        
        
        $newnotifications = $this->getDoctrine()->getRepository(Notification::class)->findBy(['isNew' => true]);
        
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach ($newnotifications as $n) {
            $n->setIsNew(false);
            $entityManager->persist($n);
        }
        
        $entityManager->flush();
        
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        return $this->render('admin/notification/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'notifications' => $notifications,
            'notificationsCount' => count($notifications),
        ]);
    }

    /**
     * @Route("/clear-notifications/", name="clear_notifications")
     */
    public function clear_notifications()
    {
        $notifications = $this->getDoctrine()->getRepository(Notification::class)->findBy(['isNew' => true]);
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($notifications as $n) {
            $n->setIsNew(false);
            $entityManager->persist($n);
        }


        $entityManager->flush();

        return $this->redirectToRoute('dashboard');
    }
}
