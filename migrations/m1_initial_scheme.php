<?php

namespace florinp\messenger\migrations;

class m1_initial_scheme extends \phpbb\db\migration\migration
{

	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'messenger_friends_request' => array(
					'COLUMNS' => array(
						'request_id' => array('UINT', null, 'auto_increment', 0),
						'user_id' => array('UINT', 0),
						'sender_id' => array('UINT', 0),
						'status' => array('UINT:1', 0),
						'time' => array('TIMESTAMP', 0)
					),
					'PRIMARY_KEY' => 'request_id'
				),
				$this->table_prefix . 'messenger_user_friends' => array(
					'COLUMNS' => array(
						'user_id' => array('UINT', 0),
						'friend_id' => array('UINT', 0)
					)
				)
			)
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'messenger_friends_request',
				$this->table_prefix . 'messenger_user_friends'
			)
		);
	}
}
