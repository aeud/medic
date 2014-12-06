<?php

namespace Medic\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Holiday
 *
 * @ORM\Table(name="holiday")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Holiday
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
    * @ORM\ManyToOne(targetEntity="Medic\DashboardBundle\Entity\Calendar", inversedBy="holidays")
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
     * @return Holiday
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
     * @return Holiday
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
     * Set title
     *
     * @param string $title
     * @return Holiday
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
     * Set start
     *
     * @param \DateTime $start
     * @return Holiday
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
     * @return Holiday
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
     * Set calendar
     *
     * @param \Medic\DashboardBundle\Entity\Calendar $calendar
     * @return Holiday
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
