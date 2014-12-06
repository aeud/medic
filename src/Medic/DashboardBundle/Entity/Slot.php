<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slot
 *
 * @ORM\Table(name="slot")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Slot
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
     * @var integer
     *
     * @ORM\Column(name="week_day", type="integer")
     */
    private $weekDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="hour", type="integer")
     */
    private $hour;

    /**
     * @var integer
     *
     * @ORM\Column(name="minutes", type="integer")
     */
    private $minutes;

    /**
     * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\Calendar", inversedBy="slots")
     * @ORM\JoinColumn(nullable=false, name="calendar_id")
     */
    private $calendar;


    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
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
     * @return Slot
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
     * @return Slot
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
     * Set weekDay
     *
     * @param integer $weekDay
     * @return Slot
     */
    public function setWeekDay($weekDay)
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    /**
     * Get weekDay
     *
     * @return integer
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Set hour
     *
     * @param integer $hour
     * @return Slot
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return integer
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set minutes
     *
     * @param integer $minutes
     * @return Slot
     */
    public function setMinutes($minutes)
    {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * Get minutes
     *
     * @return integer
     */
    public function getMinutes()
    {
        return $this->minutes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->slots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set calendar
     *
     * @param \Medic\DashboardBundle\Entity\Calendar $calendar
     * @return Slot
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

}
