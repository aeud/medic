<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */

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
			$user = $this->getUser();
			$now = new \DateTime();
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
			    WHERE c = :calendar AND e.end >= :now'
			)->setParameters(array(
				'calendar' => $calendar,
				'now' => $now
			))->setMaxResults(10);
			$events = $query->getResult();
	    	return array(
	    		'events' => $events
	    	);
		}

    }

    /**
     * @Route("/week/{week}/{year}", name="week", defaults={"week"=NULL,"year"=NULL, "date"=NULL}, requirements={"week"="\d+","year"="\d+"})
	 * @Route("/day/{date}", name="day", defaults={"week"=NULL,"year"=NULL, "date"=NULL})
     * @Template()
     */
    public function planningAction($week, $year, $date)
    {
		$route = $this->getRequest()->get('_route');
		$securityContext = $this->container->get('security.context');
		if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$user = $this->getUser();
			$now = new \DateTime();
			if ($route == 'week') {
				$n = 7;
				if ($year === null) {
					$year = (int) $now->format('Y');
				}
				if ($week === null) {
					$week = (int) $now->format('W');
				}
				if ($week == 0) {
					return $this->redirect($this->generateUrl('week', array(
						'week' => 52,
						'year' => $year - 1
					)));
				}
				$from = $now->setISODate($year, $week);
				$to = new \DateTime($from->format('Y-m-d'));
				$to->modify('+7 days');
			} elseif ($route == 'day') {
				$n = 1;
				$from = new \DateTime($date ? $date : $now->format('Y-m-d'));
				$to = new \DateTime($from->format('Y-m-d'));
				$to->modify('+1 days');
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

			$query = $em->createQuery(
			    'SELECT s.weekDay, s.hour, s.minutes
			    FROM MedicDashboardBundle:Slot s
				LEFT JOIN s.calendar c
			    WHERE c = :calendar'
			)->setParameters(array(
				'calendar' => $calendar
			));
			$slotsTmp = $query->getResult();
			$slots = array();
			foreach ($slotsTmp as $slot) {
				$slots[] = '' . $slot['weekDay'] . '-' . $slot['hour'] . '-' . $slot['minutes'];
			}

      $query = $em->createQuery(
      'SELECT h
      FROM MedicDashboardBundle:Holiday h
      WHERE h.calendar = :calendar AND h.start <= :to AND h.end >= :from'
      )->setParameters(array(
        'calendar' => $calendar,
        'to' => $to,
        'from' => $from
      ));
      $holidays = $query->getResult();

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
			for ($i=0; $i < $n; $i++) {
				$days[] = $date->format('Y-m-d');
				$date->modify('+1 day');
			}

		    return array(
		    	'calendar' => $calendar,
				'days' => $days,
				'events' => $events,
				'from' => $from,
				'week' => $week,
				'year' => $year,
				'slots' => $slots,
        'holidays' => $holidays
		    );
		} else {
			return $this->render('MedicDashboardBundle:Default:test.html.twig',array(
				'name' => 'name'
			));
			//return $this->redirect($this->generateUrl('fos_user_security_login'));
		}
    }
}
