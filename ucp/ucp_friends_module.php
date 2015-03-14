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
    	case 'requests';

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
                confirm_box(false, 'Are you sure you want to delete the requests?', build_hidden_fields(array(
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

    		$friends_controller->requests();
    		$this->tpl_name = 'ucp_friends_requests';
    	break;
    }

  }

}
