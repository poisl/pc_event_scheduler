<?php

namespace PoiCom\PcEventScheduler\Tests\Unit\Domain\Model;

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
 * Test case for class \PoiCom\PcEventScheduler\Domain\Model\Event.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Thomas Poisl <thomas@poisl.org>
 */
class EventTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \PoiCom\PcEventScheduler\Domain\Model\Event
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \PoiCom\PcEventScheduler\Domain\Model\Event();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getStartReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getStart()
		);
	}

	/**
	 * @test
	 */
	public function setStartForDateTimeSetsStart()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setStart($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'start',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEndReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getEnd()
		);
	}

	/**
	 * @test
	 */
	public function setEndForDateTimeSetsEnd()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setEnd($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'end',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLocationReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setLocationForIntSetsLocation()
	{	}

	/**
	 * @test
	 */
	public function getActiveReturnsInitialValueForBool()
	{
		$this->assertSame(
			FALSE,
			$this->subject->getActive()
		);
	}

	/**
	 * @test
	 */
	public function setActiveForBoolSetsActive()
	{
		$this->subject->setActive(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'active',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getInactiveReasonReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getInactiveReason()
		);
	}

	/**
	 * @test
	 */
	public function setInactiveReasonForStringSetsInactiveReason()
	{
		$this->subject->setInactiveReason('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'inactiveReason',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getParticipantsReturnsInitialValueForParticipant()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getParticipants()
		);
	}

	/**
	 * @test
	 */
	public function setParticipantsForObjectStorageContainingParticipantSetsParticipants()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();
		$objectStorageHoldingExactlyOneParticipants = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneParticipants->attach($participant);
		$this->subject->setParticipants($objectStorageHoldingExactlyOneParticipants);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneParticipants,
			'participants',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addParticipantToObjectStorageHoldingParticipants()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();
		$participantsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$participantsObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($participant));
		$this->inject($this->subject, 'participants', $participantsObjectStorageMock);

		$this->subject->addParticipant($participant);
	}

	/**
	 * @test
	 */
	public function removeParticipantFromObjectStorageHoldingParticipants()
	{
		$participant = new \PoiCom\PcEventScheduler\Domain\Model\Participant();
		$participantsObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$participantsObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($participant));
		$this->inject($this->subject, 'participants', $participantsObjectStorageMock);

		$this->subject->removeParticipant($participant);

	}
}
