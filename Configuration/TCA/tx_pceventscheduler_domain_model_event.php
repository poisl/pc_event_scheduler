<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event',
		'label' => 'start',
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
		'searchFields' => 'start,end,location,active,inactive_reason,participants,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('pc_event_scheduler') . 'Resources/Public/Icons/tx_pceventscheduler_domain_model_event.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, start, end, location, active, inactive_reason, participants, ',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, start, end, location, active, inactive_reason, participants, '),
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
				'foreign_table' => 'tx_pceventscheduler_domain_model_event',
				'foreign_table_where' => 'AND tx_pceventscheduler_domain_model_event.pid=###CURRENT_PID### AND tx_pceventscheduler_domain_model_event.sys_language_uid IN (-1,0)',
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

		'start' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.start',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime,required',
				'checkbox' => 0,
				'default' => '0000-00-00 00:00:00'
			),
		),
		'end' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.end',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime,required',
				'checkbox' => 0,
				'default' => '0000-00-00 00:00:00'
			),
		),
		'location' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.location',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'active' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.active',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'inactive_reason' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.inactive_reason',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'participants' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:pc_event_scheduler/Resources/Private/Language/locallang.xlf:tx_pceventscheduler_domain_model_event.participants',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_pceventscheduler_domain_model_participant',
				'foreign_field' => 'event',
				'foreign_sortby' => 'sorting',
				'maxitems' => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'useSortable' => 1,
					'showAllLocalizationLink' => 1
				),
			),

		),
		
	),
);