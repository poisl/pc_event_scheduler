<?php
namespace PoiCom\PcEventScheduler\Command;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

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
 * Notify command controller notifies participants for the next event
 */
class NotifyCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
{	
     /**
	 * Misc Functions
	 *
	 * @var \PoiCom\PcEventScheduler\Utility\Div
	 * @inject
	 */
	protected $div;
	
	/**
	 * eventRepository
	 *
	 * @var \PoiCom\PcEventScheduler\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;
	
	/**
	 * participantRepository
	 *
	 * @var \PoiCom\PcEventScheduler\Domain\Repository\ParticipantRepository
	 * @inject
	 */
	protected $participantRepository = NULL;

	/**
	 * The settings.
	 * @var array
	 */
	protected $settings = array();
	
	/**
	 * configurationManager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Notifies participants for the next event
	 *
	 * @return void
	 */
	public function notifyCommand()
	{
		$this->settings = $this->configurationManager->getConfiguration(
			ConfigurationManager::CONFIGURATION_TYPE_SETTINGS
		);
		
		$eventStartTime = $this->settings['eventStartTime'];
		$participantGroupId = $this->settings['participantGroupId'];
		$eventPageUid = $this->settings['eventPageUid'];
		$offset = 0;
		 
		$eventStartDate = $this->div->nextEventDate($offset);
		$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
		
		if ($events->count() == 0) {
			$this->div->createEvent($eventStartDate);
			$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
		}
		
		if ($events->getFirst()->getActive()) {
			
			$this->initFrontend();
			
			$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
			
			$link = $cObj->typolink_URL(array('parameter' => "$eventPageUid", 'forceAbsoluteUrl' => 1));
			
			$acceptUrl = $link."?&tx_pceventscheduler_pceventscheduler%5Baction%5D=accept";
			$cancelUrl = $link."?&tx_pceventscheduler_pceventscheduler%5Baction%5D=cancel";
		
			$participantsUnknown = $this->participantRepository->findParticipantsUnknown($events->getFirst()->getUid(), $participantGroupId);
		 
			if (isset($participantsUnknown)) {
				foreach ($participantsUnknown as $participantUnknown) {
					$receiverEmail = $participantUnknown['email'];
					$receiverName = $participantUnknown['name'];
			 	
					$from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();
					$mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
						'TYPO3\\CMS\\Core\\Mail\\MailMessage');
					$mail->setFrom($from);
					$mail->setSubject($this->settings['notifyMailSubject']);
					$mail->setTo($receiverEmail);
					$mail->setBody(
						str_replace(
							array('###name###', '###eventdate###', '###eventtime###', '###eventlocation###', '###acceptlink###', '###cancellink###'), 
							array($receiverName, $events->getFirst()->getStart()->format("d.m.Y"), $eventStartTime, $events->getFirst()->getLocation(), $acceptUrl, $cancelUrl),
							$this->settings['notifyMailBody']
						), 'text/html' );
					$mail->send();
				}
			}
		}
		return true;
	}
	
	/**
	 * init frontend to render frontend links in task
	 * 
	 * @param int $rootId
	 * @param int $typeNum
	 * @return void
	 */
	protected function initFrontend($rootId = 1, $typeNum = 0) {
		if (!is_object($GLOBALS['TT'])) {
			$GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker;
			$GLOBALS['TT']->start();
		}
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController',  $GLOBALS['TYPO3_CONF_VARS'], $rootId, $typeNum);
		$GLOBALS['TSFE']->connectToDB();
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
	
		if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
			$rootline = \TYPO3\CMS\Backend\Utility\BackendUtility::BEgetRootLine($rootId);
			$host = \TYPO3\CMS\Backend\Utility\BackendUtility::firstDomainRecord($rootline);
			$_SERVER['HTTP_HOST'] = $host;
		}
	}
}