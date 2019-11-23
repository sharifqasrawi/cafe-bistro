<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Entity\Category;
use App\Entity\Gallary;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/items/list/{d},{page}", methods = {"GET"} ,defaults = {"page":"1", "d" : "0"} ,name="list_items")
     */
    public function index($d, $page, Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['IsDeleted' => 0], ['Title' => 'ASC']);

        $categoryId = $request->get('category');

        if ($d == 0 or $d == 1) {
            if ($categoryId != null) {
                $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);
                $Items = $this->getDoctrine()->getRepository(Item::class)->findAllByDeletedAndCategory($d, $categoryId, $page);
            } else {
                $Items = $this->getDoctrine()->getRepository(Item::class)->findAllByDeleted($d, $page);
            }
        } else {
            $Items = $this->getDoctrine()->getRepository(Item::class)->findAllByDeleted(0, $page);
        }

        $ItemsCount = count($Items);



        return $this->render('admin/item/index.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Items' => $Items,
            'ItemsCount' => $ItemsCount,
            'filterd' => $d,
            'Categories' => $Categories,
        ]);
    }

    /**
     * @Route("/items/trash-item", name="trash_item")
     */
    public function trash_item(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('trash-item', $token)) {
            $itemId = $request->request->get('itemId');
            $item = $this->getDoctrine()->getRepository(Item::class)->find($itemId);

            if ($item == null) {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            } else {
                $item->setIsDeleted(1);
                $date = new \DateTime;
                $item->setDeletedAt($date);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($item);
                $entityManager->flush();

                $this->addFlash('notice', 'Item added to trash');

                return $this->redirectToRoute('list_items', ['d' => 0]);
            }
        }

        $this->addFlash('error', 'Invaild token');

        return $this->redirectToRoute('list_items', ['d' => 0]);
    }

    /**
     * @Route("/items/restore-item", name="restore_item")
     */
    public function restore_item(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('restore-item', $token)) {
            $itemId = $request->request->get('itemId');
            $item = $this->getDoctrine()->getRepository(Item::class)->find($itemId);

            if ($item == null) {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            } else {
                $item->setIsDeleted(0);
                $date = new \DateTime;
                $item->setDeletedAt($date);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($item);
                $entityManager->flush();

                $this->addFlash('success', 'Item restored from trash');

                return $this->redirectToRoute('list_items', ['d' => 0]);
            }
        }

        $this->addFlash('error', 'Invaild token');

        return $this->redirectToRoute('list_items', ['d' => 1]);
    }

    /**
     * @Route("/items/create", name="create_item")
     */
    public function create_item(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findNotDeleted();

        $item = new Item;
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catId = $request->request->get('category');
            if ($catId == null) {
                $this->addFlash('error', 'Please select a category');
                return $this->render('admin/item/create_item.html.twig', [
                    'SystemSettings' => $SystemSettings,
                    'Categories' => $Categories,
                    'form' => $form->createView(),
                ]);
            }

            $cat = $this->getDoctrine()->getRepository(Category::class)->find($catId);

            $item->setTitle($request->request->get('item')['Title']);
            $item->setDescription($request->request->get('item')['Description']);
            $item->setPrice($request->request->get('item')['Price']);
            $item->setIsGallery($request->request->get('item')['isGallery']);
            $item->setCategory($cat);

            $date = new \DateTime();
            $item->setCreatedAt($date);
            $item->setCreatedBy('Sharif Qasrawi');
            $item->setIsDeleted(false);

            $file = $form['Image']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $item->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();


            if ($request->request->get('item')['isGallery']) {
                $gallery = new Gallary;
                $gallery->setTitle($item->getTitle());
                $gallery->setImage($item->getImage());
                $gallery->setCreatedAt($date);
                $gallery->setIsItem(true);
                $gallery->setIsSlide(false);

                $entityManager->persist($gallery);
            }

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item created successfully');

            return $this->redirectToRoute('list_items', ['d' => 0]);
        }

        return $this->render('admin/item/create_item.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Categories' => $Categories,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/items/edit/{id}", name="edit_item")
     */
    public function edit_item(Item $item, Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findNotDeleted();

        $originalImg = $item->getImage();
        $originalIsItem = $item->getIsGallery();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catId = $request->request->get('category');
            if ($catId == null) {
                $this->addFlash('error', 'Please select a category');
                return $this->render('admin/item/create_item.html.twig', [
                    'SystemSettings' => $SystemSettings,
                    'Categories' => $Categories,
                    'form' => $form->createView(),
                ]);
            }

            $cat = $this->getDoctrine()->getRepository(Category::class)->find($catId);

            $item->setTitle($request->request->get('item')['Title']);
            $item->setDescription($request->request->get('item')['Description']);
            $item->setPrice($request->request->get('item')['Price']);
            $item->setIsGallery($request->request->get('item')['isGallery']);
            $item->setCategory($cat);

            $date = new \DateTime();
            $item->setCreatedAt($date);
            $item->setCreatedBy('Sharif Qasrawi');
            $item->setIsDeleted(false);

            $file = $form['Image']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $item->setImage($fileName);
            } else {
                $item->setImage($originalImg);
            }

            $entityManager = $this->getDoctrine()->getManager();

            if ($request->request->get('item')['isGallery'] && !$originalIsItem) {
                $gallery = new Gallary;
                $gallery->setTitle($item->getTitle());
                $gallery->setImage($item->getImage());
                $gallery->setCreatedAt($date);
                $gallery->setIsItem(true);
                $gallery->setIsSlide(false);
                $entityManager->persist($gallery);
            } else if (!$request->request->get('item')['isGallery']) {
                $gallery = $this->getDoctrine()->getRepository(Gallary::class)->findOneBy(['Image' => $item->getImage()]);
                if ($gallery != null)
                    $entityManager->remove($gallery);
            }

            $entityManager->persist($item);
            $entityManager->flush();

            $this->addFlash('success', 'Item updated successfully');

            return $this->redirectToRoute('list_items', ['d' => 0]);
        }

        return $this->render('admin/item/edit_item.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Categories' => $Categories,
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }
}
