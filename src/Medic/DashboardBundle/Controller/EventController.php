<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Medic\DashboardBundle\Entity\Calendar;
use Medic\DashboardBundle\Entity\UserCalendar as Link;
use Medic\DashboardBundle\Entity\Event;

class EventController extends Controller
{
    /**
     * @Route("/calendar/{hash}/event/new", name="event_new")
	 * @Template()
	 * @Method("GET")
     */
    public function newAction($hash)
    {
		return array(
			'hash' => $hash
		);
    }
	
    /**
     * @Route("/calendar/{hash}/event/new")
	 * @Template()
	 * @Method("POST")
     */
    public function createAction($hash)
    {
		$em = $this->getDoctrine()->getManager();
		
		$user = $this->getUser();
		
		$query = $em->createQuery(
		    'SELECT c
		    FROM MedicDashboardBundle:Calendar c
			LEFT JOIN c.preUsers l
		    WHERE c.hash = :hash AND l.user = :user AND c.isActive = 1'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash
		));
		$calendar = $query->getSingleResult();
		
		$request = $this->getRequest();
		$query = $request;
		
		$start = new \DateTime($query->get('start'));
		
		$event = new Event();
		$event->setStart($start);
		$event->setCalendar($calendar);
		$event->setCreatedBy($user);
		
		$em->persist($event);
		$em->flush();
		
		return $this->redirect($this->generateUrl('home'));
    }
}
