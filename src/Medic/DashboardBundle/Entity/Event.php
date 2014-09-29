<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=100)
     */
    private $privacy = 'default';

    /**
     * @var string
     *
     * @ORM\Column(name="show_as", type="string", length=20)
     */
    private $showAs = 'busy';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_canceled", type="boolean")
     */
    private $isCanceled = false;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=127, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;
	
    /**
     * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\Calendar", inversedBy="events")
     * @ORM\JoinColumn(nullable=false, name="calendar_id")
     */
    private $calendar;
	
    /**
     * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false, name="user_id")
     */
    private $createdBy;
	
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setHash(hash('crc32b', uniqid('', true)));
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
    }
	
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
		$this->setUpdatedAt(new \DateTime());
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Event
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Event
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
     * Set start
     *
     * @param \DateTime $start
     * @return Event
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Event
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
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
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set privacy
     *
     * @param string $privacy
     * @return Event
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy
     *
     * @return string 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set showAs
     *
     * @param string $showAs
     * @return Event
     */
    public function setShowAs($showAs)
    {
        $this->showAs = $showAs;

        return $this;
    }

    /**
     * Get showAs
     *
     * @return string 
     */
    public function getShowAs()
    {
        return $this->showAs;
    }

    /**
     * Set isCanceled
     *
     * @param boolean $isCanceled
     * @return Event
     */
    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }

    /**
     * Get isCanceled
     *
     * @return boolean 
     */
    public function getIsCanceled()
    {
        return $this->isCanceled;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Event
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Event
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set calendar
     *
     * @param \Medic\DashboardBundle\Entity\Calendar $calendar
     * @return Event
     */
    public function setCalendar(\Medic\DashboardBundle\Entity\Calendar $calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar
     *
     * @return \Medic\DashboardBundle\Entity\Calendar 
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Set createdBy
     *
     * @param \Medic\DashboardBundle\Entity\User $createdBy
     * @return Event
     */
    public function setCreatedBy(\Medic\DashboardBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Medic\DashboardBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
