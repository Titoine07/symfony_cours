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
		$date = date("Y-m-d H:i:s");
		$substractDate = strtotime ( '-15 day' , strtotime ( $date ) ) ;
		$endDate = date ("Y-m-d H:i:s" , $substractDate );
		
		var_dump($endDate);
		
		$listAdverts = $this->_em->createQueryBuilder();
		
		$listAdverts->select('a')
			->from('OCPlatformBundle:Advert', 'a')
			->where( 'a.updated_at' <= ':subdate' )
			->setParameter ( 'subdate', $endDate )
			->getQuery()
			->getResult()
		;
		
		foreach ( $listAdverts as $listAdvert ) {
			$listApplications = $listAdvert->addApplication();
			
			$advertSkills = $this->_em->getRepository('PlatformBundle:AdvertSkill')->findByAdvert($listAdvert->getId());
			
			foreach ( $advertSkills as $advertSkill ) {
				$this->_em->remove($advertSkill);
			}
			
			$this->_em->remove($listApplications);
			$this->_em->remove($listAdvert);
			$this->_em->flush();
		}
	}

}