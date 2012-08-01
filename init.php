<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Init for the Messaging plugin
 *
 * @package   SwiftRiver
 * @author    Ushahidi Team
 * @category  Plugins
 * @copyright (c) 2008-2012 Ushahidi Inc <http://ushahidi.com>
 */

/**
 * Routes
 */
Route::set('messages', '<account>/messages(/<action>(/<id>))',
	array(
		'action' => '(inbox|outbox|create|lookup)',
		'id'     => '\d+'
	))
	->defaults(array(
		'controller' => 'messages',
		'action'     => 'inbox'
	));

/**
 * Injections
 */
Swiftriver_Event::add('swiftriver.dashboard.nav', function() {

	$nav = & Swiftriver_Event::$data;

	$unread = Model_Message::count_unread(Auth::instance()->get_user()->id);
	$unread = ($unread > 0) ? ' ('.$unread.')' : '';
	$nav[] = array(
		'id' => 'messages-navigation-link',
		'url' => '/messages',
		'label' => __('Messages').$unread
	);
});

Swiftriver_Event::add('swiftriver.user.notification.count', function() {

	$count = & Swiftriver_Event::$data;

	$count += Model_Message::count_unread(Auth::instance()->get_user()->id);
});

/**
 * Media
 */
Swiftriver_Event::add('swiftriver.template.head.css', function() {
	if (Request::current()->controller() == 'messages' OR Request::current()->controller() == 'user')
	{
		echo Html::style("plugins/messaging/media/css/plugin-messages.css");
	}
});

Swiftriver_Event::add('swiftriver.template.head.js', function() {
	if (Request::current()->controller() == 'messages' OR Request::current()->controller() == 'user')
	{
		$link_create = route::url('messages', array(
			'account' => Auth::instance()->get_user()->account->account_path,
			'action' => 'create'
		));
		$link_lookup = route::url('messages', array(
			'account' => Auth::instance()->get_user()->account->account_path,
			'action' => 'lookup'
		));
		echo Html::script("plugins/messaging/media/js/plugin-messages.js");
		echo View::factory("template/plugin-messages-create")
			->bind('link_create', $link_create)
			->bind('link_lookup', $link_lookup);
	}
	if (Request::current()->controller() == 'user' AND Request::current()->param('account') != Auth::instance()->get_user()->account->account_path)
	{
		echo Html::script("plugins/messaging/media/js/plugin-messages-inject.php?r=".Request::current()->param('account'));
	}
});
