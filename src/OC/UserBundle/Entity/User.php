<?php

namespace OC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;


/**
 *@ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser 
{

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Advert", mappedBy="user")
     */
    private $adverts; // Notez le « s », un utilisateur est lié à plusieurs annonces


    /**
     * Add adverts
     *
     * @param \OC\PlatformBundle\Entity\Advert $adverts
     * @return User
     */
    public function addAdvert(\OC\PlatformBundle\Entity\Advert $adverts)
    {
        $this->adverts[] = $adverts;

        return $this;
    }

    /**
     * Remove adverts
     *
     * @param \OC\PlatformBundle\Entity\Advert $adverts
     */
    public function removeAdvert(\OC\PlatformBundle\Entity\Advert $adverts)
    {
        $this->adverts->removeElement($adverts);
    }

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdverts()
    {
        return $this->adverts;
    }
}
