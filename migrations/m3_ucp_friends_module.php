<?php

namespace florinp\messenger\migrations;

class m3_ucp_friends_module extends \phpbb\db\migration\migration
{

    public function update_data()
    {
        return array(
            array('module.add', array(
                    'ucp',
                    '',
                    'Messenger Friends',
                )
            ),
            array('module.add', array(
                    'ucp',
                    'Messenger Friends',
                    array(
                        'module_basename'   => '\florinp\messenger\ucp\ucp_friends_module',
                        'module_class' => 'ucp_friends_module',
                        'modes' => array('friends','requests'),
                    ),
                )
            ),
            array('module.add', array(
                    'ucp',
                    'Messenger Friends',
                    array(
                        'module_basename' => '\florinp\messenger\ucp\ucp_friends_module',
                        'module_class' => 'ucp_friends_module',
                        'module_mode' => 'add_friend',
                        'module_display' => 0,
                        'module_enabled' => 1,
                        'module_auth' => 'ext_florinp/messenger && acl_u_access_messenger'
                    )
                )
            ),
        );
    }

    public function revert_data()
    {
        return array(
            array('module.remove', array(
                    'ucp',
                    'Messenger Friends',
                    array(
                        'module_basename' => '\florinp\messenger\ucp\ucp_friends_module',
                        'module_class' => 'ucp_friends_module',
                        'module_mode' => 'add_friend',
                        'module_display' => 0,
                        'module_enabled' => 1,
                        'module_auth' => 'ext_florinp/messenger && acl_u_access_messenger'
                    )
                )
            ),
            array('module.remove', array(
                    'ucp',
                    'Messenger Friends',
                    array(
                        'module_basename'   => '\florinp\messenger\ucp\ucp_friends_module',
                        'module_class' => 'ucp_friends_module',
                        'modes' => array('friends','requests'),
                    )
                )
            ),
            array('module.remove', array(
                    'ucp',
                    '',
                    'Messenger Friends'
                )
            ),
        );

    }

}
