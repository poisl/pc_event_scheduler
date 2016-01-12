<?php
namespace PoiCom\PcEventScheduler\Utility;

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
 * Misc functions
 */
class Div extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	/**
	 * eventRepository
	 *
	 * @var \PoiCom\PcEventScheduler\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;

	/**
	 * holidayRepository
	 *
	 * @var \PoiCom\PcEventScheduler\Domain\Repository\HolidayRepository
	 * @inject
	 */
	protected $holidayRepository = NULL;
	
	/**
	 * participantRepository
	 *
	 * @var \PoiCom\PcEventScheduler\Domain\Repository\ParticipantRepository
	 * @inject
	 */
	protected $participantRepository = NULL;
	
	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Check if logged fe_user is in given usergroup
	 *
	 * @param int $groupId
	 * @return bool $userInsideGroup
	 */
	public function isLoggedUserInGroup($groupId)
	{
		$userInsideGroup = FALSE;
		$usergroup = $GLOBALS['TSFE']->fe_user->user['usergroup'];
		
		if (in_array($groupId,  explode(',', $usergroup))) {
			$userInsideGroup = TRUE;
		}
		
		return $userInsideGroup;
			
	}
	
	/**
	 * Return Uid of logged user
	 *
	 * @return FrontendUserId
	 */
	public function getLoggedUserId()
	{
		if (!is_array($GLOBALS['TSFE']->fe_user->user)) {
			return 0;
		}
		return $GLOBALS['TSFE']->fe_user->user['uid'];
	}
	
	/**
	 * Return logged user
	 *
	 * @return FrontendUser
	 */
	public function getLoggedUser()
	{
		return $this->getUserByUid($this->getLoggedUserId())->getFirst();
	}
	
	/**
	 * Get FE user by Uid
	 *
	 * @param int $uid fe_user UID
	 * @return FrontendUser
	 */
	public function getUserByUid($uid)
	{
		$feUserRepository = $this->objectManager->get("TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository");
		
		$query = $feUserRepository->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(false);
		$query->matching($query->equals('uid', $uid));
		
		return $query->execute();
	}
	
	/**
	 * Check if even date is inside holiday
	 *
	 * @param \DateTime $eventDate
	 * @return bool $dateInsideHoliday
	 */
	public function dateInsideHoliday($eventDate) {
		$dateInsideHoliday = FALSE;
		$holidays = $this->holidayRepository->findByDate($eventDate);
		
		if($holidays->count() > 0) {
			$dateInsideHoliday = TRUE;
		}
		
		return $dateInsideHoliday;
	}

	/**
	 * Checks how many days it is until the next event starts
	 *
	 * @return int $daysUntilNextEvent
	 */
	public function daysUntilNextEvent() {
		$eventWeekday = $this->settings['eventWeekday'];
		$today= new \DateTime(date ('Y-m-d'));
		$nextEventDate = new \DateTime(date ('Y-m-d', strtotime("this ".$eventWeekday)));
	 	
		$diff = $today->diff($nextEventDate);
	 
		return $diff->days;
	}
	
	/**
	 * Get next event date
	 *
	 * @param int $offset
	 * @return \DateTime $nextEventDate
	 */
	 public function nextEventDate($offset) {
		$eventStartTime = $this->settings['eventStartTime'];
	 	$repeatEventInterval = $this->settings['repeatEventInterval'];
	 	$daysUntilNextEvent = $this->daysUntilNextEvent();
	 	
		$nextEventDate= new \DateTime(date("Y-m-d H:i:s",mktime(substr($eventStartTime,0,2), substr($eventStartTime,3,2), '00', date("m"), date("d")+$daysUntilNextEvent+$offset*$repeatEventInterval*7, date("Y"))));
		
		return $nextEventDate;
	 }
	 
	 /**
	  * Create event
	  *
	  * @param \DateTime $eventStartDate
	  * @return void
	  */
	 public function createEvent($eventStartDate) {
	 	$eventEndTime = $this->settings['eventEndTime'];
	 	$eventLocation = $this->settings['defaultLocation'];
	 	$eventEndDate = clone $eventStartDate;
	 	$eventEndDate->setTime(substr($eventEndTime,0,2), substr($eventEndTime,3,2), '00');

	 	$event = New \PoiCom\PcEventScheduler\Domain\Model\Event;
	 	
	 	if ($this->dateInsideHoliday($eventStartDate)) {
	 		$event->setActive(FALSE);
	 		$event->setInactiveReason($this->holidayRepository->findByDate($eventStartDate)->getFirst()->getDescription());
	 	}
	 	if (!$this->dateInsideHoliday($eventStartDate)) {
	 		$event->setActive(TRUE);
	 	}
	 	
	 	$event->setStart($eventStartDate);
	 	$event->setEnd($eventEndDate);
	 	$event->setLocation($eventLocation);
		
	 	$this->eventRepository->add($event);
	 	$this->persistenceManager->persistAll();
	 }	 
}