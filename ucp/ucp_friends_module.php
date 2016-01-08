<?php

namespace florinp\messenger\ucp;

class ucp_friends_module
{

  public $u_action;

  public function main($id, $mode)
  {

	global $phpbb_container, $request, $user;

	$friends_controller = $phpbb_container->get('florinp.messenger.friends.controller');
	$friends_controller->set_page_url($this->u_action);

	$this->tpl_name = 'friends';

	switch($mode)
	{
	  case 'friends':
		
		$action = $request->variable('action', '');
		switch($action)
		{
			case 'add_friend':
				$user_id = $request->variable('user_id', 0);
				if($user_id > 0)
				{
				  if(confirm_box(true))
				  {
					$user_id = $request->variable('user_id', 0);
					$redirect_url = $request->variable('redirect_url', '');
					if($friends_controller->send_request($user_id))
					{
					  redirect($redirect_url);
					}
				  } else
				  {
					$user_id = $request->variable('user_id', 0);
					$redirect_url = $request->server('HTTP_REFERER');
					confirm_box(false, $user->lang('CONFIRM_ADD_FRIEND'), build_hidden_fields(array(
					  'user_id' => $user_id,
					  'redirect_url' => $redirect_url,
					)));
				  }
				}
			  break;
		  case 'remove_friend':
			$user_id = $request->variable('user_id', 0);
			if ($user_id > 0)
			{
				if (confirm_box(true))
				{
					$user_id = $request->variable('user_id', 0);
					$redirect_url = $request->variable('redirect_url', '');
					if ($friends_controller->remove_friend($user_id))
					{
					  redirect($redirect_url);
					}
				} else
				{
					$user_id = $request->variable('user_id', 0);
					$redirect_url = $request->server('HTTP_REFERER');
					confirm_box(false, $user->lang('CONFIRM_REMOVE_FRIEND'), build_hidden_fields(array(
					  'user_id' => $user_id,
					  'redirect_url' => $redirect_url,
					)));
				}
			}
		  break;
			default:
				if($request->is_set_post('action'))
				{
					$action = $request->variable('action', '');
					switch($action) 
					{
						case 'remove':
							if(confirm_box(true))
							{
								$user_id = $request->variable('user_id', array(0));
								$redirect_url = $request->variable('redirect_url', '');
								if($friends_controller->remove_friend($user_id))
								{
								  redirect($redirect_url);
								}
							}
							else
							{
								$user_id = $request->variable('user_id', array(0));
								$redirect_url = $request->server('HTTP_REFERER');
								confirm_box(false, $user->lang('CONFIRM_REMOVE_FRIEND'), build_hidden_fields(array(
								  'user_id' => $user_id,
								  'redirect_url' => $redirect_url,
								)));
							}
						break;
					}
				}
				$friends_controller->friends_list();
				$this->tpl_name = 'friends';
			break;
		}
	  break;

	  case 'requests':

		if($request->is_set_post('action'))
		{
		  $action = $request->variable('action', '');

		  switch($action)
		  {
			case 'delete':

			  if(confirm_box(true))
			  {
				$requests_id = $request->variable('requests_id', array(0));
				$friends_controller->delete_request($requests_id);
			  }
			  else
			  {
				$requests_id = $request->variable('requests_id', array(0));
				confirm_box(false, $user->lang('CONFIRM_REMOVE_REQUESTS'), build_hidden_fields(array(
				  'requests_id' => $requests_id,
				  'action' => $action,
				  'mode' => $mode
				)));
			  }

			break;
			case 'approve':
			  $requests_id = $request->variable('requests_id', array(0));
			  $friends_controller->approve_request($requests_id);
			break;
		  }
		}
        
		$action = $request->variable('action', '');
		switch($action)
		{
			case 'cancel_request':
				if(confirm_box(true))
			  {
				$request_id = $request->variable('request_id', 0);
				$redirect_url = $request->variable('redirect_url', '');
				if($friends_controller->delete_request($request_id))
				{
					redirect($redirect_url);
				}
			  }
			  else
			  {
				$request_id = $request->variable('request_id', 0);
				$redirect_url = $request->server('HTTP_REFERER');
				confirm_box(false, $user->lang('CONFIRM_REMOVE_REQUESTS'), build_hidden_fields(array(
				  'request_id' => $request_id,
				  'action' => $action,
				  'mode' => $mode,
				  'redirect_url' => $redirect_url,
				)));
			  }
			break;
		}

			$friends_controller->requests();
			$this->tpl_name = 'ucp_friends_requests';
		break;

	}

  }

}
