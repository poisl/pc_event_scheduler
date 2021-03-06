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
 * The repository for Holidays
 */
class HolidayRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

    /**
     * Get holiday within date
     *
     * @param \DateTime $date
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findByDate(\DateTime $date){
    	$query = $this->createQuery();
    	$result = $query->matching($query->logicalAnd($query->lessThanOrEqual('start', $date->format("Y-m-d")), $query->greaterThanOrEqual('end', $date->format("Y-m-d"))))->execute();
    	return $result;
    }
    
    /**
     * Get holiday after given date
     *
     * @param \DateTime $date
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findAfterDate(\DateTime $date){
    	$query = $this->createQuery();
    	$result = $query->matching($query->greaterThanOrEqual('end', $date->format("Y-m-d")))->setOrderings(array('start' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))->execute();
    	return $result;
    }
}