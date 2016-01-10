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
 * Participant
 */
class Participant extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * accept
     *
     * @var bool
     */
    protected $accept = '';
    
    /**
     * User that is participating
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $participantUserId = null;
    
    /**
     * tstamp
     *
     * @var string
     */
    protected $tstamp;
    
    /**
    * Returns the tstamp
    *
    * @return string $tstamp
    */
    public function getTstamp() {
    	return $this->tstamp;
    }
    
    /**
     * Returns the accept
     *
     * @return bool accept
     */
    public function getAccept()
    {
        return $this->accept;
    }
    
    /**
     * Sets the accept
     *
     * @param string $accept
     * @return void
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
    }
    
    /**
     * Returns the participantUserId
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser participantUserId
     */
    public function getParticipantUserId()
    {
        return $this->participantUserId;
    }
    
    /**
     * Sets the participantUserId
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $participantUserId
     * @return void
     */
    public function setParticipantUserId(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $participantUserId)
    {
        $this->participantUserId = $participantUserId;
    }

}