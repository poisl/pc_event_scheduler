<?php
namespace PoiCom\PcEventScheduler\Domain\Repository;

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
 * The repository for Participants
 */
class ParticipantRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * Find participant for specific event
     *
     * @param int $eventUid
     * @param int $participantUserId
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findParticipant($eventUid, $participantUserId){
    	$query = $this->createQuery();
    	$result = $query->matching($query->logicalAnd($query->equals('event', $eventUid), $query->equals('participantUserId', $participantUserId)))->execute();
    	return $result;
    }

    /**
     * Find accepting participants for specific event
     *
     * @param int $eventUid
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findParticipantsAccepted($eventUid){
    	$query = $this->createQuery();
    	$result = $query->matching($query->logicalAnd($query->equals('event', $eventUid), $query->equals('accept', '1')))->execute();
    	return $result;
    }
    
    /**
     * Find canceling participants for specific event
     *
     * @param int $eventUid
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findParticipantsCanceled($eventUid){
    	$query = $this->createQuery();
    	$result = $query->matching($query->logicalAnd($query->equals('event', $eventUid), $query->equals('accept', '0')))->execute();
    	return $result;
    }
    
    /**
     * Find FE_Users with unknown participation status for a given event and usergroup
     *
     * @param int $eventUid
     * @param int $participantGroupId
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findParticipantsUnknown($eventUid, $participantGroupId)
    {
    	$feUserRepository = $this->objectManager->get("TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository");
    	$query = $feUserRepository->createQuery();
    	$query->statement("SELECT * FROM fe_users
    			WHERE
    			uid NOT IN (SELECT participant_user_id FROM tx_pceventscheduler_domain_model_participant WHERE event = $eventUid)
    			AND deleted = 0
    			AND disable = 0
    			AND FIND_IN_SET($participantGroupId, usergroup)
    			");
    	return $query->execute(TRUE);
    }
}