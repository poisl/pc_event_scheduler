<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "pc_event_scheduler"
 *
 * Auto generated by Extension Builder 2015-12-22
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Event Scheduler',
	'description' => 'Event scheduler can be used for planning of recurring events. Example: If you are in a small sports club, you could plan weekly games. Members will be able to join or cancel, so other members can see who participates in the game. You can plan vacation schedules, where no events will be scheduled. Last but not least you can notify users that have not accepted or canceled the event yet by email.',
	'category' => 'plugin',
	'author' => 'Thomas Poisl',
	'author_email' => 'thomas@poisl.org',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.5',
	'constraints' => array(
		'depends' => array(
			'typo3' => '7.6.0-7.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);