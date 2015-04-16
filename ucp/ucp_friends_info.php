<?php

namespace florinp\messenger\ucp;

class ucp_friends_info
{

  function module()
  {
    return array(
      'filename' => '\florinp\messenger\ucp\ucp_friends_module',
      'title' => 'Messenger Friends',
      'modes' => array(
        'friends' => array(
          'title' => 'Friends list',
          'auth' => 'ext_florinp/messenger && acl_u_access_messenger'
        ),
        'requests' => array(
          'title' => 'Requests',
          'auth' => 'ext_florinp/messenger && acl_u_access_messenger',
        ),
        'add_friend' => array(
          'title' => 'Add friend',
          'auth' => 'ext_florinp/messenger && acl_u_access_messenger',
        ),
        'remove_friend' => array(
            'title' => 'Remove friend',
            'auth' => 'ext_florinp/messenger && acl_u_access_messenger'
        )
      )
    );
  }

}
