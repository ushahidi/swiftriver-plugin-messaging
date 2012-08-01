<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Controller for the Messaging plugin
 *
 * @package   SwiftRiver
 * @author    Ushahidi Team
 * @category  Plugins
 * @copyright (c) 2008-2012 Ushahidi Inc <http://ushahidi.com>
 */

class Controller_Messages extends Controller_User {

	public function before()
	{
		parent::before();

		// CHECK: Are you viewing own messages?
		if ( ! $this->owner)
		{
			$this->request->redirect($this->dashboard_url);
		}

		// Grab the message you are viewing OR zero
		$this->id = intval($this->request->param('id'));

		// Set the links the views will use
		$this->link_index = URL::site($this->account->account_path);

		$this->link_inbox = route::url('messages', array(
			'account' => $this->account->account_path,
			'action' => 'inbox'
		));

		$this->link_outbox = route::url('messages', array(
			'account' => $this->account->account_path,
			'action' => 'outbox'
		));
	}

	public function action_inbox()
	{
		// CHECK: Are we reading a specific message?
		if ($this->id)
			return $this->action_inbox_read();

		$messages = ORM::factory('message')
			->where('recipient_id', '=', $this->user->id)
			->order_by('read', 'asc')
			->order_by('timestamp', 'desc')
			->find_all()
			->as_array();

		$this->set_layout();
		$this->template->header->title = $this->user->name.' / '.__('Inbox');
		$this->template->content->active = 'Inbox';
		$this->template->content->body = View::factory('pages/messages/inbox')
			->bind('messages', $messages)
			->bind('link_inbox', $this->link_inbox);
	}

	public function action_inbox_read()
	{
		$message = ORM::factory('message', $this->id);

		// CHECK: Are you the recipient of this message?
		if ( ! $message->is_recipient())
			throw new HTTP_Exception_404();

		$message->read = 1;
		$message->save();
		$reply = TRUE;

		$this->set_layout();
		$this->template->header->title = $message->subject;
		$this->template->content->active = 'Inbox';
		$this->template->content->body = View::factory('pages/messages/read')
			->bind('message', $message)
			->bind('reply', $reply);
	}

	public function action_outbox()
	{
		// CHECK: Are we reading a specific message?
		if ($this->id)
			return $this->action_outbox_read();

		$messages = ORM::factory('message')
			->where('sender_id', '=', $this->user->id)
			->order_by('timestamp', 'desc')
			->find_all()
			->as_array();

		$this->set_layout();
		$this->template->header->title = $this->user->name.' / '.__('Outbox');
		$this->template->content->active = 'Outbox';
		$this->template->content->body = View::factory('pages/messages/outbox')
			->bind('messages', $messages)
			->bind('link_outbox', $this->link_outbox);
	}

	public function action_outbox_read()
	{
		$message = ORM::factory('message', $this->id);

		// CHECK: Are you the sender of this message?
		if ( ! $message->is_sender())
			throw new HTTP_Exception_404();

		$this->set_layout();
		$this->template->header->title = $message->subject;
		$this->template->content->active = 'Outbox';
		$this->template->content->body = View::factory('pages/messages/read')
			->bind('message', $message);
	}

	public function action_create()
	{
		if ( ! $this->request->method() == 'POST')
			return;

		$this->auto_render = FALSE;
		$this->response->headers('Content-Type', 'application/json');
		$params = json_decode($this->request->body(), TRUE);

		// CHECK: Do we have all necessary data?
		if ( ! isset($params['recipient']) OR
		     ! isset($params['subject']) OR
		     ! isset($params['body']))
		{
			$this->response->status(400);
			echo json_encode(__("Something went wrong!"));
			return;
		}

		// CHECK: Is the recipient, subject and message at least one character?
		if (strlen($params['recipient']) < 1 OR
		    strlen($params['subject']) < 1 OR
		    strlen($params['body']) < 1)
		{
			$this->response->status(400);
			echo json_encode(__("You didn't fill in one of the fields!"));
			return;
		}

		$recipient = ORM::factory('account')
			->where('account_path', '=', $params['recipient'])
			->find();

		// CHECK: Is the recipient a real user?
		if (is_null($recipient) OR $recipient->account_path != $params['recipient'])
		{
			$this->response->status(400);
			echo json_encode(__("The recipient isn't a real account!"));
			return;
		}

		// CHECK: Is the recipient you?
		if ($this->account->account_path == $recipient->account_path)
		{
			$this->response->status(400);
			echo json_encode(__("You can't send messages to yourself!"));
			return;
		}

		$last = ORM::factory('message')
			->where('sender_id', '=', $this->user->id)
			->order_by('timestamp', 'desc')
			->find();

		// CHECK: Was this message just sent?
		if ($last->recipient->account->account_path == $params['recipient'] AND
		    $last->subject == $params['subject'] AND
		    $last->message == $params['body'])
		{
			$this->response->status(400);
			echo json_encode(__("The message was already sent!"));
			return;
		}

		// CHECK: Has another message been recently sent?
		if (time() - strtotime($last->timestamp) < 30)
		{
			$this->response->status(400);
			echo json_encode(__("You're sending messages too quickly!"));
			return;
		}

		$message = ORM::factory('message');
		$message->recipient_id = $recipient->user_id;
		$message->sender_id = $this->user->id;
		$message->subject = HTML::entities($params['subject']);
		$message->message = HTML::entities($params['body']);
		$message->save();
	}
	
	public function action_lookup()
	{
		if ( ! $this->request->method() == 'GET')
			return;

		$this->auto_render = FALSE;
		$this->response->headers('Content-Type', 'application/json');
		$query = $this->request->query('q');

		if ( ! $query)
			return;

		echo json_encode(Model_User::get_like($query, array($this->user->id)));
	}

	protected function set_layout()
	{
		$this->template->content = View::factory('pages/messages/layout')
			->bind('link_index', $this->link_index);

		$this->template->content->unread = Model_Message::count_unread($this->user->id);
		$this->template->content->active = '';
		$this->template->content->nav = array(
			'Inbox' => $this->link_inbox,
			'Outbox' => $this->link_outbox
		);
	}
} // End Messages
