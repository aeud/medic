<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Medic\DashboardBundle\Entity\Calendar;
use Medic\DashboardBundle\Entity\UserCalendar as Link;
use Medic\DashboardBundle\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/calendar/{hash}/event/new-quick", name="event_new_quick")
	 * @Template()
	 * @Method("GET")
     */
    public function popoverAction($hash)
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
		
		$start = $query->get('start');
		$startH = $query->get('starth');
		$startM = $query->get('startm');
		$end = $query->get('end');
		$endH = $query->get('endh');
		$endM = $query->get('endm');
		$title = $query->get('title');
		$name = $query->get('name');
		$phone = $query->get('phone');
		$email = $query->get('email');
		$description = $query->get('description');
		$privacy = $query->get('privacy');
		if (!$privacy) $privacy = 'private';
		$showMe = $query->get('showme');
		if (!$showMe) $showMe = 'busy';
		$duration = $query->get('duration');
		
		$call = $query->get('call');
		
		$from = new \DateTime($start . ' ' . $startH . ':' . $startM);
		
		if ($end && $endH && $endM) {
			$to = new \DateTime($end . ' ' . $endH . ':' . $endM);
		} elseif ($duration) {
			$to = new \DateTime($start . ' ' . $startH . ':' . $startM);
			$to->modify($duration);
		}
		
		
		$event = new Event();
		$event->setStart($from);
		$event->setEnd($to);
		$event->setCalendar($calendar);
		$event->setCreatedBy($user);
		$event->setTitle($title);
		$event->setName($name);
		$event->setPhone($phone);
		$event->setEmail($email);
		$event->setDescription($description);
		$event->setPrivacy($privacy);
		$event->setShowAs($showMe);
		
		$em->persist($event);
		$em->flush();
		
		if ($call) {
			return $this->redirect($call);
		} else {
			return $this->redirect($this->generateUrl('home', array(
				'week' => $from->format('W'),
				'year' => $from->format('Y')
			)));
		}
		
    }
	
    /**
     * @Route("/calendar/{hash}/event/{hash2}/edit", name="event_edit")
	 * @Template()
	 * @Method("GET")
     */
    public function editAction($hash, $hash2)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		
		$query = $em->createQuery(
		    'SELECT e
		    FROM MedicDashboardBundle:Event e
			LEFT JOIN e.calendar c
			LEFT JOIN c.preUsers l
		    WHERE c.hash = :hash AND l.user = :user AND e.hash = :hash2 AND c.isActive = 1'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash,
			'hash2' => $hash2
		));
		$event = $query->getSingleResult();
		return array(
			'event' => $event
		);
    }
	
    /**
     * @Route("/calendar/{hash}/event/{hash2}/edit")
	 * @Method("POST")
     */
    public function updateAction($hash, $hash2)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		
		$query = $em->createQuery(
		    'SELECT e
		    FROM MedicDashboardBundle:Event e
			LEFT JOIN e.calendar c
			LEFT JOIN c.preUsers l
		    WHERE c.hash = :hash AND l.user = :user AND e.hash = :hash2 AND c.isActive = 1'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash,
			'hash2' => $hash2
		));
		$event = $query->getSingleResult();
		
		$request = $this->getRequest();
		$query = $request;
		
		$start = $query->get('start');
		$startH = $query->get('starth');
		$startM = $query->get('startm');
		$end = $query->get('end');
		$endH = $query->get('endh');
		$endM = $query->get('endm');
		$title = $query->get('title');
		$name = $query->get('name');
		$phone = $query->get('phone');
		$email = $query->get('email');
		$description = $query->get('description');
		$privacy = $query->get('privacy');
		$showMe = $query->get('showme');
		
		$from = new \DateTime($start . ' ' . $startH . ':' . $startM);
		$to = new \DateTime($end . ' ' . $endH . ':' . $endM);
		
		$event->setStart($from);
		$event->setEnd($to);
		$event->setTitle($title);
		$event->setName($name);
		$event->setPhone($phone);
		$event->setEmail($email);
		$event->setDescription($description);
		$event->setPrivacy($privacy);
		$event->setShowAs($showMe);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('home', array(
			'week' => $from->format('W'),
			'year' => $from->format('Y')
		)));
    }
	
    /**
     * @Route("/calendar/{hash}/event/{hash2}/cancel", name="event_cancel")
	 * @Method("GET")
     */
    public function cancelAction($hash, $hash2)
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		
		$query = $em->createQuery(
		    'SELECT e
		    FROM MedicDashboardBundle:Event e
			LEFT JOIN e.calendar c
			LEFT JOIN c.preUsers l
		    WHERE c.hash = :hash AND l.user = :user AND e.hash = :hash2 AND c.isActive = 1'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash,
			'hash2' => $hash2
		));
		$event = $query->getSingleResult();
		$from = $event->getStart();
		
		$em->remove($event);
		$em->flush();
		
		return $this->redirect($this->generateUrl('home', array(
			'week' => $from->format('W'),
			'year' => $from->format('Y')
		)));
    }
	
    /**
     * @Route("/calendar/{hash}/events", name="calendar_events")
	 * @Method("GET")
     */
    public function eventsAction($hash)
    {
		$request = $this->getRequest();
		$query = $request->query;
		
		$from = $query->get('from');
		$to = $query->get('to');
		
		if (preg_match('/^20[0-2][0-9]\-[0,1][0-9]\-[0-3][0-9]$/i', $from) && preg_match('/^20[0-2][0-9]\-[0,1][0-9]\-[0-3][0-9]$/i', $to)) {
			$from = new \DateTime($from);
			$to = new \DateTime($to);
		} else {
			$from = new \DateTime();
			while ($from->format('N') != '1'){
				$from->modify('-1 day');
			}
			$to = new \DateTime($from->format('Y-m-d'));
			$to->modify('+5 days');
		}
		
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$query = $em->createQuery(
		    'SELECT e.start, e.end, e.title, e.hash
		    FROM MedicDashboardBundle:Event e
			LEFT JOIN e.calendar c
			LEFT JOIN c.preUsers l
		    WHERE c.hash = :hash AND l.user = :user AND c.isActive = 1 AND e.start <= :to AND e.end >= :from'
		)->setParameters(array(
			'user' => $user,
			'hash' => $hash,
			'from' => $from,
			'to' => $to
		));
		$results = $query->getResult();
		$response = new JsonResponse();
		
		$events = array();
		foreach ($results as $event) {
			$durationSteps = ($event['end']->getTimestamp() - $event['start']->getTimestamp()) / 60 / 15;
			for ($i=0; $i < $durationSteps; $i++) { 
				if ($i > 0) {
					$event['start']->modify('+15 minutes');
				}
				if (!isset($events[$event['start']->format('Y-m-d H:i:s')])) $events[$event['start']->format('Y-m-d H:i:s')] = array();
				$events[$event['start']->format('Y-m-d H:i:s')] = array(
					'start' => $event['start']->format('Y-m-d H:i:s'),
					'title' => $event['title'],
					'hash' => $event['hash']
				);
			}
		}
		
		$response->setData(array(
			'dates' => array(
				'from' => $from,
				'to' => $to
			),
			'results' => count($events),
		    //'events' => $events,
			'events' => $events
		));
		return $response;
    }
}
