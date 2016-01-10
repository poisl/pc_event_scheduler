<?php
namespace PoiCom\PcEventScheduler\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Thomas Poisl <thomas@poisl.org>, PoiCom
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Event
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * start
     *
     * @var \DateTime
     * @validate NotEmpty
     */
    protected $start = null;
    
    /**
     * end
     *
     * @var \DateTime
     * @validate NotEmpty
     */
    protected $end = null;
    
    /**
     * location
     *
     * @var string
     */
    protected $location = '';
    
    /**
     * active
     *
     * @var bool
     * @validate NotEmpty
     */
    protected $active = false;
    
    /**
     * inactiveReason
     *
     * @var string
     */
    protected $inactiveReason = '';
    
    /**
     * An EventInstance can have none or multiple Participants.
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PoiCom\PcEventScheduler\Domain\Model\Participant>
     * @cascade remove
     */
    protected $participants = null;
    
    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->participants = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
     * Returns the start
     *
     * @return \DateTime $start
     */
    public function getStart()
    {
    	return $this->start;
    }
    
    /**
     * Sets the start
     *
     * @param \DateTime $start
     * @return void
     */
    public function setStart(\DateTime $start)
    {
    	$this->start = $start;
    }
    
    /**
     * Returns the end
     *
     * @return \DateTime $end
     */
    public function getEnd()
    {
    	return $this->end;
    }
    
    /**
     * Sets the end
     *
     * @param \DateTime $end
     * @return void
     */
    public function setEnd(\DateTime $end)
    {
    	$this->end = $end;
    }
    
    /**
     * Adds a Participant
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Participant $participant
     * @return void
     */
    public function addParticipant(\PoiCom\PcEventScheduler\Domain\Model\Participant $participant)
    {
        $this->participants->attach($participant);
    }
    
    /**
     * Removes a Participant
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Participant $participantToRemove The Participant to be removed
     * @return void
     */
    public function removeParticipant(\PoiCom\PcEventScheduler\Domain\Model\Participant $participantToRemove)
    {
        $this->participants->detach($participantToRemove);
    }
    
    /**
     * Returns the participants
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PoiCom\PcEventScheduler\Domain\Model\Participant> $participants
     */
    public function getParticipants()
    {
        return $this->participants;
    }
    
    /**
     * Sets the participants
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\PoiCom\PcEventScheduler\Domain\Model\Participant> $participants
     * @return void
     */
    public function setParticipants(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $participants)
    {
        $this->participants = $participants;
    }
    
    /**
     * Returns the location
     *
     * @return string location
     */
    public function getLocation()
    {
        return $this->location;
    }
    
    /**
     * Sets the location
     *
     * @param string $location
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
    
    /**
     * Returns the active
     *
     * @return bool $active
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Sets the active
     *
     * @param bool $active
     * @return void
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
    
    /**
     * Returns the boolean state of active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }
    
    /**
     * Returns the inactiveReason
     *
     * @return string $inactiveReason
     */
    public function getInactiveReason()
    {
        return $this->inactiveReason;
    }
    
    /**
     * Sets the inactiveReason
     *
     * @param string $inactiveReason
     * @return void
     */
    public function setInactiveReason($inactiveReason)
    {
        $this->inactiveReason = $inactiveReason;
    }

}