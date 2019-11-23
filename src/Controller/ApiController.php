<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Gallary;
use App\Entity\Item;
use App\Entity\SystemSettings;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/system", name="system")
     */
    public function system(SerializerInterface $serializer)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->findAll();


        $json = $serializer->serialize(
            $SystemSettings,
            'json'
        );

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api-menu", name="api_menu")
     */
    public function api_menu(SerializerInterface $serializer)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findNotDeleted();


        $json = $serializer->serialize(
            $categories,
            'json'
        );

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/api-gallery", name="api_gallery")
     */
    public function gallery(SerializerInterface $serializer)
    {
        $photos = $this->getDoctrine()->getRepository(Gallary::class)->findBy([], ['Created_at' => 'DESC']);


        $json = $serializer->serialize(
            $photos,
            'json'
        );

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/send-message", name="send_message")
     */
    public function send_message(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $response = new Response();

        $message = new Message;

        $message->setName($data['name']);
        $message->setEmail($data['email']);
        $message->setSubject($data['subject']);
        $message->setText($data['text']);

        $datetime = new \DateTime;

        $message->setCreatedAt($datetime);
        $message->setIsSeen(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($message);
        $entityManager->flush();



        $response->setContent(json_encode(array('id' => $message->getId())));
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }

    /**
     * @Route("/comment", name="comment")
     */
    public function comment(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $response = new Response();

        $comment = new Comment;

        $item = $this->getDoctrine()->getRepository(Item::class)->find($data['itemId']);

        $comment->setItem($item);
        $comment->setUserName($data['name']);
        $comment->setUserEmail($data['email']);
        $comment->setText($data['text']);
        $comment->setIsTestimonial(false);
        $datetime = new \DateTime;
        $comment->setCreatedAt($datetime);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();



        $response->setContent(json_encode(array('id' => $comment->getId())));
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }
}
