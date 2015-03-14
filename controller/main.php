<?php

namespace florinp\messenger\controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class main
{

	protected $config;
	protected $helper;
	protected $template;
	protected $user;
	protected $model;
	protected $request;
	protected $notification_manager;

	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\request\request $request ,\phpbb\user $user, \florinp\messenger\models\main_model $model, \phpbb\notification\manager $notification_manager)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->request = $request;
		$this->user = $user;
		$this->model = $model;
		$this->notification_manager = $notification_manager;
	}

	public function handle()
	{
	}

	public function index()
	{

	}

	public function publish()
	{
		/* AJAX check  */
		$http_request = $this->request->server('HTTP_X_REQUESTED_WITH');
		if(empty($http_request) && strtolower($http_request) != 'xmlhttprequest') {
			return new Response("The request is invalid", 500);
		}

		$text = $this->request->variable('text', '', true);
		$receiver_id = $this->request->variable('receiver_id', 0);
		$sender_id = $this->user->data['user_id'];


		if($receiver_id != 0 && trim($text) != '')
		{
			$text = htmlspecialchars($text);
			$text = str_replace(array("\n", "\r"), '', $text);

			$message = array(
				'sender_id' => $sender_id,
				'receiver_id' => $receiver_id,
				'text' => $text,
				'sentAt' => time()
			);

			if($id = $this->model->sendMessage($message))
			{
				$lastMessage = $this->model->getMessageById($id);
				$response = array('success' => true, 'message' => $lastMessage);
			} else {
				$response = array(
					'succes' => false,
					'error' => 'An error has been ocurred!'
				);
			}
		}

		return new JsonResponse($response, 200);
	}

	public function load()
	{
		/* AJAX check  */
		$http_request = $this->request->server('HTTP_X_REQUESTED_WITH');
		if(empty($http_request) && strtolower($http_request) != 'xmlhttprequest') {
			return new Response("The request is invalid", 500);
		}

		$friend_id = $this->request->variable('friend_id', 0);

		if($friend_id > 0) {
			$messages = $this->model->getMessages($friend_id);
			return new JsonResponse($messages, 200);
		}
		return new JsonResponse(array('success' => false, 'error' => 'The request is invalid'), 200);
	}

	public function updateMessages()
	{
		/* AJAX check  */
		$http_request = $this->request->server('HTTP_X_REQUESTED_WITH');
		if(empty($http_request) && strtolower($http_request) != 'xmlhttprequest') {
			return new Response("The request is invalid", 500);
		}

		$friend_id = $this->request->variable('friend_id', 0);
		if($friend_id > 0)
		{
			$newVal = $this->model->updateMessagesStatus($friend_id);
			return new JsonResponse(array('success' => true, 'newVal' => $newVal), 200);
		}
		return new JsonResponse(array('success' => false), 200);
	}

	public function checkForNewMessages()
	{
		/* AJAX check  */
		$http_request = $this->request->server('HTTP_X_REQUESTED_WITH');
		if(empty($http_request) && strtolower($http_request) != 'xmlhttprequest') {
			return new Response("The request is invalid", 500);
		}

		$friend_id = $this->request->variable('friend_id', 0);
		if($friend_id > 0)
		{
			$messages = $this->model->getInboxFromId($friend_id);
			return new JsonResponse(array('success' => true, 'messages' => $messages), 200);
		}
		return new JsonResponse(array('success' => false), 200);
	}

}
