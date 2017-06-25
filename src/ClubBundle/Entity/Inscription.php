<?php

namespace ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="ClubBundle\Repository\InscriptionRepository")
 */
class Inscription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="userId",type="integer")
     * @ORM\ManyToOne(targetEntity="User",inversedBy= "inscriptions")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $userId;

    /**
     * @var int
     * @ORM\Column(name="eventId", type="integer")
     * @ORM\ManyToOne(targetEntity="Event",inversedBy= "inscriptions")
     * @ORM\JoinColumn(name="event_id",referencedColumnName="id")
     */
     protected $eventId;

    /**
     * @var string
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;


    /**
     * @var string
     * @ORM\Column(name="nomEvent", type="string", length=255, nullable=true)
     */
    private $nomEvent;

    /**
     * @var string
     * @ORM\Column(name="nomUser", type="string", length=255, nullable=true)
     */
    private $nomUser;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Inscription
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set eventId
     *
     * @param integer $eventId
     *
     * @return Inscription
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Inscription
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set nomEvent
     *
     * @param string $nomEvent
     *
     * @return Inscription
     */
    public function setNomEvent($nomEvent)
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    /**
     * Get nomEvent
     *
     * @return string
     */
    public function getNomEvent()
    {
        return $this->nomEvent;
    }

    /**
     * Set nomUser
     *
     * @param string $nomUser
     *
     * @return Inscription
     */
    public function setNomUser($nomUser)
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    /**
     * Get nomUser
     *
     * @return string
     */
    public function getNomUser()
    {
        return $this->nomUser;
    }
}
