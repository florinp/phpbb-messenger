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

    public function getFriends()
	{
		$sql = "
            SELECT u.user_id, 
                   u.username, 
                   u.username_clean, 
                   u.user_type, 
                   u.user_colour, 
                   s.session_id, 
                   s.session_time
            FROM ". $this->user_friends_table ."
            LEFT JOIN ". USERS_TABLE ." AS u ON u.user_id = ". $this->user_friends_table .".friend_id
            LEFT JOIN ". SESSIONS_TABLE ." AS s ON s.session_user_id = u.user_id
            WHERE ". $this->user_friends_table .".user_id = ". (int)$this->user->data['user_id'] ."
            GROUP BY u.user_id
        ";
		$result = $this->db->sql_query($sql);

		$friends = array();
		while($row = $this->db->sql_fetchrow($result))
		{
			$friends[] = array(
				'user_id' => $row['user_id'],
				'username' => $row['username_clean'],
				'user_colour' => $row['user_colour'],
                'user_status' => ($row['session_time'] >= (time() - ($this->config['load_online_time'] * 60))) ? 1 : 0,
			);
		}
		$this->db->sql_freeresult();

		return $friends;
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
                    AND `status` = 0
			ORDER BY `time` DESC
		";

		$result = $this->db->sql_query($sql);

		while($row = $this->db->sql_fetchrow($result))
		{
			$requests[] = $row;
		}

		return $requests;
	}

	public function get_friend_request($id)
	{
		$sql = "
			SELECT `request_id`,
					`user_id`,
					`sender_id`,
					`status`,
					`time`
			FROM ". $this->friends_request_table ."
			WHERE `request_id` = ". (int)$id ."
					## AND `status` = 0
			ORDER BY `time` DESC
			LIMIT 1
		";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);

		return $row;
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

	public function add_friend($data)
	{

		$check_friend = $this->check_friend($data);
		if($check_friend == false)
		{
			$sql = "
				INSERT INTO ". $this->user_friends_table ."
					(
						`user_id`,
						`friend_id`
					)
				VALUES
					(
						". (int)$data['user_id'] .",
						". (int)$data['friend_id'] ."
					)
			";
			if($this->db->sql_query($sql))
			{
				$aux = $data['user_id'];
				$data['user_id'] = $data['friend_id'];
				$data['friend_id'] = $aux;

				self::add_friend($data);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}

	public function check_friend($data)
	{
		$sql = "
			SELECT COUNT(*) AS `count`
			FROM ". $this->user_friends_table ."
			WHERE `user_id` = ". (int)$data['user_id'] ."
			 		AND `friend_id` = ". (int)$data['friend_id'] ."
		";
		$this->db->sql_query($sql);
		$count = $this->db->sql_fetchfield('count');
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function check_request($data)
	{
		$sql = "
			SELECT COUNT(*) AS `count`
			FROM ". $this->friends_request_table ."
			WHERE `user_id` = ". (int)$data['user_id'] ."
					AND `sender_id` = ". (int)$data['sender_id'] ."
					AND `status` = 0
			LIMIT 1
		";
		$this->db->sql_query($sql);
		$count = $this->db->sql_fetchfield('count');
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    
    public function remove_friend($user_id)
    {
        $sql = "DELETE FROM ". $this->user_friends_table ." WHERE `user_id` = ". (int)$user_id ."";
        $this->db->sql_query($sql);
        
        $sql = "DELETE FROM ". $this->user_friends_table ." WHERE `friend_id` = ". (int)$user_id ."";
        $this->db->sql_query($sql);
    }

}
