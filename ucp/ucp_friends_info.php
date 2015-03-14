<?php

namespace florinp\messenger\ucp;

class ucp_friends_info
{

  function module()
  {
    return array(
      'filename' => '\florinp\messenger\ucp\ucp_friends_module',
      'title' => 'Friends',
      'modes' => array(
        'requests' => array(
          'title' => 'Requests',
          'auth' => 'ext_florinp/messenger',
        ),
      )
    );
  }

}
