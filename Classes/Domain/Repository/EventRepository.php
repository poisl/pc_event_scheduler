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
 * The repository for EventInstances
 */
class EventRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );
    
    /**
     * Find oldest Event
     *
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findOldestEvent(){
    	$query = $this->createQuery();
    	$result = $query->setOrderings(array('start' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))->execute();
    	return $result;
    }
    
    /**
     * Find events of the given year that are either active or inactive
     *
     * @param int $year
     * @param bool $active
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findEventsOfYearByActive($year, $active){
    	$currentYear = date("Y");
    	
    	$lastDayInYear = new \DateTime();
    	$lastDayInYear -> setDate($year, 12, 31);
    	$lastDayInYear -> setTime(23, 59, 59);
    	
    	if ($currentYear == $year) {
    		$lastDayInYear -> setDate(date("Y"), date("m"), date("d"));
    	}
    	
    	$firstDayInYear = new \DateTime();
    	$firstDayInYear -> setDate($year, 1, 1);
    	$firstDayInYear -> setTime(0, 0, 0);
    	
    	$query = $this->createQuery();
    	$query = $query->matching($query->logicalAnd(array(
				$query->lessThanOrEqual('start', $lastDayInYear->format("Y-m-d H:i:s")),
				$query->greaterThanOrEqual('start', $firstDayInYear->format("Y-m-d H:i:s")),
    			$query->equals('active', $active)
    		)));
		$result = $query->setOrderings(array('start' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))->execute();
    	return $result;
    }
    
    /**
     * Average of accepts or cancels per active event for a given year
     *
     * @param int $year
     * @param bool $accept
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function calculateAverageAcceptsPerEventByYear($year, $accept){
       	$currentYear = date("Y");
    	
    	$lastDayInYear = new \DateTime();
    	$lastDayInYear -> setDate($year, 12, 31);
    	$lastDayInYear -> setTime(23, 59, 59);
    	
    	if ($currentYear == $year) {
    		$lastDayInYear -> setDate(date("Y"), date("m"), date("d"));
    	}
    	
    	$last = $lastDayInYear->format('Y-m-d H:i:s');
    	$firstDayInYear = new \DateTime();
    	$firstDayInYear -> setDate($year, 1, 1);
    	$firstDayInYear -> setTime(0, 0, 0);
    	$first = $firstDayInYear->format('Y-m-d H:i:s');
    	
    	$query = $this->createQuery();
    	$query->statement("
    			SELECT
    				CAST(AVG(accepted) as decimal(2,1)) as average,
    				MIN(accepted) as minimum, 
    				MAX(accepted) as maximum
    			FROM (
    				SELECT count(s.participant_user_id) AS accepted, s.event
    				FROM tx_pceventscheduler_domain_model_participant s, tx_pceventscheduler_domain_model_event e
    				WHERE e.active=1
    				AND e.start >= '$first'
    				AND e.start <= '$last'
    			    AND s.event=e.uid
    				AND s.accept='$accept' GROUP BY s.event
    			)
    			AS z
    			");
    	return $query->execute(TRUE);
    }
    
    /**
     * Top 3 participants of a given year
     *
     * @param int $year
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function topParticipantsByYear($year){
        $currentYear = date("Y");
    	
    	$lastDayInYear = new \DateTime();
    	$lastDayInYear -> setDate($year, 12, 31);
    	$lastDayInYear -> setTime(23, 59, 59);
    	
    	if ($currentYear == $year) {
    		$lastDayInYear -> setDate(date("Y"), date("m"), date("d"));
    	}
    	
    	$last = $lastDayInYear->format('Y-m-d H:i:s');
    	$firstDayInYear = new \DateTime();
    	$firstDayInYear -> setDate($year, 1, 1);
    	$firstDayInYear -> setTime(0, 0, 0);
    	$first = $firstDayInYear->format('Y-m-d H:i:s');
    	 
    	$query = $this->createQuery();
    	$query->statement("
				SELECT name AS participantName, count(s.event) AS eventsAccepted
    				FROM tx_pceventscheduler_domain_model_participant s, tx_pceventscheduler_domain_model_event e, fe_users u
    			WHERE e.start >= '$first'
    				AND e.start <= '$last'
    				AND s.event=e.uid
    				AND s.accept = '1'
    				AND e.active = '1'
    				AND s.participant_user_id = u.uid
    			GROUP BY s.participant_user_id ORDER BY eventsAccepted
    			DESC LIMIT 3
    			");
    	return $query->execute(TRUE);
    }
}