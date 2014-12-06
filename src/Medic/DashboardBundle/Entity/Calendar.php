<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calendar
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Calendar
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
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

	/**
     * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\UserCalendar", mappedBy="calendar")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $preUsers;

	/**
     * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\Event", mappedBy="calendar")
	 * @ORM\OrderBy({"start" = "ASC"})
     */
    private $events;

	/**
     * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\Slot", mappedBy="calendar")
	 * @ORM\OrderBy({"weekDay" = "ASC", "hour"="ASC", "minutes"="ASC"})
     */
    private $slots;

    /**
    * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\Holiday", mappedBy="calendar")
    * @ORM\OrderBy({"start" = "ASC"})
    */
    private $holidays;

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
     * @return Calendar
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
     * @return Calendar
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
     * Set hash
     *
     * @param string $hash
     * @return Calendar
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Calendar
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Calendar
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
     * Add preUsers
     *
     * @param \Medic\DashboardBundle\Entity\UserCalendar $preUsers
     * @return Calendar
     */
    public function addPreUser(\Medic\DashboardBundle\Entity\UserCalendar $preUsers)
    {
        $this->preUsers[] = $preUsers;

        return $this;
    }

    /**
     * Remove preUsers
     *
     * @param \Medic\DashboardBundle\Entity\UserCalendar $preUsers
     */
    public function removePreUser(\Medic\DashboardBundle\Entity\UserCalendar $preUsers)
    {
        $this->preUsers->removeElement($preUsers);
    }

    /**
     * Get preUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreUsers()
    {
        return $this->preUsers;
    }

    /**
     * Add events
     *
     * @param \Medic\DashboardBundle\Entity\Event $events
     * @return Calendar
     */
    public function addEvent(\Medic\DashboardBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Medic\DashboardBundle\Entity\Event $events
     */
    public function removeEvent(\Medic\DashboardBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add slots
     *
     * @param \Medic\DashboardBundle\Entity\Slot $slots
     * @return Calendar
     */
    public function addSlot(\Medic\DashboardBundle\Entity\Slot $slots)
    {
        $this->slots[] = $slots;

        return $this;
    }

    /**
     * Remove slots
     *
     * @param \Medic\DashboardBundle\Entity\Slot $slots
     */
    public function removeSlot(\Medic\DashboardBundle\Entity\Slot $slots)
    {
        $this->slots->removeElement($slots);
    }

    /**
     * Get slots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSlots()
    {
        return $this->slots;
    }

    /**
     * Add holidays
     *
     * @param \Medic\DashboardBundle\Entity\Holiday $holidays
     * @return Calendar
     */
    public function addHoliday(\Medic\DashboardBundle\Entity\Holiday $holidays)
    {
        $this->holidays[] = $holidays;

        return $this;
    }

    /**
     * Remove holidays
     *
     * @param \Medic\DashboardBundle\Entity\Holiday $holidays
     */
    public function removeHoliday(\Medic\DashboardBundle\Entity\Holiday $holidays)
    {
        $this->holidays->removeElement($holidays);
    }

    /**
     * Get holidays
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHolidays()
    {
        return $this->holidays;
    }
}
