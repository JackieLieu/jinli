<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author lichanghau
 *
 */
class Client_Service_ContinueLoginActivityConfig{

	/**
	 * 
	 * Enter description here ...
	 */
	public static function getAll() {
		return array(self::_getDao()->count(), self::_getDao()->getAll());
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $params
	 * @param unknown_type $page
	 * @param unknown_type $limit
	 */
	public static function getList($page = 1, $limit = 10, $params = array(), $orderBy = array('id'=>'DESC')) {
		if ($page < 1) $page = 1; 
		$start = ($page - 1) * $limit;
		$ret = self::_getDao()->getList($start, $limit, $params, $orderBy);
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function get($id) {
		if (!intval($id)) return false;
		return self::_getDao()->get(intval($id));
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function getBy($params, $orderBy = array('id'=>'DESC','end_time'=>'DESC','start_time'=>'DESC')) {
		return self::_getDao()->getBy($params,$orderBy);
	}
	
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function getsBy($params, $orderBy = array('id'=>'ASC')) {
		return self::_getDao()->getsBy($params,$orderBy);
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public static function update($data, $id) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public static function updateBy($data, $params) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->updateBy($data, $params);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function delete($id) {
		return self::_getDao()->delete(intval($id));
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function add($data) {
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
		if(isset($data['id'])) $tmp['id'] = intval($data['id']);
		if(isset($data['task_name'])) $tmp['task_name'] = $data['task_name'];
		if(isset($data['img'])) $tmp['img'] = $data['img'];
		if(isset($data['award_type'])) $tmp['award_type'] = $data['award_type'];
		if(isset($data['award'])) $tmp['award'] = $data['award'];
		if(isset($data['status'])) $tmp['status'] = $data['status'];
		if(isset($data['create_time'])) $tmp['create_time'] = intval($data['create_time']);
		if(isset($data['start_time'])) $tmp['start_time'] = intval($data['start_time']);
		if(isset($data['end_time'])) $tmp['end_time'] = intval($data['end_time']);
		return $tmp;
	}
	
	/**
	 * 
	 * @return Client_Dao_DailyTask
	 */
	private static function _getDao() {
		return Common::getDao("Client_Dao_ContinueLoginActivityCofig");
	}
}
