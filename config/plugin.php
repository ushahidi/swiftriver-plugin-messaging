<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Plugin Details for the Messaging plugin
 *
 * @package   SwiftRiver
 * @author    Ushahidi Team
 * @category  Plugins
 * @copyright (c) 2008-2012 Ushahidi Inc <http://ushahidi.com>
 */

return array(
	'messaging' => array(
		'name'         => 'Messaging',
		'description'  => "Allow users to send private messages.",
		'author'       => "Ushahidi",
		'email'        => 'team@ushahidi.com',
		'version'      => '0.1.0',
		'channel'      => FALSE,
		'dependencies' => array(
			'core' => array(
				'min' => '0.2.0',
				'max' => '10.0.0'
			)
		)
	)
);

?>
