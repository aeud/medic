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
			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery(
			    'SELECT c
			    FROM MedicDashboardBundle:Calendar c
				LEFT JOIN c.preUsers l
			    WHERE l.lastViewed = 1 AND l.user = :user AND c.isActive = 1'
			)->setParameters(array(
				'user' => $this->getUser()
			));

			$calendar = $query->getSingleResult();
		    return array(
		    	'calendar' => $calendar
		    );
		} else {
			return $this->redirect($this->generateUrl('fos_user_security_login'));
		}
    }
}
