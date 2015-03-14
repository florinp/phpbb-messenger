<?php

namespace florinp\messenger\models;

class friends_model {
	protected $config;
	protected $helper;
	protected $db;
	protected $user;
	protected $friends_request_table;
	protected $user_friends_table;

	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, $friends_request_table, $user_friends_table) {
		$this->config = $config;
		$this->helper = $helper;
		$this->db = $db;
		$this->user = $user;
		$this->friends_request_table = $friends_request_table;
		$this->user_friends_table = $user_friends_table;
	}

	public function get_friends_requests()
	{

		$requests = array();

		$sql = "
			SELECT `request_id`,
					`user_id`,
					`sender_id`,
					`status`,
					`time`
			FROM ". $this->friends_request_table ."
			WHERE `user_id` = ". (int)$this->user->data['user_id'] ."
					## AND `status` = 0
			ORDER BY `time` DESC
		";

		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$requests[] = $row;
		}

		return $requests;
	}

	public function insert_friends_request(array $data) {
		$sql = "
      INSERT INTO " . $this->friends_request_table . "
        (
          `user_id`,
          `sender_id`,
          `status`,
          `time`
        )
      VALUES
        (
          " . ( int ) $data ['user_id'] . ",
          " . ( int ) $data ['sender_id'] . ",
          0,
          " . time () . "
        )
    ";
		$this->db->sql_query ( $sql );

		return $this->db->sql_nextid ();
	}

	public function delete_friend_request($request_id)
	{
		$sql = "
			DELETE FROM ". $this->friends_request_table ." WHERE `request_id` = ". (int)$request_id ."
		";

		return $this->db->sql_query($sql);
	}

	public function approve_friend_request($request_id)
	{
		$sql = "
			UPDATE ". $this->friends_request_table ." SET `status` = 1 WHERE `request_id` = ". (int)$request_id ."
		";

		return $this->db->sql_query($sql);
	}

}
