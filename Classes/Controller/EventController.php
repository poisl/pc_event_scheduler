<?php
namespace PoiCom\PcEventScheduler\Controller;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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
     * Misc Functions
     *
     * @var \PoiCom\PcEventScheduler\Utility\Div
     * @inject
     */
    protected $div;
    
    /**
     * persistenceManager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}
    	$this->view->assign('offset', $offset);

    	$eventStartDate = $this->div->nextEventDate($offset);
    	
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	
    	if ($events->count() == 0) {
    		$this->div->createEvent($eventStartDate);
    		$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	}
    	
    	$participantsAccepted = $this->participantRepository->findParticipantsAccepted($events->getFirst()->getUid());
    	$participantsCanceled = $this->participantRepository->findParticipantsCanceled($events->getFirst()->getUid());
    	$participantsUnknown = $this->participantRepository->findParticipantsUnknown($events->getFirst()->getUid(), $this->settings['participantGroupId']);
    	
    	$this->view->assign('participantsAccepted', $participantsAccepted);
    	$this->view->assign('participantsCanceled', $participantsCanceled);
    	$this->view->assign('participantsUnknown', $participantsUnknown);
        $this->view->assign('events', $events);
    }
    
    /**
     * accept action
     *
     * @return void
     */
    public function acceptAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}
    	
    	$eventStartDate = $this->div->nextEventDate($offset);
    	 
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	 
    	if ($events->count() == 0) {
    		$this->div->createEvent($eventStartDate);
    		$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	}
    	
    	$eventParticipant = $this->participantRepository->findParticipant($events->getFirst()->getUid(), $this->div->getLoggedUserId());
    	
    	if ($eventParticipant->count() == 0) {   	
	    	$participant = New \PoiCom\PcEventScheduler\Domain\Model\Participant;
    	
	    	$participant->setParticipantUserId($this->div->getLoggedUser());
    		$participant->setAccept(TRUE);

    		$events->getFirst()->addParticipant($participant);
    		
    		$this->eventRepository->update($events->getFirst());
    	} else {
    		if (!$eventParticipant->getFirst()->getAccept()) {
    			$eventParticipant->getFirst()->setAccept(TRUE);
    			$this->eventRepository->update($events->getFirst());
    		}
    	}
    	$this->addFlashMessage(LocalizationUtility::translate('message.acceptedEvent', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }

    /**
     * cancel action
     *
     * @return void
     */
    public function cancelAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}
    	 
    	$eventStartDate = $this->div->nextEventDate($offset);
    
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    
    	if ($events->count() == 0) {
    		$this->div->createEvent($eventStartDate);
    		$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	}
    	 
    	$eventParticipant = $this->participantRepository->findParticipant($events->getFirst()->getUid(), $this->div->getLoggedUserId());
    	 
    	if ($eventParticipant->count() == 0) {
    		$participant = New \PoiCom\PcEventScheduler\Domain\Model\Participant;
    		 
    		$participant->setParticipantUserId($this->div->getLoggedUser());
    		$participant->setAccept(FALSE);
    		 
    		$events->getFirst()->addParticipant($participant);
    		$this->eventRepository->update($events->getFirst());
    	} else {
    		if ($eventParticipant->getFirst()->getAccept()) {
    			$eventParticipant->getFirst()->setAccept(FALSE);
    			$this->eventRepository->update($events->getFirst());
    		}
    	}
    	$this->addFlashMessage(LocalizationUtility::translate('message.canceledEvent', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
    
    /**
     * next action
     *
     * @return void
     */
    public function nextAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    		$offset++;
    	} else {
    		$offset = 1;
    	}
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
    
    /**
     * last action
     *
     * @return void
     */
    public function lastAction()
    {
        if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    		$offset--;
    	} else {
    		$offset = -1;
    	}
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
    
    /**
     * action activate
     *
     * @return void
     */
    public function activateAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}

    	$eventStartDate = $this->div->nextEventDate($offset);
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	$events->getFirst()->setActive(TRUE);
    	$events->getFirst()->setInactiveReason('');
    	$this->eventRepository->update($events->getFirst());
    	
    	$this->addFlashMessage(LocalizationUtility::translate('message.activateEvent', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
     	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
    
    /**
     * action deactivate
     *
     * @return void
     */
    public function deactivateAction()
    {
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}
    
    	$eventStartDate = $this->div->nextEventDate($offset);
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	$events->getFirst()->setActive(FALSE);
    	$events->getFirst()->setInactiveReason(LocalizationUtility::translate('inactiveReason.adminCanceled', $this->extensionName));
    	$this->eventRepository->update($events->getFirst());
    	 
    	$this->addFlashMessage(LocalizationUtility::translate('message.deactivateEvent', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
    
    /**
     * action location
     *
     * @return void
     */
    public function locationAction()
    {
    	$location = $this->request->getArgument('location');
    	if ($this->request->hasArgument('offset')) {
    		$offset = $this->request->getArgument('offset');
    	} else {
    		$offset = 0;
    	}
    
    	$eventStartDate = $this->div->nextEventDate($offset);
    	$events = $this->eventRepository->findByStart($eventStartDate->format("Y-m-d H:i:s"));
    	$events->getFirst()->setLocation($location);
    	$this->eventRepository->update($events->getFirst());
    
    	$this->addFlashMessage(LocalizationUtility::translate('message.setLocation', $this->extensionName).": $location", '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
    	$this->redirect('list', Null, Null, array('offset'=>$offset));
    }
}