<?php

namespace App\Controller;


use App\Form\SocialType;
use App\Form\AddressType;
use App\Form\AppInfoType;

use App\Form\LoginPageBg;
use App\Form\ContactUsType;
use App\Form\LogoImageType;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use App\Form\AboutUsImageType;
use App\Form\ContactHeaderImageType;
use App\Form\GalleryHeaderImageType;
use App\Form\MenuSettingsType;
use App\Form\HomePageImageType;
use App\Form\LocationHeaderImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


Use App\Utils\Interfaces\UploaderInterface;
Use App\Utils\FileUploader;


/**
 * @Route("/admin")
 */
class SystemController extends AbstractController
{
   
   
    /**
     * @Route("/system/edit-address", name = "edit_address")
     */
    public function edit_address(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(AddressType::class, $SystemSettings);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $SystemSettings->SetAddressStreet($request->request->get('address')['Address_Street']);
            $SystemSettings->SetAddressCity($request->request->get('address')['Address_City']);
            $SystemSettings->SetAddressCountry($request->request->get('address')['Address_Country']);
            $SystemSettings->setGoogleIframe($request->request->get('address')['Google_Iframe']);
            $SystemSettings->setLocationLongitude($request->request->get('address')['Location_Longitude']);
            $SystemSettings->setLocationLatitude($request->request->get('address')['Location_Latitude']);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();

            $this->addFlash('success', 'Address updated successfully');

            return $this->redirectToRoute('dashboard');

        }


