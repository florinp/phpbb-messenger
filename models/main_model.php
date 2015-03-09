<?php

namespace florinp\messenger\models;

class main_model
{
	
	protected $config;
	protected $helper;
	protected $phpbb_db;
	protected $user;
	protected $db;
	
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\db\driver\driver_interface $phpbb_db, \phpbb\user $user, \florinp\messenger\libs\database $db)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->phpbb_db = $phpbb_db; 
		$this->user = $user;
		$this->db = $db;
	}
	
	/**
	 * get all friends
	 * @return array the list of friends
	 */
	public function getFriends()
	{
		$sql = "SELECT * 
				FROM " . ZEBRA_TABLE ."
				LEFT JOIN " . USERS_TABLE . " ON ".USERS_TABLE.".user_id = ".ZEBRA_TABLE.".zebra_id
				WHERE ".ZEBRA_TABLE.".user_id = ". (int)$this->user->data['user_id'] ."
					AND ".ZEBRA_TABLE.".friend = 1
					AND ".ZEBRA_TABLE.".foe = 0		
			";
		$result = $this->phpbb_db->sql_query($sql);
		
		$friends = array();
		while($row = $this->phpbb_db->sql_fetchrow($result))
		{
			if(!$this->checkFriend($row['user_id'])) continue;
			$friends[] = array(
				'user_id' => $row['user_id'],
				'username' => $row['username'],
				'user_colour' => $row['user_colour'],
				'inbox' => $this->getInboxFromId($row['user_id'])
			);
		}
		$this->phpbb_db->sql_freeresult();
		
		return $friends;
	}
	
	/**
	 * check if the friend is friend the primary friend (sorry if doesn't have any logic)
	 */
	public function checkFriend($friend_id)
	{
		$sql = "
			SELECT COUNT(zebra_id) as friend_count
			FROM ". ZEBRA_TABLE ."
			WHERE user_id = ". (int)$friend_id ."
				AND zebra_id = ". (int)$this->user->data['user_id'] ."
				AND friend = 1
				AND foe = 0		
		";
		$result = $this->phpbb_db->sql_query($sql);
		$friend_count = (int)$this->phpbb_db->sql_fetchfield('friend_count');
		$this->phpbb_db->sql_freeresult($result);
		
		if($friend_count > 0) {
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
			'text' => $data['text'],
			'newMsg' => 1,
			'sentAt' => time()	
		);
		
		if($this->db->insert('messages', $insert))
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
	
	public function getMessages($friend_id)
	{
		
		$config = array(
			'language' => '\RelativeTime\Languages\English',
			'separator' => ', ',
			'suffix' => true,
			'truncate' => 1,
		);
		$relativeTime = new \RelativeTime\RelativeTime($config);
		
		// get the sent messages
		$sql = "SELECT *
				FROM `messages`
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
		
		$sent = array();
		foreach($sentMessages as $msg)
		{
			$item = array();
			$item['id'] = $msg['id'];
			$item['sender_id'] = $msg['sender_id'];
			$item['receiver_id'] = $msg['receiver_id'];
			$item['text'] = $msg['text'];
			$item['sentAt'] = $msg['sentAt'];
			$item['type'] = 'sent';
			
			$sent[] = $item; 
		}
		$inbox = array();
		foreach($getInbox as $msg)
		{
			$item = array();
			$item['id'] = $msg['id'];
			$item['sender_id'] = $msg['sender_id'];
			$item['receiver_id'] = $msg['receiver_id'];
			$item['text'] = $msg['text'];
			$item['sentAt'] = $msg['sentAt'];
			$item['type'] = 'inbox';
				
			$inbox[] = $item;
		}
		
		$unsorted_messages = array_merge($sent, $inbox);
		
		uasort($unsorted_messages, function($a, $b) {
			return ($a['sentAt'] > $b['sentAt']) ? -1 : 1;
		});
		
		$sorted_messages = array();
		foreach($unsorted_messages as $msg)
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