<?php

namespace florinp\messenger\migrations;

class m2_ucp_friends_module extends \phpbb\db\migration\migration
{

    public function update_data()
    {
        return array(
            array('module.add', array(
                    'ucp',
                    '',
                    'Friends',
                )
            ),
            array('module.add', array(
                    'ucp',
                    'Friends',
                    array(
                        'module_basename'   => '\florinp\messenger\ucp\ucp_friends_module',
                        'modes' => array('requests'),
                    ),
                )
            ),
        );
    }
    
    public function revert_data()
    {
        return array(
            array('module.remove', array(
                    'ucp',
                    '',
                    'Friends'
                )
            ),
            array('module.remove', array(
                    'ucp',
                    'Friends',
                    array(
                        'module_basename'   => '\florinp\messenger\ucp\ucp_friends_module',
                        'modes' => array('requests'),
                    )
                )
            )
        );
        
    }

}