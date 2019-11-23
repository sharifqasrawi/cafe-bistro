<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Comment;
use App\Entity\Message;


use App\Entity\Category;
use App\Entity\Gallary;
use App\Entity\Notification;
use App\Entity\OpeningTime;
use App\Entity\SystemSettings;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Utils\Interfaces\UploaderInterface;
use App\Utils\FileUploader;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {

        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $ItemsCount = count($this->getDoctrine()->getRepository(Item::class)->findAll());
        $CategoriesCount = count($this->getDoctrine()->getRepository(Category::class)->findNotDeleted());
        $CommentsCount = count($this->getDoctrine()->getRepository(Comment::class)->findAll());
        $MessagesCount = count($this->getDoctrine()->getRepository(Message::class)->findAll());
        $ImagesCount = count($this->getDoctrine()->getRepository(Gallary::class)->findAll());
        $OpeningTimesCount = count($this->getDoctrine()->getRepository(OpeningTime::class)->findAll());
        $UsersCount = count($this->getDoctrine()->getRepository(User::class)->findAll());
        $NotificationsCount = $this->getDoctrine()->getRepository(Notification::class)->findAll();


        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        return $this->render('admin/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'CategoriesCount' => $CategoriesCount,
            'ItemsCount' => $ItemsCount,
            'CommentsCount' => $CommentsCount,
            'MessagesCount' => $MessagesCount,
            'OpeningTimesCount' => $OpeningTimesCount,
            'ImagesCount' => $ImagesCount,
            'UsersCount' => $UsersCount,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'NotificationsCount' => count($NotificationsCount),
        ]);
    }
}
