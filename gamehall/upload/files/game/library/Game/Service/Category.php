<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author lichanghua
 *
 */
class Game_Service_Category{

	/**
	 * 
	 * Enter description here ...
	 */
	public static function getAllCategory() {
		return array(self::_getDao()->count(), self::_getDao()->getAll());
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $params
	 * @param unknown_type $page
	 * @param unknown_type $limit
	 */
	public static function getList($page = 1, $limit = 10, $params = array()) {
		if ($page < 1) $page = 1; 
		$start = ($page - 1) * $limit;
		$ret = self::_getDao()->getList($start, $limit, $params, array('sort'=>'DESC'));
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function getCategory($id) {
		if (!intval($id)) return false;
		return self::_getDao()->get(intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public static function updateCategory($data, $id) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function deleteCategory($id) {
		return self::_getDao()->delete(intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function addCategory($data) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->insert($data);
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function updateCategoryTJ($id) {
		if (!$id) return false;
		return self::_getDao()->increment('hits', array('id'=>intval($id)));
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['sort'])) $tmp['sort'] = intval($data['sort']);
		if(isset($data['title'])) $tmp['title'] = $data['title'];
		if(isset($data['img'])) $tmp['img'] = $data['img'];
		if(isset($data['status'])) $tmp['status'] = intval($data['status']);
		if(isset($data['hits'])) $tmp['hits'] = intval($data['hits']);
		return $tmp;
	}
	
	/**
	 * 
	 * @return Game_Dao_Category
	 */
	private static function _getDao() {
		return Common::getDao("Game_Dao_Category");
	}
}
