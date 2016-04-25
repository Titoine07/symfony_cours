<?php

namespace OC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OCUserBundle extends Bundle 
{

	public function getParent() 
	{
		//On lui fait hériter du bundle FOSUserBundle
		return 'FOSUserBundle';
	}

}
