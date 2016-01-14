<?php
namespace PoiCom\PcEventScheduler\Controller;

use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
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
 * HolidayController
 */
class HolidayController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * holidayRepository
     *
     * @var \PoiCom\PcEventScheduler\Domain\Repository\HolidayRepository
     * @inject
     */
    protected $holidayRepository = NULL;
    
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
    	$now = new \DateTime(date("Y-m-d"));
    	$holidays = $this->holidayRepository->findAfterDate($now);
        $this->view->assign('holidays', $holidays);
    }
    
    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        
    }
    
    /**
     * action create
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday
     * @return void
     */
    public function createAction(\PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday)
    {
		if (!$this->div->isLoggedUserInGroup($this->settings['eventAdminGroupId'])) {
			$this->addFlashMessage($this->div->translate('message.noAdmin', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
			$this->redirect('list');
		}
    	$this->addFlashMessage($this->div->translate('message.holidayCreated', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->holidayRepository->add($holiday);
        $this->redirect('list');
    }
    
    /**
     * action edit
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday
     * @ignorevalidation $holiday
     * @return void
     */
    public function editAction(\PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday)
    {
        $this->view->assign('holiday', $holiday);
    }
    
    /**
     * action update
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday
     * @return void
     */
    public function updateAction(\PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday)
    {
    	if (!$this->div->isLoggedUserInGroup($this->settings['eventAdminGroupId'])) {
    		$this->addFlashMessage($this->div->translate('message.noAdmin', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    		$this->redirect('list');
    	}
    	$this->addFlashMessage($this->div->translate('message.holidayEdited', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->holidayRepository->update($holiday);
        $this->redirect('list');
    }
    
    /**
     * action delete
     *
     * @param \PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday
     * @return void
     */
    public function deleteAction(\PoiCom\PcEventScheduler\Domain\Model\Holiday $holiday)
    {
    	if (!$this->div->isLoggedUserInGroup($this->settings['eventAdminGroupId'])) {
    		$this->addFlashMessage($this->div->translate('message.noAdmin', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    		$this->redirect('list');
    	}
    	$this->addFlashMessage($this->div->translate('message.holidayDeleted', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->holidayRepository->remove($holiday);
        $this->redirect('list');
    }
    
    /**
     * Init
     *
     * @return void
     */
    public function initializeAction()
    {
        if (isset($this->arguments['holiday'])) {
            $this->arguments['holiday']->getPropertyMappingConfiguration()->forProperty('start')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', DateTimeConverter::CONFIGURATION_DATE_FORMAT, "Y-m-d");
            $this->arguments['holiday']->getPropertyMappingConfiguration()->forProperty('end')->setTypeConverterOption('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter', DateTimeConverter::CONFIGURATION_DATE_FORMAT, "Y-m-d");
        }
    }
}