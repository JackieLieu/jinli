<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author tiansh
 *
 */
class Theme_Service_Rom{

	/**
	 * 
	 * Enter description here ...
	 */
	public static function getAllRom() {
		return array(self::_getDao()->count(), self::_getDao()->getAllRom());
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $params
	 * @param unknown_type $page
	 * @param unknown_type $limit
	 */
	public static function getList($page = 1, $limit = 10, $params = array()) {
		$params = self::_cookData($params);
		if ($page < 1) $page = 1; 
		$start = ($page - 1) * $limit;
		$ret = self::_getDao()->getList($start, $limit, $params, array('id'=>'DESC'));
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function getRom($id) {
		if (!intval($id)) return false;
		return self::_getDao()->get(intval($id));
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param string $name
	 */
	public static function getRomByName($name) {
		if (!$name) return false;
		return self::_getDao()->getBy(array('name'=>$name));
	}
	
		
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public static function updateRom($data, $id) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function deleteRom($id) {
		return self::_getDao()->delete(intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function addRom($data) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->insert($data);
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['name'])) $tmp['name'] = $data['name'];
		return $tmp;
	}
	
	/**
	 * 
	 * @return Theme_Dao_Rom
	 */
	private static function _getDao() {
		return Common::getDao("Theme_Dao_Rom");
	}
}