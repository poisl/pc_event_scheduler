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
 * Test case for class PoiCom\PcEventScheduler\Controller\ParticipantController.
 *
 * @author Thomas Poisl <thomas@poisl.org>
 */
class ParticipantControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \PoiCom\PcEventScheduler\Controller\ParticipantController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('PoiCom\\PcEventScheduler\\Controller\\ParticipantController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllParticipantsFromRepositoryAndAssignsThemToView()
	{

		$allParticipants = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$participantRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\ParticipantRepository', array('findAll'), array(), '', FALSE);
		$participantRepository->expects($this->once())->method('findAll')->will($this->returnValue($allParticipants));
		$this->inject($this->subject, 'participantRepository', $participantRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('participants', $allParticipants);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenParticipantToView()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('participant', $participant);

		$this->subject->showAction($participant);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenParticipantToParticipantRepository()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();

		$participantRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\ParticipantRepository', array('add'), array(), '', FALSE);
		$participantRepository->expects($this->once())->method('add')->with($participant);
		$this->inject($this->subject, 'participantRepository', $participantRepository);

		$this->subject->createAction($participant);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenParticipantToView()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('participant', $participant);

		$this->subject->editAction($participant);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenParticipantInParticipantRepository()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();

		$participantRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\ParticipantRepository', array('update'), array(), '', FALSE);
		$participantRepository->expects($this->once())->method('update')->with($participant);
		$this->inject($this->subject, 'participantRepository', $participantRepository);

		$this->subject->updateAction($participant);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenParticipantFromParticipantRepository()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();

		$participantRepository = $this->getMock('PoiCom\\PcEventScheduler\\Domain\\Repository\\ParticipantRepository', array('remove'), array(), '', FALSE);
		$participantRepository->expects($this->once())->method('remove')->with($participant);
		$this->inject($this->subject, 'participantRepository', $participantRepository);

		$this->subject->deleteAction($participant);
	}
}
