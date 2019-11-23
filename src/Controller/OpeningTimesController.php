<?php

namespace App\Controller;


use App\Entity\OpeningTime;
use App\Entity\Notification;
use App\Entity\SystemSettings;
use App\Form\OpeningTimesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class OpeningTimesController extends AbstractController
{
     /**
     * @Route("/opening-times", name = "edit_opening_times")
     */
    public function edit_opening_times(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();
        $NewNotifications = $this->getDoctrine()->getRepository(Notification::class)->findTop10New();

        $ot = new OpeningTime;
        $form = $this->createForm(OpeningTimesType::class, $ot);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $ot->SetDay($request->request->get('opening_times')['Day']);
            $ot->SetDayOrder($request->request->get('opening_times')['DayOrder']);
            $ot->SetOpenHour($request->request->get('opening_times')['Open_hour']);
            $ot->SetCloseHour($request->request->get('opening_times')['Close_hour']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ot);
            $entityManager->flush();

            $this->addFlash('success', 'Opening time added successfully');

            return $this->redirectToRoute('edit_opening_times');
        }

        return $this->render('admin/edit_opening_times.html.twig', [
            'OpeningTimes' => $OpeningTimes,
            'SystemSettings' => $SystemSettings,
            'NewNotifications' => $NewNotifications,
            'NewNotificationsCount' => count($NewNotifications),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-opening-time", name = "delete_opening_time")
     */
    public function delete_opening_time(Request $request)
    {
        $SystemSettings = $this->getDoctrine()->getRepository(SystemSettings::class)->find(1);
        $OpeningTimes = $this->getDoctrine()->getRepository(OpeningTime::class)->getOrdered();

        $token = $request->request->get('token');

        
        if($this->isCsrfTokenValid('delete-ot', $token)) 
        {
            $oId = $request->request->get('oId');
            $ot =  $this->getDoctrine()->getRepository(OpeningTime::class)->find($oId);

            if($ot == null)
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

                $entityManager->remove($ot);

                $entityManager->flush();

                $this->addFlash('notice', 'Opening time deleted successfully');

                return $this->redirectToRoute('edit_opening_times');
            }
           
        }
            
        $this->addFlash('error', 'Invaild token');

        return $this->redirectToRoute('edit_opening_times');

    }
}
