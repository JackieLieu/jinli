<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author ljp
 *
 */
class Common_Service_Base {
	/**
	 * beginTransaction
	 * @return boolean
	 */
	public static function beginTransaction() {
		try{
			return Db_Adapter_Pdo::getPDO()->beginTransaction();
		}catch(Exception $e){
			if(stripos($e->getMessage(),"active transaction")){
				return false;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * rollback
	 * @return boolean
	 */
	public static function rollBack() {
		return Db_Adapter_Pdo::getPDO()->rollBack();
	}
	
	/**
	 * commit
	 * @return boolean
	 */
	public static function commit() {
		return Db_Adapter_Pdo::getPDO()->commit();
	}	
}