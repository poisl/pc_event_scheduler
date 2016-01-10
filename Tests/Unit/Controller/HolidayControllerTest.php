<?php
namespace PoiCom\PcEventScheduler\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Thomas Poisl <thomas@poisl.org>, PoiCom
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class PoiCom\PcEventScheduler\Controller\HolidayController.
 *
 * @author Thomas Poisl <thomas@poisl.org>
 */
class HolidayControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \PoiCom\PcEventScheduler\Controller\HolidayController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('PoiCom\\PcEventScheduler\\Controller\\HolidayController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllHolidaysFromRepositoryAndAssignsThemToView()
	{

		$allHolidays = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$holidayRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\HolidayRepository', array('findAll'), array(), '', FALSE);
		$holidayRepository->expects($this->once())->method('findAll')->will($this->returnValue($allHolidays));
		$this->inject($this->subject, 'holidayRepository', $holidayRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('holidays', $allHolidays);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenHolidayToView()
	{
		$holiday = new \PoiCom\PcEventScheduler\Domain\Model\Holiday();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('holiday', $holiday);

		$this->subject->showAction($holiday);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenHolidayToHolidayRepository()
	{
		$holiday = new \PoiCom\PcEventScheduler\Domain\Model\Holiday();

		$holidayRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\HolidayRepository', array('add'), array(), '', FALSE);
		$holidayRepository->expects($this->once())->method('add')->with($holiday);
		$this->inject($this->subject, 'holidayRepository', $holidayRepository);

		$this->subject->createAction($holiday);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenHolidayToView()
	{
		$holiday = new \PoiCom\PcEventScheduler\Domain\Model\Holiday();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('holiday', $holiday);

		$this->subject->editAction($holiday);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenHolidayInHolidayRepository()
	{
		$holiday = new \PoiCom\PcEventScheduler\Domain\Model\Holiday();

		$holidayRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\HolidayRepository', array('update'), array(), '', FALSE);
		$holidayRepository->expects($this->once())->method('update')->with($holiday);
		$this->inject($this->subject, 'holidayRepository', $holidayRepository);

		$this->subject->updateAction($holiday);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenHolidayFromHolidayRepository()
	{
		$holiday = new \PoiCom\PcEventScheduler\Domain\Model\Holiday();

		$holidayRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\HolidayRepository', array('remove'), array(), '', FALSE);
		$holidayRepository->expects($this->once())->method('remove')->with($holiday);
		$this->inject($this->subject, 'holidayRepository', $holidayRepository);

		$this->subject->deleteAction($holiday);
	}
}
