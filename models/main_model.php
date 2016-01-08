<?php

namespace florinp\messenger\models;

class main_model
{

	protected $config;
	protected $helper;
	protected $phpbb_db;
	protected $user;
	protected $db;
	protected $friends_request_table;
	protected $user_friends_table;
	protected $emojione;

	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $phpbb_db, \phpbb\user $user, \florinp\messenger\libs\database $db, \florinp\messenger\libs\emojione $emojione, $friends_request_table, $user_friends_table)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->phpbb_db = $phpbb_db;
		$this->user = $user;
		$this->db = $db;
		$this->emojione = $emojione;
		$this->friends_request_table = $friends_request_table;
		$this->user_friends_table = $user_friends_table;
	}

	/**
	 * get all friends
	 * @return array the list of friends
	 */
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
            FROM ". $this->user_friends_table."
            LEFT JOIN ". USERS_TABLE." AS u ON u.user_id = ".$this->user_friends_table.".friend_id
            LEFT JOIN ". SESSIONS_TABLE." AS s ON s.session_user_id = u.user_id
            WHERE ". $this->user_friends_table.".user_id = ".(int)$this->user->data['user_id']."
            GROUP BY u.user_id
        ";
		$result = $this->phpbb_db->sql_query($sql);

		$friends = array();
		while ($row = $this->phpbb_db->sql_fetchrow($result))
		{
			$friends[] = array(
				'user_id' => $row['user_id'],
				'username' => $row['username_clean'],
				'user_colour' => $row['user_colour'],
				'user_status' => ($row['session_time'] >= (time() - ($this->config['load_online_time'] * 60))) ? 1 : 0,
				'inbox' => $this->getInboxFromId($row['user_id'])
			);
		}
		$this->phpbb_db->sql_freeresult();

		return $friends;
	}

	/**
	 * checks if a user is friend with other user and vice-versa
	 */
	public function checkFriend($friend_id)
	{
		$sql = "
			SELECT COUNT(zebra_id) as friend_count
			FROM ". ZEBRA_TABLE."
			WHERE user_id = ". (int)$friend_id."
				AND zebra_id = ". (int)$this->user->data['user_id']."
				AND friend = 1
				AND foe = 0
		";
		$result = $this->phpbb_db->sql_query($sql);
		$friend_count = (int)$this->phpbb_db->sql_fetchfield('friend_count');
		$this->phpbb_db->sql_freeresult($result);

		if ($friend_count > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getInboxFromId($friend_id)
	{
		$sql = "SELECT *
				FROM `messages`
				WHERE `sender_id` = :sender_id
					AND `receiver_id` = :receiver_id
					AND `newMsg` = 1
		";
		$results = $this->db->select($sql, array(
			':sender_id' => (int)$friend_id,
			':receiver_id' => (int)$this->user->data['user_id']
		));

		return count($results);
	}

	public function sendMessage($data)
	{
		$insert = array(
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id'],
			'text' => $this->emojione->toImage($data['text']),
			'newMsg' => 1,
			'sentAt' => time()
		);

		if ($this->db->insert('messages', $insert))
			return $this->db->lastInsertId();
		else
			return false;
	}

	public function sendFile($data)
	{
		$insert = array(
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id'],
			'fileName' => $data['fileName'],
			'file' => $data['file'],
			'type' => $data['type'],
			'sentAt' => time()
		);

		if ($this->db->insert('files', $insert))
			return $this->db->lastInsertId();
		else
			return false;
	}

	public function getMessageById($id)
	{
		$sql = "
			SELECT *
			FROM `messages`
			WHERE `id` = :id
			LIMIT 1
		";
		$message = $this->db->select($sql, array(
			':id' => $id
		));

		return $message[0];
	}

	public function getFileById($id)
	{
		$sql = "
			SELECT *
			FROM `files`
			WHERE `id` = :id
			LIMIT 1
		";
		$file = $this->db->select($sql, array(
			':id' => $id
		));

		return $file[0];
	}

	public function getMessages($friend_id)
	{
		// get the sent messages
		$sql = "SELECT *
				FROM `messages`
				WHERE `sender_id` = :sender_id
					AND `receiver_id` = :receiver_id
				ORDER BY `sentAt` ASC
		";
		$sqlFiles = "SELECT *
			FROM `files`
			WHERE `sender_id` = :sender_id
				AND `receiver_id` = :receiver_id
			ORDER BY `sentAt` ASC
		";
		$sentMessages = $this->db->select($sql, array(
			':sender_id' => $this->user->data['user_id'],
			':receiver_id'=> $friend_id
		));
		$getInbox = $this->db->select($sql, array(
			':sender_id' => $friend_id,
			':receiver_id' => $this->user->data['user_id']
		));

		$sentFiles = $this->db->select($sqlFiles, array(
			':sender_id' => $this->user->data['user_id'],
			':receiver_id'=> $friend_id
		));
		$getFiles = $this->db->select($sqlFiles, array(
			':sender_id' => $friend_id,
			':receiver_id' => $this->user->data['user_id']
		));

		$sent = array();
		foreach ($sentMessages as $msg)
		{
			$item = array();
			$item['id'] = $msg['id'];
			$item['sender_id'] = $msg['sender_id'];
			$item['receiver_id'] = $msg['receiver_id'];
			$item['text'] = $this->emojione->toImage($msg['text']);
			$item['sentAt'] = $msg['sentAt'];
			$item['type'] = 'sent';

			$sent[] = $item;
		}
		$inbox = array();
		foreach ($getInbox as $msg)
		{
			$item = array();
			$item['id'] = $msg['id'];
			$item['sender_id'] = $msg['sender_id'];
			$item['receiver_id'] = $msg['receiver_id'];
			$item['text'] = $this->emojione->toImage($msg['text']);
			$item['sentAt'] = $msg['sentAt'];
			$item['type'] = 'inbox';

			$inbox[] = $item;
		}

		foreach ($sentFiles as $file) {
			$item = array();
			$item['id'] = 'f_'.$file['id'];
			$item['sender_id'] = $file['sender_id'];
			$item['receiver_id'] = $file['receiver_id'];
			$item['fileName'] = $file['fileName'];
			$item['file'] = $file['file'];
			$item['sentAt'] = $file['sentAt'];
			$item['type'] = 'sent';

			$sent[] = $item;
		}

		foreach ($getFiles as $file) {
			$item = array();
			$item['id'] = 'f_'.$file['id'];
			$item['sender_id'] = $file['sender_id'];
			$item['receiver_id'] = $file['receiver_id'];
			$item['fileName'] = $file['fileName'];
			$item['file'] = $file['file'];
			$item['sentAt'] = $file['sentAt'];
			$item['type'] = 'inbox';

			$inbox[] = $item;
		}

		$unsorted_messages = array_merge($sent, $inbox);

		uasort($unsorted_messages, function($a, $b) {
			return ($a['sentAt'] > $b['sentAt']) ? -1 : 1;
		});

		$sorted_messages = array();
		foreach ($unsorted_messages as $msg)
		{
			$sorted_messages[] = $msg;
		}

		return $sorted_messages;
	}

	public function updateMessagesStatus($friend_id)
	{

		$sql = "
			UPDATE `messages`
			SET `newMsg` = 0
			WHERE `newMsg` = 1
				AND `sender_id` = :sender_id
				AND `receiver_id` = :receiver_id
		";

		$sth = $this->db->prepare($sql);
		$sth->bindValue(':sender_id', $friend_id);
		$sth->bindValue(':receiver_id', $this->user->data['user_id']);
		$sth->execute();

		return $this->getInboxFromId($friend_id);
	}

}
