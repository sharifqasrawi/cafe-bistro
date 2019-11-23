<?php

namespace App\Controller;


use App\Entity\Category;


use App\Form\CategoryType;

use App\Entity\Notification;
use App\Entity\SystemSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


Use App\Utils\Interfaces\UploaderInterface;
Use App\Utils\FileUploader;

/**
 * @Route("/admin")
 */
class CategoryController extends AbstractController
{
      /**
     * @Route("/categories/list-categories/{d},{page}", methods = {"GET"} ,defaults = {"page":"1", "d" : "0"} , name="list_categories")
     */
    public function list_categories($d, $page)
    {

        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        if($d == 0 or $d == 1)
        {
            $Categories = $this->getDoctrine()->getRepository(Category::class)->findAllByDeleted($d, $page);
        }
        else
        {
            $Categories = $this->getDoctrine()->getRepository(Category::class)->findAllByDeleted(0, $page);
        }

        return $this->render('admin/category/list_categories.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Categories' => $Categories,
            'CategoriesCount' => count($Categories),
            'filterd' => $d,
        ]);
    }


    /**
     * @Route("/categories/trashed-categories", name="trashed_categories")
     */
    public function trashed_categories()
    {

        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $Categories = $this->getDoctrine()->getRepository(Category::class)->findTrashed();
        $filter = 'trashed';

        return $this->render('admin/category/list_categories.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'Categories' => $Categories,
            'CategoriesCount' => count($Categories),
            'filter' => $filter,
        ]);
    }

    /**
     * @Route("/categories/trash-category/", name="trash_category")
     */
    public function trash_category( Request $request)
    {

        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('trash-category', $token)) 
        {
            $catId = $request->request->get('catId');
            $category = $this->getDoctrine()->getRepository(Category::class)->find($catId);

            if($category == null)
            {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            }
            else
            {
                $category->setIsDeleted(1);
                $date = new \DateTime;
                $category->setDeletedAt($date);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($category);
                $entityManager->flush();

                $this->addFlash('notice', 'Category added to trash');

                return $this->redirectToRoute('list_categories' , [ 'd' => 0 ]);
            }
        }
        $this->addFlash('error', 'Invaild token');
        return $this->redirectToRoute('list_categories', [ 'd' => 0 ]);
    }

    
    /**
     * @Route("/categories/restore-category/", name="restore_category")
     */
    public function restore_category(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('restore-category', $token)) 
        {
            $catId = $request->request->get('catId');
            $category =  $Categories = $this->getDoctrine()->getRepository(Category::class)->find($catId);

            if($category == null)
            {
                $errorMessage = 'The requested resource is not found on the website.';
                $this->addFlash('error', 'Error requesting the resource');
                return $this->render('admin/NotFound.html.twig', [
                    'errorMessage' => $errorMessage
                ]);
            }
            else
            {
                $category->setIsDeleted(0);
                $date = new \DateTime;
                $category->setDeletedAt($date);

                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($category);
                $entityManager->flush();

                $this->addFlash('success', 'Category restored from trash');

                return $this->redirectToRoute('list_categories', [ 'd' => 1 ]);
            }
        }

        $this->addFlash('error', 'Invaild token');
        return $this->redirectToRoute('list_categories', [ 'd' => 1 ]);
    }


     /**
     * @Route("/categories/delete-category/", name="delete_category")
     */
    public function delete_category(Request $request)
    {
        $token = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-category', $token)) 
        {
            $catId = $request->request->get('catId');
            $category = $this->getDoctrine()->getRepository(Category::class)->find($catId);

            if($category == null)
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

                $entityManager->remove($category);

                $entityManager->flush();

                $this->addFlash('notice', 'Category deleted successfully');

                return $this->redirectToRoute('list_categories', [ 'd' => 1 ]);
            }
        }

        $this->addFlash('error', 'Invaild token');
        return $this->redirectToRoute('trashed_categories', [ 'd' => 1 ]);
    }

     /**
     * @Route("/categories/new-cateogry", name="new_category")
     */
    public function new_category(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $category->setTitle($request->request->get('category')['Title']);
            $category->setIsOnHomePage($request->request->get('category')['IsOnHomePage']);

            $icon = $request->request->get('category')['icon'];

            if($icon != null)
            {
                $category->setIcon($icon);
            }

            $date = new \DateTime();
            $category->setCreatedAt($date);
            $category->setIsDeleted(false);

            $file = $form['header_image']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $category->setHeaderImage($fileName);
            }

            
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category added successfully');

            return $this->redirectToRoute('list_categories', [ 'd' => 0 ]);
        }

        return $this->render('admin/category/new_category.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),

        ]);
    }

     /**
     * @Route("/categories/edit-cateogry/{id}", name="edit_category")
     */
    public function edit_category(Category $category ,Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(CategoryType::class, $category);
        
        $originalImg = $category->getHeaderImage();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            
            $category->setTitle($request->request->get('category')['Title']);

            $category->setIsOnHomePage($request->request->get('category')['IsOnHomePage']);
            
            $icon = $request->request->get('category')['icon'];
            
            if($icon != null)
            {
                $category->setIcon($icon);
            }
            
            $date = new \DateTime();
            $category->setCreatedAt($date);
            $category->setIsDeleted(false);
            
            $file = $form['header_image']->getData();
            
            if ($file) {
                $fileUploader->delete('/' . Category::uploadFolder . $originalImg);
               
                $fileName = $fileUploader->upload($file);
                $category->setHeaderImage($fileName);
            }
            else
            {
                $category->setHeaderImage($originalImg );
            }

            
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category updated successfully');

            return $this->redirectToRoute('list_categories', [ 'd' => 0 ]);
        }

        
        return $this->render('admin/category/edit_category.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'category' => $category,
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/categories/view-cateogry/{id}", name="view_category")
     */
    public function view_category(Category $category)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();


        if($category == null)
        {
            $errorMessage = 'The requested resource is not found on the website.';
            $this->addFlash('error', 'Error requesting the resource');
            return $this->render('admin/NotFound.html.twig', [
                'errorMessage' => $errorMessage
            ]);
        }
        else
        {
            return $this->render('admin/category/view_category.html.twig', [
                'category' => $category,
                'SystemSettings' => $SystemSettings,
                'NewNotifications' => $NewNotifications,
                'NewNotificationsCount' => count($NewNotifications),
            ]);
        }
    }

}
