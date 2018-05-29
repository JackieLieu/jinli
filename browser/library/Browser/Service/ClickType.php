<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 
 * Enter desClicktypeiption here ...
 * @author tiansh
 *
 */
class Browser_Service_ClickType{
	

	/**
	 *
	 * Enter desClicktypeiption here ...
	 */
	public static function getAllClicktype() {
		return array(self::_getDao()->count(), self::_getDao()->getAll());
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	
	public static function getClicktype($id) {
		return self::_getDao()->get(intval($id));
	}
	
	/**
	 *
	 * Enter desClicktypeiption here ...
	 * @param unknown_type $params
	 * @param unknown_type $page
	 * @param unknown_type $limit
	 */
	public static function getList($page = 1, $limit = 10, $params = array()) {
		$params = self::_cookData($params);
		
		if ($page < 1) $page = 1;
		$start = ($page - 1) * $limit;
		$ret = self::_getDao()->getList($start, $limit, $params);
		$total = self::_getDao()->count($data);
		return array($total, $ret);
	}
	
	/**
	 * 
	 * @param unknown_type $parent_id
	 * @return multitype:
	 */
	public static function getListByParentId($parent_id) {	
		return self::_getDao()->getListByParentID($parent_id);
	}
	
	/**
	 * 
	 * @param unknown_type $parent_id
	 * @return multitype:
	 */
	public static function getListByParentIds($pids) {
		if (!count($pids)) return false;
		return self::_getDao()->getListByParentIds($pids);
	}
	
	/**
	 * 
	 * @param unknown_type $url
	 * @return multitype:
	 */
	public static function getDataByUrl($url) {
		return self::_getDao()->getDataByUrl($url);
	}
	
	
	/**
	 *
	 * @param unknown_type $Date
	 * @return multitype:
	 */
	public static function getParentsList() {
		return self::_getDao()->getParentList();
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function addClicktype($data) {
		if (empty($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->insert($data);
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	
	public static function updateClicktype($data, $id){
		if (empty($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $uid
	 */
	public static function deleteClicktype($id) {
		return self::_getDao()->delete(intval($id));
	}

	/**
	 *
	 * Enter desClicktypeiption here ...
	 * @param unknown_type $data
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['name'])) $tmp['name'] = $data['name'];
		if(isset($data['parent_id'])) $tmp['parent_id'] = intval($data['parent_id']);
		if(isset($data['order_id'])) $tmp['order_id'] = intval($data['order_id']);
		return $tmp;
	}
		
	/**
	 *
	 * @return Browser_Dao_Clicktype
	 */
	private static function _getDao() {
		return Common::getDao("Browser_Dao_ClickType");
	}
}
