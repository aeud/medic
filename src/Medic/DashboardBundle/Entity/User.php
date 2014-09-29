<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;
	
	/**
     * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\UserCalendar", mappedBy="user")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $preCalendars;
	
	/**
     * @ORM\OneToMany(targetEntity="Medic\DashboardBundle\Entity\Event", mappedBy="createdBy")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $events;
	
	public function __construct()
    {
        parent::__construct();
    }
	
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setHash(hash('crc32b', uniqid('', true)));
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
     * Set hash
     *
     * @param string $hash
     * @return User
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
     * Add preCalendars
     *
     * @param \Medic\DashboardBundle\Entity\UserCalendar $preCalendars
     * @return User
     */
    public function addPreCalendar(\Medic\DashboardBundle\Entity\UserCalendar $preCalendars)
    {
        $this->preCalendars[] = $preCalendars;

        return $this;
    }

    /**
     * Remove preCalendars
     *
     * @param \Medic\DashboardBundle\Entity\UserCalendar $preCalendars
     */
    public function removePreCalendar(\Medic\DashboardBundle\Entity\UserCalendar $preCalendars)
    {
        $this->preCalendars->removeElement($preCalendars);
    }

    /**
     * Get preCalendars
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreCalendars()
    {
        return $this->preCalendars;
    }

    /**
     * Add events
     *
     * @param \Medic\DashboardBundle\Entity\Event $events
     * @return User
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
}
