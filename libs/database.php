<?php

namespace phpbb\messenger\libs;

use PDO;

class database extends \PDO
{
	protected $extension_manager;
	
	public function __construct(\phpbb\extension\manager $ext_manager, $dbName)
	{
		global $phpbb_root_path;
		
		$database = $phpbb_root_path . 'ext/phpbb/messenger/db/messages.db';
		parent::__construct('sqlite:'.$database);
		parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
	{
		$sth = $this->prepare($sql);
		foreach($array as $key => $value)
		{
			$sth->bindValue("$key", $value);
		}
		
		$sth->execute();
		return $sth->fetchAll($fetchMode);
	}

	public function insert($table, $data)
	{
		//ksort($data);
		
		$fieldNames = implode('`, `', array_keys($data));
		$fieldValues = ':'.implode(', :', array_keys($data));
		
		$sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
		
		foreach($data as $key => $value)
		{
			$sth->bindValue(":$key", $value);
		}
		
		return $sth->execute();
	}
	
	public function update($table, $data, $where)
	{
		ksort($data);
	
		$fieldDetails = NULL;
		foreach($data as $key=> $value) {
			$fieldDetails .= "`$key`=:$key,";
		}
		$fieldDetails = rtrim($fieldDetails, ',');
	
		$sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
	
		foreach ($data as $key => $value) {
			$sth->bindValue(":$key", $value);
		}
	
		return $sth->execute();
	}
	
	public function delete($table, $where, $limit = 1)
	{
		return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
	}
	
	protected function find($path, $prefix, $suffix)
	{
		$finder = $this->extension_manager->get_finder();
	
		return $finder
		->set_extensions(array('phpbb/messenger'))
		->prefix($prefix)
		->suffix($suffix)
		->core_path("$path/")
		->extension_directory("/$path")
		->find()
		;
	}
}