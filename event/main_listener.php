<?php

namespace florinp\messenger\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{

	static public function getSubscribedEvents()
	{
		return array(
            'core.user_setup' => 'load_language_on_setup',
			'core.page_footer' => 'friends_list',
			'core.page_header' => 'check_login',
			'core.memberlist_view_profile' => 'check_friends'
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	protected $model;

	protected $friends_model;

	protected $user;

	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \florinp\messenger\models\main_model $model, \florinp\messenger\models\friends_model $friends_model, \phpbb\user $user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->model = $model;
		$this->friends_model = $friends_model;
		$this->user = $user;
	}

	public function friends_list()
	{
		$friends = $this->model->getFriends();
		$friends_online = array_filter($friends, function($friend){
			return $friend['user_status'] != 0;
		});
		$this->template->assign_var('S_COUNT_FRIENDS', count($friends_online));
		foreach($friends as $friend)
		{
			$this->template->assign_block_vars('chat_friends', array(
				'U_USERID' => $friend['user_id'],
				'U_USERNAME' => $friend['username'],
				'U_USERCOLOR' => $friend['user_colour'],
				'U_STATUS' => $friend['user_status'],
				'U_USERINBOX' => $friend['inbox']
			));
		}
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'florinp/messenger',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function check_login()
	{
		$s_enable_messenger = 0;
		if($this->user->data['user_id'] != 1)
		{
			$s_enable_messenger = 1;
		}
		$this->template->assign_var('S_ENABLE_MESSENGER', $s_enable_messenger);
	}

	public function check_friends($event)
	{
		$user_id = $event['member']['user_id'];
        $sender_id = $this->user->data['user_id'];
        $request = $this->friends_model->get_request_by_sender_id($sender_id);
		$check_friend = $this->friends_model->check_friend(array(
			'user_id' => $this->user->data['user_id'],
			'friend_id' => $user_id,
		));
		$check_request = $this->friends_model->check_request(array(
			'user_id' => $user_id,
			'sender_id' => $this->user->data['user_id']
		));
		$this->template->assign_vars(array(
			'U_USER_ID' => $user_id,
			'U_CHECK_FRIEND' => $check_friend,
			'U_CHECK_REQUEST' => $check_request,
            'U_REQUEST_ID' => $request['request_id']
		));
	}
}
