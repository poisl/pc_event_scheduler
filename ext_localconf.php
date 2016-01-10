<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \PoiCom\PcEventScheduler\Command\NotifyCommandController::class;

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'PoiCom.' . $_EXTKEY,
	'Pceventscheduler',
	array(
		'Event' => 'list, next, last, accept, cancel, activate, deactivate, location',
		'Participant' => 'list, show, new, create, edit, update, delete',
		'Holiday' => 'list, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Event' => 'list, next, last, accept, cancel, activate, deactivate, location',
		'Participant' => 'list, show, new, create, edit, update, delete',
		'Holiday' => 'list, new, create, edit, update, delete',
		
	)
);