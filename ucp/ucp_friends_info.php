<?php

namespace florinp\messenger\ucp;

class ucp_friends_info
{

  function module()
  {
	return array(
	  'filename' => '\florinp\messenger\ucp\ucp_friends_module',
	  'title' => 'CHAT_BOX',
	  'modes' => array(
		'friends' => array(
		  'title' => 'FRIENDS_LIST',
		  'auth' => 'ext_florinp/messenger && acl_u_access_messenger'
		),
		'requests' => array(
		  'title' => 'FRIENDS_REQUESTS',
		  'auth' => 'ext_florinp/messenger && acl_u_access_messenger',
		),
	  )
	);
  }

}
