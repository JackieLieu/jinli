<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class Db_Pdo {
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $config
	 * @throws PDOException
	 */
	static public function factory($config){
		$db = NULL;
		if (!is_array($config)) {
			throw new PDOException('Db parameters must be in an array.');
		}
		/**
		 * username and password checked
		 */
		if (!$config['username'] || !$config['password']) {
			throw new PDOException("PDO connect access username or passwd.");
		}
		/**
		 * 
		 */
		$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $config['host'], $config['dbname']);
		try{
			$db = new Pdo($dsn, $config['username'], $config['password']);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}
		
		if ($config['displayError']) {
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $db;
	}
}
