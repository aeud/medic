<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserCalendar
 *
 * @ORM\Table(name="user_has_calendar")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity({"user", "calendar"})
 */
class UserCalendar
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
     * @ORM\Column(name="role", type="string", length=20)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="last_viewed", type="boolean")
     */
    private $lastViewed;
	
    /**
     * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\User", inversedBy="preCalendars")
     * @ORM\JoinColumn(nullable=false, name="user_id")
     */
    private $user;
	
    /**
     * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\Calendar", inversedBy="preUsers")
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
     * @return UserCalendar
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
     * @return UserCalendar
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
     * Set role
     *
     * @param string $role
     * @return UserCalendar
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set lastViewed
     *
     * @param boolean $lastViewed
     * @return UserCalendar
     */
    public function setLastViewed($lastViewed)
    {
        $this->lastViewed = $lastViewed;

        return $this;
    }

    /**
     * Get lastViewed
     *
     * @return boolean 
     */
    public function getLastViewed()
    {
        return $this->lastViewed;
    }

    /**
     * Set user
     *
     * @param \Medic\DashboardBundle\Entity\User $user
     * @return UserCalendar
     */
    public function setUser(\Medic\DashboardBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Medic\DashboardBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set calendar
     *
     * @param \Medic\DashboardBundle\Entity\Calendar $calendar
     * @return UserCalendar
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
