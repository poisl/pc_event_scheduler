<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_participant',
		'label' => 'accept',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',

		),
		'searchFields' => 'accept,participant_user_id,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('pc_event_scheduler') . 'Resources/Public/Icons/tx_pceventscheduler_domain_model_participant.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, accept, participant_user_id',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, accept, participant_user_id, '),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_pceventscheduler_domain_model_participant',
				'foreign_table_where' => 'AND tx_pceventscheduler_domain_model_participant.pid=###CURRENT_PID### AND tx_pceventscheduler_domain_model_participant.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),

		'accept' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_participant.accept',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'participant_user_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_participant.participant_user_id',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		
		'event' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'tstamp' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_participant.tstamp',
			'config' => array(
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			),
		),
	),
);