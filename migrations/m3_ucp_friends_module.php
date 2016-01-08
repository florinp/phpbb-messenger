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
					'CHAT_BOX',
				)
			),
			array('module.add', array(
					'ucp',
					'CHAT_BOX',
					array(
						'module_basename'   => '\florinp\messenger\ucp\ucp_friends_module',
						'module_class' => 'ucp_friends_module',
						'modes' => array('friends','requests'),
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
					'CHAT_BOX',
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
					'CHAT_BOX'
				)
			),
		);

	}

}
