<?php

namespace Kevin\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Kevin\PlatformBundle\Validator\Antiflood;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="Kevin\PlatformBundle\Repository\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une annonce existe déjà avec ce titre.")
 */
class Advert
{
    const NB_ADVERTS_PER_PAGE = 10;
/**
     * @ORM\ManyToMany(targetEntity="Kevin\PlatformBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToOne(targetEntity="Kevin\PlatformBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Kevin\PlatformBundle\Entity\Application", mappedBy="advert")
     */
    private $applications;

    /**
     * @ORM\OneToMany(targetEntity="Kevin\PlatformBundle\Entity\AdvertSkill", mappedBy="advert")
     */
    private $advertSkills;

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
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     * @Antiflood()
     */
    private $content;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="nbApplications", type="integer")
     */
    private $nbApplications = 0;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \Kevin\PlatformBundle\Entity\Image $image
     *
     * @return Advert
     */
    public function setImage(\Kevin\PlatformBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Kevin\PlatformBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date         = new \Datetime();
        $this->categories   = new ArrayCollection();
        $this->applications = new ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \Kevin\PlatformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(\Kevin\PlatformBundle\Entity\Category $category)
    {
        if(!$this->categories->contains($category)){
            $this->categories[] = $category;
        }

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Kevin\PlatformBundle\Entity\Category $category
     */
    public function removeCategory(\Kevin\PlatformBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
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
     * Add application
     *
     * @param \Kevin\PlatformBundle\Entity\Application $application
     *
     * @return Advert
     */
    public function addApplication(\Kevin\PlatformBundle\Entity\Application $application)
    {
        $this->applications[] = $application;

        $application->setAdvert($this);

        return $this;
    }

    /**
     * Remove application
     *
     * @param \Kevin\PlatformBundle\Entity\Application $application
     */
    public function removeApplication(\Kevin\PlatformBundle\Entity\Application $application)
    {
        $this->applications->removeElement($application);
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
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

    /**
     * @ORM\PreUpdate
     */
   public function updateDate()
   {
        $this->setUpdatedAt(new \DateTime());
   }

    public function increaseApplication()
    {
        $this->nbApplications++;
    }

    public function decreaseApplication()
    {
        $this->nbApplications--;
    }

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     *
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
     *
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
     * Add advertSkill
     *
     * @param \Kevin\PlatformBundle\Entity\Application $advertSkill
     *
     * @return Advert
     */
    public function addAdvertSkill(\Kevin\PlatformBundle\Entity\Application $advertSkill)
    {
        $this->advertSkills[] = $advertSkill;

        $advertSkill->setAdvert($advertSkill);

        return $this;
    }

    /**
     * Remove advertSkill
     *
     * @param \Kevin\PlatformBundle\Entity\Application $advertSkill
     */
    public function removeAdvertSkill(\Kevin\PlatformBundle\Entity\Application $advertSkill)
    {
        $this->advertSkills->removeElement($advertSkill);
    }

    /**
     * Get advertSkills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvertSkills()
    {
        return $this->advertSkills;
    }
}