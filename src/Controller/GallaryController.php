<?php

namespace App\Controller;

use App\Entity\Gallary;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use App\Form\GallaryImageType;
use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */

class GallaryController extends AbstractController
{
    /**
     * @Route("/gallery/{page}", defaults = { "page" : 1 } ,name="gallary")
     */
    public function index(int $page, Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $Images = $this->getDoctrine()->getRepository(Gallary::class)->findAllPaged($page);
        $ImagesCount = count($this->getDoctrine()->getRepository(Gallary::class)->findAll());

        $gallaryImg = new Gallary;

        $form = $this->createForm(GallaryImageType::class, $gallaryImg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gallaryImg->setTitle($request->request->get('gallary_image')['Title']);
            $gallaryImg->setIsSlide(false);
            $gallaryImg->setIsItem(false);

            $date = new \DateTime();
            $gallaryImg->setCreatedAt($date);

            $form = $form['Image']->getData();

            if ($form) {
                $fileName = $fileUploader->upload($form);
                $gallaryImg->setImage($fileName);
            }


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($gallaryImg);
            $entityManager->flush();

            $this->addFlash('success', 'Image added successfully');

            return $this->redirectToRoute('gallary');
        }

        return $this->render('admin/gallary/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Images' => $Images,
            'ImagesCount' => $ImagesCount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gallary/delete-image", name = "delete_gallary_image")
     */
    public function delete_gallary_image(Request $request, UploaderInterface $fileUploader)
    {

        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-image', $token)) {
            $imageId = $request->request->get('imageId');

            $image = $this->getDoctrine()->getRepository(Gallary::class)->find($imageId);

            if (!$image->getIsItem()) {

                $fileUploader->delete('/' . Gallary::uploadFolder . $image->getImage());

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->remove($image);

                $entityManager->flush();

                $this->addFlash('success', 'Image deleted successfully');

                return $this->redirectToRoute('gallary');
            } else {
                $this->addFlash('error', 'Please remove this image from the items list.');

                return $this->redirectToRoute('gallary');
            }
        }

        $this->addFlash('error', 'Error deleting image');

        return $this->redirectToRoute('gallary');
    }
}
