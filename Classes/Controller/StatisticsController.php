<?php
namespace PoiCom\PcEventScheduler\Controller;

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
 * StatisticsController
 */
class StatisticsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * action list
     *
     * @return void
     */
    public function listAction()
    {
    	if (!$this->div->isLoggedUserInGroup($this->settings['participantGroupId'])) {
    		$this->addFlashMessage($this->div->translate('message.noParticipant', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    		$this->redirect('list');
    	}
    	
    	$oldestEventYear = $this->div->getOldestEventYear();
    	$currentYear = date("Y");
    	
    	$index = 0;
    	$statistics = array();
    	
    	for($i=$oldestEventYear;$i<=$currentYear;$i++)
    	{
    		$year = $i;

    		$statistics[$index]['year'] = $year;
    		$statistics[$index]['activeEvents'] = $this->eventRepository->findEventsOfYearByActive($year, true)->count();
    		$statistics[$index]['inactiveEvents'] = $this->eventRepository->findEventsOfYearByActive($year, false)->count();

    		$averageAccepts = $this->eventRepository->calculateAverageAcceptsPerEventByYear($year, true);
    		$statistics[$index]['averageAccepts'] = $averageAccepts["0"]["average"];
    		$statistics[$index]['minimumAccepts'] = $averageAccepts["0"]["minimum"];
    		$statistics[$index]['maximumAccepts'] = $averageAccepts["0"]["maximum"];

    		$averageCancels = $this->eventRepository->calculateAverageAcceptsPerEventByYear($year, false);
    		$statistics[$index]['averageCancels'] = $averageCancels["0"]["average"];
    		$statistics[$index]['minimumCancels'] = $averageCancels["0"]["minimum"];
    		$statistics[$index]['maximumCancels'] = $averageCancels["0"]["maximum"];
    	
    		$tops = $this->eventRepository->topParticipantsByYear($year);
    	   	for($j=0;$j<=count($tops)-1;$j++) {
    			$statistics[$index]['tops'][$j]['participantName'] = $tops[$j]["participantName"];
    			$statistics[$index]['tops'][$j]['eventsAccepted'] = $tops[$j]["eventsAccepted"];
    	   	}
    		
    		$index++;
    	}
    	$this->view->assign('statistics', $statistics);
    }
}