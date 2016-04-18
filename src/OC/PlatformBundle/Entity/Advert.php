<?php

namespace OC\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
// N'oubliez pas ce use :
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Advert
 *
 * @ORM\Table(name="oc_advert")
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert {

	/**
	 * @ORM\OneToOne(targetEntity="OC\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
	 */
	private $image;
	
	/**
	 * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\Category", cascade={"persist"})
	 */
	private $categories;
	
    /**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications; // Notez le « s », une annonce est liée à plusieurs candidatures
	
    /**
     * @ORM\OneToMany(targetEntity="OC\PlatformBundle\Entity\AdvertSkill", mappedBy="advert")
     */
    private $advertskills; 
	
	
	
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date", type="datetime")
	 */
	private $date;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", length=255)
	 */
	private $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="author", type="string", length=255)
	 */
	private $author;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	private $content;

	/**
	 * 
	 * @ORM\Column(name="published", type="boolean")
	 */
	private $published = true;
	
	/**
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $updatedAt;

	
	/**
	 * @ORM\Column(name="nb_applications", type="integer")
	 */
	private $nbApplications = 0;
	
	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	
	
	public function __construct() {
		// Par défaut, la date de l'annonce est la date d'aujourd'hui
		$this->date		= new \Datetime();
		$this->categories	= new ArrayCollection();
		$this->applications	= new ArrayCollection();
		$this->advertskills	= new ArrayCollection();
	}

	
	
	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set date
	 *
	 * @param \DateTime $date
	 * @return Advert
	 */
	public function setDate($date) {
		$this->date = $date;

		return $this;
	}

	/**
	 * Get date
	 *
	 * @return \DateTime 
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 * @return Advert
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string 
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set author
	 *
	 * @param string $author
	 * @return Advert
	 */
	public function setAuthor($author) {
		$this->author = $author;

		return $this;
	}

	/**
	 * Get author
	 *
	 * @return string 
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 * @return Advert
	 */
	public function setContent($content) {
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string 
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set published
	 *
	 * @param boolean $published
	 * @return Advert
	 */
	public function setPublished($published) {
		$this->published = $published;

		return $this;
	}

	/**
	 * Get published
	 *
	 * @return boolean 
	 */
	public function getPublished() {
		return $this->published;
	}


    /**
     * Set image
     *
     * @param \OC\PlatformBundle\Entity\Image $image
     * @return Advert
     */
    public function setImage(\OC\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \OC\PlatformBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param \OC\PlatformBundle\Entity\Category $categories
     * @return Advert
     */
    public function addCategory(\OC\PlatformBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \OC\PlatformBundle\Entity\Category $categories
     */
    public function removeCategory(\OC\PlatformBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add applications
     *
     * @param \OC\PlatformBundle\Entity\Application $applications
     * @return Advert
     */
    public function addApplication(\OC\PlatformBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;
    // On lie l'annonce à la candidature
		$applications->setAdvert($this);
	
        return $this;
    }

    /**
     * Remove applications
     *
     * @param \OC\PlatformBundle\Entity\Application $applications
     */
    public function removeApplication(\OC\PlatformBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

	/**
	 * @ORM\PreUpdate
	 */
	public function updateDate()
	{
		$this->setUpdatedAt(new \DateTime());
	}

	/**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
	
	
	public function increaseApplication() {
		$this->nbApplications++;
	}

	public function decreaseApplication() {
		$this->nbApplications--;
	}

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer 
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add advertskills
     *
     * @param \OC\PlatformBundle\Entity\AdvertSkill $advertskills
     * @return Advert
     */
    public function addAdvertskill(\OC\PlatformBundle\Entity\AdvertSkill $advertskills)
    {
        $this->advertskills[] = $advertskills;
	// Pour que l'attribut advert de l'object $advertskills soit défini, il faut absolument faire d'abord appel au setter setAdvert(),
	// car c'est le seul qui accède à cet attribut (qui est en private)
		$advertskills->setAdvert($this);

        return $this;
    }

    /**
     * Remove advertskills
     *
     * @param \OC\PlatformBundle\Entity\AdvertSkill $advertskills
     */
    public function removeAdvertskill(\OC\PlatformBundle\Entity\AdvertSkill $advertskills)
    {
        $this->advertskills->removeElement($advertskills);
    }

    /**
     * Get advertskills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertskills()
    {
        return $this->advertskills;
    }
	
}
