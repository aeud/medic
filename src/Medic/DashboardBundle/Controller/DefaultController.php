<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction()
    {
		$securityContext = $this->container->get('security.context');
		if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$request = $this->getRequest();
			$query = $request->query;
			$user = $this->getUser();
		
			$from = $query->get('from');
		
			if (preg_match('/^20[0-2][0-9]\-[0,1][0-9]\-[0-3][0-9]$/i', $from)) {
				$from = new \DateTime($from);
				while ($from->format('N') != '1'){
					$from->modify('-1 day');
				}
				$to = new \DateTime($from->format('Y-m-d'));
				$to->modify('+5 days');
			} else {
				$from = new \DateTime();
				$from = new \DateTime($from->format('Y-m-d'));
				while ($from->format('N') != '1'){
					$from->modify('-1 day');
				}
				$to = new \DateTime($from->format('Y-m-d'));
				$to->modify('+5 days');
			}
			
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
			    'SELECT e.start, e.end, e.title, e.hash
			    FROM MedicDashboardBundle:Event e
				LEFT JOIN e.calendar c
			    WHERE c = :calendar AND e.start <= :to AND e.end >= :from'
			)->setParameters(array(
				'calendar' => $calendar,
				'from' => $from,
				'to' => $to
			));
			$results = $query->getResult();
			
			$events = array();
			foreach ($results as $event) {
				$durationSteps = ($event['end']->getTimestamp() - $event['start']->getTimestamp()) / 60 / 15;
				for ($i=0; $i < $durationSteps; $i++) { 
					if ($i > 0) {
						$event['start']->modify('+15 minutes');
					}
					if (!isset($events[$event['start']->format('Y-m-d H:i:s')])) $events[$event['start']->format('Y-m-d H:i:s')] = array();
					$events[$event['start']->format('Y-m-d H:i:s')][] = array(
						'start' => $event['start']->format('Y-m-d H:i:s'),
						'title' => $event['title'],
						'hash' => $event['hash']
					);
				}
			}
			
			$date = new \DateTime($from->format('Y-m-d'));
			$days = array();
			for ($i=0; $i < 5; $i++) { 
				$days[] = $date->format('Y-m-d');
				$date->modify('+1 day');
			}
			
		    return array(
		    	'calendar' => $calendar,
				'days' => $days,
				'events' => $events,
				'from' => $from
		    );
		} else {
			return $this->render('MedicDashboardBundle:Default:test.html.twig',array(
				'name' => 'name'
			));
			//return $this->redirect($this->generateUrl('fos_user_security_login'));
		}
    }
}
