<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Medic\DashboardBundle\Entity\Calendar;
use Medic\DashboardBundle\Entity\UserCalendar as Link;

class CalendarController extends Controller
{
    /**
     * @Route("/calendar/default/{hash}", name="calendar_default", defaults={"hash"=NULL})
     */
    public function defaultAction($hash)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		foreach ($user->getPreCalendars() as $link) {
			$link->setLastViewed(0);
		}
		if ($hash) {
			$query = $em->createQuery(
			    'SELECT l
			    FROM MedicDashboardBundle:UserCalendar l
				LEFT JOIN l.calendar c
			    WHERE c.hash = :hash AND l.user = :user AND c.isActive = 1'
			)->setParameters(array(
				'user' => $user,
				'hash' => $hash
			));
			$link = $query->getSingleResult();
		} else {
			$query = $em->createQuery(
			    'SELECT l
			    FROM MedicDashboardBundle:UserCalendar l
				LEFT JOIN l.calendar c
			    WHERE l.user = :user AND c.isActive = 1'
			)->setParameters(array(
				'user' => $user
			));
			$link = $query->getSingleResult();
		}
		$link->setLastViewed(1);
		$em->flush();
		return $this->redirect($this->generateUrl('home'));
    }
	
    /**
     * @Route("/calendar/new", name="calendar_new")
     */
    public function newAction()
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		
		$calendar = new Calendar();
		$calendar->setName('Untitled');
		$calendar->setIsActive(true);
		$em->persist($calendar);
		$link = new Link();
		$link->setRole('owner');
		$link->setLastViewed(1);
		$link->setCalendar($calendar);
		$link->setUser($user);
		$em->persist($link);
		$em->flush();
		
		return $this->redirect($this->generateUrl('calendar_default', array(
			'hash' => $calendar->getHash()
		)));
    }
	
    /**
     * @Route("/calendar/cancel/{hash}", name="calendar_cancel")
     */
    public function cancelAction($hash)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		foreach ($user->getPreCalendars() as $link) {
			$link->setLastViewed(0);
		}
		$query = $em->createQuery(
		    'SELECT l
		    FROM MedicDashboardBundle:UserCalendar l
			LEFT JOIN l.calendar c
		    WHERE c.hash = :hash AND l.user = :user'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash
		));
		$link = $query->getSingleResult();
		if ($link) {
			$calendar = $link->getCalendar();
			$em->remove($link);
			$em->flush();
		} else {
			return $this->redirect($this->generateUrl('home'));
		}
		return $this->redirect($this->generateUrl('calendar_default'));
    }
}
