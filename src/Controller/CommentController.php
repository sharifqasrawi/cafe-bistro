<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/comments/list/{page}", defaults = { "page": "1" } ,name="list_comments")
     */
    public function index($page)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $comments = $this->getDoctrine()->getRepository(Comment::class)->findOrdered($page);
        $commentsCount = count($comments);

        return $this->render('admin/comment/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'comments' => $comments,
            'commentsCount' => $commentsCount,
        ]);
    }

    
    /**
     * @Route("/comments/delete", name="delete_comment")
     */
    public function delete_comment(Request $request)
    {

        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-comment', $token)) 
        {
            $commentId = $request->request->get('commentId');
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);

            if($comment == null)
            {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            }
            else
            {

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->remove($comment);

                $entityManager->flush();

                $this->addFlash('notice', 'Comment deleted successfully');

                $page = $request->request->get('page');

                return $this->redirectToRoute('list_comments');
            }
        }

        return $this->redirectToRoute('list_comments');
    }

    /**
     * @Route("/comments/set-unset-testimonial/{id}", name="set_unset_testimonial")
     */
    public function set_unset_testimonial(Comment $comment)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $comment->setIsTestimonial(!$comment->getIsTestimonial());
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('list_comments');
    }
}
