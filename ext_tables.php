<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'PoiCom.' . $_EXTKEY,
	'Pceventscheduler',
	'Event Scheduler'
);

/**
 * Flexform
 */
$pluginSignature = str_replace('_', '', $_EXTKEY) . '_pceventscheduler';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
		$pluginSignature,
		'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/FlexFormPi1.xml'
		);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Event Scheduler');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pceventscheduler_domain_model_event', 'EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pceventscheduler_domain_model_event');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pceventscheduler_domain_model_participant', 'EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pceventscheduler_domain_model_participant');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_pceventscheduler_domain_model_holiday', 'EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pceventscheduler_domain_model_holiday');
