<?php

namespace Medic\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SettingsController extends Controller
{
    /**
     * @Route("/settings", name="settings")
     * @Template()
     */
    public function indexAction()
    {
		return array();
    }
}
