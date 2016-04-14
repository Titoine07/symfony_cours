<?php

namespace OC\PlatformBundle\Purger;
use Doctrine\ORM\EntityManager;

/**
 * @InjectParams({
 *    "em" = @Inject("doctrine.orm.entity_manager")
 * })
 */
class OCPurger
{
	/**
	 * @var Symfony\Bundle\DoctrineBundle\Registry
	 */
	protected $_em;
	
	public function __construct(EntityManager $em)
	{
	$this->_em = $em;
	}
	
	public function removeOldUpdate($days) 
	{
		
		
		
		
		
		$listAdvertApp = $this->_em
				->getRepository('OCPlatformBundle:Advert')
				->getAdvertWithApplications()
		;
		
		
	}

}