<?php

namespace phpbb\messenger\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_footer' => 'friends_list',
			'core.page_header' => 'check_login'
		);
	}
	
	/* @var \phpbb\controller\helper */
	protected $helper;
	
	/* @var \phpbb\template\template */
	protected $template;
	
	protected $model;
	
	protected $user;
	
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\messenger\models\main_model $model, \phpbb\user $user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->model = $model;
		$this->user = $user;
	}
	
	public function friends_list()
	{
		$friends = $this->model->getFriends();
		foreach($friends as $friend)
		{
			$this->template->assign_block_vars('chat_friends', array(
				'U_USERID' => $friend['user_id'],
				'U_USERNAME' => $friend['username'],
				'U_USERCOLOR' => $friend['user_colour'],
				'U_USERINBOX' => $friend['inbox']
			));
		}
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
}