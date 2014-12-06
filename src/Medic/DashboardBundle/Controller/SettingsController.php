<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Medic\DashboardBundle\Entity\Slot;
use Medic\DashboardBundle\Entity\Holiday;

/**
* @Route("/admin")
*/

class SettingsController extends Controller
{
  /**
  * @Route("/settings", name="settings")
  * @Template()
  * @Method("GET")
  */
  public function indexAction()
  {
    return array();
  }

  /**
  * @Route("/settings/slots", name="settings_slots")
  * @Method("GET")
  * @Template()
  */
  public function onlineAction()
  {
    $now = new \DateTime();
    $monday = $now->setISODate($now->format('Y'), $now->format('W'));
    $days = array();

    $user = $this->getUser();

    $em = $this->getDoctrine()->getManager();

    $query = $em->createQuery(
    'SELECT c
    FROM MedicDashboardBundle:Calendar c
    LEFT JOIN c.preUsers l
    WHERE l.lastViewed = 1 AND l.user = :user AND c.isActive = 1'
    )->setParameters(array(
      'user' => $user
    ));
    $calendar = $query->getSingleResult();

    $query = $em->createQuery(
    'SELECT CONCAT(s.weekDay, \'-\', s.hour, \'-\', s.minutes) AS l
    FROM MedicDashboardBundle:Slot s
    WHERE s.calendar = :calendar'
    )->setParameters(array(
      'calendar' => $calendar
    ));
    $slotsTmp = $query->getResult();

    $slots = array();
    foreach ($slotsTmp as $s) {
      $slots[] = $s['l'];
    }

    for ($i=0; $i < 6; $i++) {
      $day = array();
      $time = new \DateTime('2015-01-01 08:00:00');
      for ($j=0; $j < 48; $j++) {
        $day[] = $time->format('H:i');
        $time->modify('+15 minutes');
      }
      $days[] = $day;
    }
    return array(
      'days' => $days,
      'monday' => $monday,
      'slots' => $slots
    );
  }
  /**
  * @Route("/settings/slots")
  * @Method("POST")
  */
  public function onlinePostAction()
  {
    $request = $this->getRequest();
    $user = $this->getUser();

    $em = $this->getDoctrine()->getManager();

    $query = $em->createQuery(
    'SELECT c
    FROM MedicDashboardBundle:Calendar c
    LEFT JOIN c.preUsers l
    WHERE l.lastViewed = 1 AND l.user = :user AND c.isActive = 1'
    )->setParameters(array(
      'user' => $user
    ));
    $calendar = $query->getSingleResult();

    $query = $em->createQuery(
    'SELECT s
    FROM MedicDashboardBundle:Slot s
    WHERE s.calendar = :calendar'
    )->setParameters(array(
      'calendar' => $calendar
    ));
    $slots = $query->getResult();

    foreach ($slots as $slot) {
      $em->remove($slot);
    }
    $em->flush();

    $now = new \DateTime();
    $monday = $now->setISODate($now->format('Y'), $now->format('W'));
    $days = array();
    for ($i=0; $i < 6; $i++) {
      $day = array();
      $time = new \DateTime('2015-01-01 08:00:00');
      for ($j=0; $j < 48; $j++) {
        $day[] = $time->format('H:i');
        if ($request->get('h-' . $monday->format('N') . '-' . $time->format('H:i'))) {
          $slot = new Slot();
          $slot->setWeekDay($monday->format('N'));
          $slot->setHour($time->format('H'));
          $slot->setMinutes($time->format('i'));
          $slot->setCalendar($calendar);
          $em->persist($slot);
        }
        $time->modify('+15 minutes');
      }
      $days[] = $day;
      $monday->modify('+1 day');
    }
    $em->flush();
    return $this->redirect($this->generateUrl('settings_slots'));
  }

  /**
  * @Route("/settings/holidays", name="settings_holidays")
  * @Method("GET")
  * @Template()
  */
  public function holidaysAction()
  {
    $now = new \DateTime();

    $em = $this->getDoctrine()->getManager();

    $user = $this->getUser();

    $query = $em->createQuery(
    'SELECT c
    FROM MedicDashboardBundle:Calendar c
    LEFT JOIN c.preUsers l
    WHERE l.lastViewed = 1 AND l.user = :user AND c.isActive = 1'
    )->setParameters(array(
      'user' => $user
    ));
    $calendar = $query->getSingleResult();

    $query = $em->createQuery(
    'SELECT h
    FROM MedicDashboardBundle:Holiday h
    WHERE h.calendar = :calendar AND h.start > :now
    ORDER BY h.start ASC'
    )->setParameters(array(
      'now' => new \DateTime(),
      'calendar' => $calendar
    ));
    $holidays = $query->getResult();

    return array(
      'holidays' => $holidays
    );
  }

  /**
  * @Route("/settings/holidays/new", name="settings_holidays_new")
  * @Method("GET")
  * @Template("MedicDashboardBundle:Settings:form.html.twig")
  */
  public function newHolidayAction()
  {
    return array();
  }

  /**
  * @Route("/settings/holidays", name="settings_holidays_post")
  * @Method("POST")
  */
  public function postHolidayAction()
  {
    $request = $this->getRequest();
    $requestBag = $request->request;
    $form = $requestBag->get('form');

    $em = $this->getDoctrine()->getManager();

    $user = $this->getUser();

    $query = $em->createQuery(
    'SELECT c
    FROM MedicDashboardBundle:Calendar c
    LEFT JOIN c.preUsers l
    WHERE l.lastViewed = 1 AND l.user = :user AND c.isActive = 1'
    )->setParameters(array(
      'user' => $user
    ));
    $calendar = $query->getSingleResult();

    if ($form['id']) {
      $holiday = $em->getRepository('MedicDashboardBundle:Holiday')->find($form['id']);
      if (!$holiday) throw $this->createNotFoundException('The holiday does not exist');
    } else {
      $holiday = new Holiday();
      $holiday->setCalendar($calendar);
    }

    $holiday->setTitle($form['title']);

    $start = new \DateTime($form['start'].' '.$form['startt']);
    $end = new \DateTime($form['end'].' '.$form['endt']);

    $holiday->setStart($start);
    $holiday->setEnd($end);

    $em->persist($holiday);
    $em->flush();

    return $this->redirect($this->generateUrl('settings_holidays'));
  }
}