        return $this->render('admin/system/edit_address.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/edit-app-info", name = "edit_appinfo")
     */
    public function edit_appinfo(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(AppInfoType::class, $SystemSettings);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $SystemSettings->SetAppName($request->request->get('app_info')['AppName']);
            $SystemSettings->SetAppInfo($request->request->get('app_info')['AppInfo']);
            $SystemSettings->SetAppInfo2($request->request->get('app_info')['AppInfo2']);
            $SystemSettings->SetAboutusText($request->request->get('app_info')['Aboutus_Text']);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();

            $this->addFlash('success', 'App info updated successfully');

            return $this->redirectToRoute('dashboard');

        }

        
        return $this->render('admin/system/edit_appinfo.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/system/edit-contact-details", name = "edit_contact_details")
     */
    public function edit_contact_details(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(ContactUsType::class, $SystemSettings);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           
            $entityManager = $this->getDoctrine()->getManager();

            $SystemSettings->SetEmail($request->request->get('contact_us')['Email']);
            $SystemSettings->SetPhone($request->request->get('contact_us')['Phone']);

            
            $entityManager->persist($SystemSettings);
            $entityManager->flush();

            $this->addFlash('success', 'Contact details updated successfully');

            return $this->redirectToRoute('dashboard');

        }

        
        return $this->render('admin/system/edit_contact_details.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/edit-social-details", name = "edit_social_details")
     */
    public function edit_social_details(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $form = $this->createForm(SocialType::class, $SystemSettings);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           
            $entityManager = $this->getDoctrine()->getManager();

            $SystemSettings->setFacebookPageIframe($request->request->get('social')['Facebook_Page_Iframe']);
            $SystemSettings->setTwitterLink($request->request->get('social')['Facebook_Link']);
            $SystemSettings->setTwitterLink($request->request->get('social')['Twitter_Link']);
            $SystemSettings->setInstagramLink($request->request->get('social')['Instagram_Link']);

            
            $entityManager->persist($SystemSettings);
            $entityManager->flush();

            $this->addFlash('success', 'Social media details updated successfully');

            return $this->redirectToRoute('dashboard');

        }

        
        return $this->render('admin/system/edit_social_details.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/website-images", name = "website_images")
     */
    public function website_images()
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $formAppLogo = $this->createForm(LogoImageType::class, $SystemSettings);
        $formHomePageImage = $this->createForm(HomePageImageType::class, $SystemSettings);
        $formAboutUsImage = $this->createForm(AboutUsImageType::class, $SystemSettings);
        $formLoginImage = $this->createForm(LoginPageBg::class, $SystemSettings);
        $formGalleryHeaderImage = $this->createForm(GalleryHeaderImageType::class, $SystemSettings);
        $formLocationHeaderImage = $this->createForm(LocationHeaderImageType::class, $SystemSettings);
        $formContactHeaderImage = $this->createForm(ContactHeaderImageType::class, $SystemSettings);
            

        return $this->render('admin/system/website_images.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'formAppLogo' => $formAppLogo->createView(),
            'formHomePageImage' => $formHomePageImage->createView(),
            'formAboutUsImage' => $formAboutUsImage->createView(),
            'formLoginImage' => $formLoginImage->createView(),
            'formGalleryHeaderImage' => $formGalleryHeaderImage->createView(),
            'formLocationHeaderImage' => $formLocationHeaderImage->createView(),
            'formContactHeaderImage' => $formContactHeaderImage->createView(),
        ]);
    }

     /**
     * @Route("/system/change-logo-image", name = "change_logo_image")
     */
    public function change_logo_image(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $originalAppLogo = $SystemSettings->getAppLogo();

        $formAppLogo = $this->createForm(LogoImageType::class, $SystemSettings);
       
        $formAppLogo->handleRequest($request);
        
        if($formAppLogo->isSubmitted() && $formAppLogo->isValid())
        {
            
            $form = $formAppLogo['AppLogo']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalAppLogo);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->SetAppLogo($fileName);

                $this->addFlash('success', 'Logo image updated successfully');

            }
            else
            {
                $SystemSettings->SetAppLogo($originalAppLogo);

                $this->addFlash('notice', 'Please select a Logo file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing logo image.');
        return $this->redirectToRoute('website_images');
    }

    /**
     * @Route("/system/change-homepage-image", name = "change_HomePage_image")
     */
    public function change_HomePage_image(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $originalHomepage = $SystemSettings->getHomePageBackground();

        $formHomePage = $this->createForm(HomePageImageType::class, $SystemSettings);
       
        $formHomePage->handleRequest($request);
        
        if($formHomePage->isSubmitted() && $formHomePage->isValid())
        {
            
            $form = $formHomePage['HomePage_Background']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalHomepage);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->setHomePageBackground($fileName);

                $this->addFlash('success', 'Home page image updated successfully');

            }
            else
            {
                $SystemSettings->setHomePageBackground($originalHomepage);

                $this->addFlash('notice', 'Please select a home page image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing logo image.');
        return $this->redirectToRoute('website_images');
    }

    /**
     * @Route("/system/change-aboutus-image", name = "change_aboutus_image")
     */
    public function change_aboutus_image(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $originalAboutUsImage = $SystemSettings->getAboutusImage();

        $formAboutusImage = $this->createForm(AboutUsImageType::class, $SystemSettings);
       
        $formAboutusImage->handleRequest($request);
        
        if($formAboutusImage->isSubmitted() && $formAboutusImage->isValid())
        {
            
            $form = $formAboutusImage['Aboutus_Image']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalAboutUsImage);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->SetAboutusImage($fileName);

                $this->addFlash('success', 'About us image updated successfully');

            }
            else
            {
                $SystemSettings->SetAboutusImage($originalAboutUsImage);

                $this->addFlash('notice', 'Please select an about us image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing logo image.');
        return $this->redirectToRoute('website_images');
    }

     /**
     * @Route("/system/change-login-image", name = "change_login_image")
     */
    public function change_login_image(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $originalLoginImage = $SystemSettings->getLoginPageBackground();

        $formLoginImage = $this->createForm(LoginPageBg::class, $SystemSettings);
       
        $formLoginImage->handleRequest($request);
        
        if($formLoginImage->isSubmitted() && $formLoginImage->isValid())
        {
            
            $form = $formLoginImage['LoginPageBackground']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalLoginImage);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->setLoginPageBackground($fileName);

                $this->addFlash('success', 'Login background image updated successfully');

            }
            else
            {
                $SystemSettings->setLoginPageBackground($originalLoginImage);

                $this->addFlash('notice', 'Please select a login background image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing login background image.');
        return $this->redirectToRoute('website_images');
    }

     /**
     * @Route("/system/change-gallery-header", name = "change_gallery_header")
     */
    public function change_gallery_header(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $originalgalleryHeader = $SystemSettings->getGalleryHeaderImage();

        $formGalleryHeader = $this->createForm(GalleryHeaderImageType::class, $SystemSettings);
       
        $formGalleryHeader->handleRequest($request);

        if($formGalleryHeader->isSubmitted() && $formGalleryHeader->isValid())
        {
            
            $form = $formGalleryHeader['GalleryHeaderImage']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalgalleryHeader);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->setGalleryHeaderImage($fileName);

                $this->addFlash('success', 'Gallery header image updated successfully');

            }
            else
            {
                $SystemSettings->setGalleryHeaderImage($originalgalleryHeader);

                $this->addFlash('notice', 'Please select a gallery header image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing gallery header image.');
        return $this->redirectToRoute('website_images');
    }

     /**
     * @Route("/system/change-location-header", name = "change_location_header")
     */
    public function change_location_header(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $originallocationHeader = $SystemSettings->getLocationHeaderImage();

        $formLocationHeader = $this->createForm(LocationHeaderImageType::class, $SystemSettings);
       
        $formLocationHeader->handleRequest($request);

        if($formLocationHeader->isSubmitted() && $formLocationHeader->isValid())
        {
            
            $form = $formLocationHeader['LocationHeaderImage']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originallocationHeader);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->setLocationHeaderImage($fileName);

                $this->addFlash('success', 'Location header image updated successfully');

            }
            else
            {
                $SystemSettings->setLocationHeaderImage($originallocationHeader);

                $this->addFlash('notice', 'Please select a Location header image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing location header image.');
        return $this->redirectToRoute('website_images');
    }

    
    /**
     * @Route("/system/change-contact-header", name = "change_contact_header")
     */
    public function change_contact_header(Request $request, UploaderInterface $fileUploader)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);

        $originalcontactHeader = $SystemSettings->getContactHeaderImage();

        $formContactHeader = $this->createForm(ContactHeaderImageType::class, $SystemSettings);
       
        $formContactHeader->handleRequest($request);

        if($formContactHeader->isSubmitted() && $formContactHeader->isValid())
        {
            
            $form = $formContactHeader['ContactHeaderImage']->getData();

            if ($form) {
                $fileUploader->delete('/' . SystemSettings::uploadFolder . $originalcontactHeader);
               
                $fileName = $fileUploader->upload($form);
                $SystemSettings->setContactHeaderImage($fileName);

                $this->addFlash('success', 'Contact us header image updated successfully');

            }
            else
            {
                $SystemSettings->setContactHeaderImage($originalcontactHeader);

                $this->addFlash('notice', 'Please select a contact us header image file');
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();


            return $this->redirectToRoute('website_images');
        }


        $this->addFlash('error', 'Error changing contact us header image.');
        return $this->redirectToRoute('website_images');
    }


    /**
     * @Route("/system/menu-settings", name = "menu_settings")
     */
    public function menu_settings(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();
            
        $form = $this->createForm(MenuSettingsType::class, $SystemSettings);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $SystemSettings->setMenuCoverHeight($request->request->get('menu_settings')['menu_cover_height']);
            $SystemSettings->setMenuPageHeight($request->request->get('menu_settings')['menu_page_height']);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($SystemSettings);
            $entityManager->flush();

            $this->addFlash('success', 'Settings saved successfully');

            return $this->redirectToRoute('dashboard');
        }


        return $this->render('admin/system/menu_settings.html.twig', [
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }
}
