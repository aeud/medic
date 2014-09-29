<?php

namespace Medic\DashboardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MedicDashboardBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
