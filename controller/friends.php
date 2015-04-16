<?php

namespace florinp\messenger\controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class friends {

	protected $config;
	protected $helper;
	protected $template;
	protected $user;
	protected $user_loader;
	protected $model;
	protected $request;
	protected $notification_manager;
	protected $u_action;

	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\request\request $request, \phpbb\user $user, \phpbb\user_loader $user_loader, \florinp\messenger\models\friends_model $model, \phpbb\notification\manager $notification_manager) {
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->request = $request;
		$this->user = $user;
		$this->user_loader = $user_loader;
		$this->model = $model;
		$this->notification_manager = $notification_manager;
	}

	public function friends_list()
	{
		$friends = $this->model->getFriends();
		$i = 0;
		foreach($friends as $friend)
		{
			$i = $i + 1;
			$user_loader = $this->user_loader->load_users(array(
				$friend['user_id']
			));
			$this->template->assign_block_vars('friends', array(
				'user_id' => $friend['user_id'],
				'username' => $this->user_loader->get_username($friend['user_id'], 'full'),
				'user_colour' => $friend['user_colour'],
				'user_status' => ($friend['user_status'] == 1) ? 'online' : 'offline',
				'row_count' => $i
			));
		}
		
		$this->template->assign_vars(array(
			'U_ACTION' => $this->u_action,
		));
	}

	public function requests()
	{
		$requests = $this->model->get_friends_requests();
		$i = 0;
		foreach($requests as $request)
		{
			$i = $i + 1;
			$user_loader = $this->user_loader->load_users(array(
				$request['user_id'],
				$request['sender_id']
			));
			$this->template->assign_block_vars('requests', array(
				'request_id' => $request['request_id'],
				'sender_username' => $this->user_loader->get_username($request['sender_id'], 'full'),
				'status' => ($request['status'] == 1) ? 'approved' : 'waiting for approval',
				'time' => date('d M Y H:i:s', $request['time']),
				'row_count' => $i
			));
		}

		$this->template->assign_vars(array(
			'U_ACTION' => $this->u_action,
		));
	}

	public function delete_request($requests_id)
	{
		if(is_array($requests_id))
		{
			foreach($requests_id as $id)
			{
				$this->model->delete_friend_request($id);
			}
		}
		else
		{
			$this->model->delete_friend_request($requests_id);
		}
	}

	public function approve_request($requests_id)
	{
		if(is_array($requests_id))
		{
			foreach($requests_id as $id)
			{
				$this->model->approve_friend_request($id);
				$request_data = $this->model->get_friend_request($id);
				$this->model->add_friend(array(
					'user_id' => $request_data['user_id'],
					'friend_id' => $request_data['sender_id']
				));
			}
		}
		else
		{
			$this->model->approve_friend_request($requests_id);
			$request_data = $this->model->get_friend_request($requests_id);
			$this->model->add_friend(array(
				'user_id' => $request_data['user_id'],
				'friend_id' => $request_data['sender_id']
			));
		}
	}

	public function send_request($user_id) {
		$user_id = ( int ) $user_id;
		$sender_id = $this->user->data ['user_id'];

		$insert = array (
				'user_id' => $user_id,
				'sender_id' => $sender_id
		);

		if ($request_id = $this->model->insert_friends_request ( $insert )) {

			$notification_data = array (
					'request_id' => $request_id,
					'sender_id' => $sender_id,
					'sender_username' => $this->user->data ['username'],
					'user_id' => $user_id
			);

			//print_r($notification_data); exit;

			$this->notification_manager->add_notifications ( array (
					'florinp.messenger.notification.type.friend_request'
			), $notification_data );

			return true;
		}

		return false;
	}
	
	public function remove_friend($user_id) {
		
		if(is_array($user_id))
		{
			foreach($user_id as $id)
			{
				$this->model->remove_friend($id);
			}
			return true;
		}
		else
		{
			if($user_id > 1)
			{
				$this->model->remove_friend($user_id);
				return true;
			}
		}
		return false;
	}

	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
