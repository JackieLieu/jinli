<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * @author huangsg
 *
 */
class Store_Service_Category extends Common_Service_Base {
	/**
	 * 获取所有的分类
	 * @return multitype:
	 */
	public static function getAllCategory($version_type){
		return self::_getDao()->getsBy(array('status'=>1, 'version_type'=>$version_type), array('sort'=>'DESC'));
	}
	
	/**
	 * 获取分类列表，分页
	 * @param number $page
	 * @param number $limit
	 * @param unknown $params
	 * @return multitype:unknown multitype:
	 */
	public static function getList($page = 1, $limit = 10, $params = array()) {
		$params = self::_cookData($params);
		if ($page < 1) $page = 1;
		$start = ($page - 1) * $limit;
		$ret = self::_getDao()->getList($start, $limit, $params, array('sort'=>'DESC'));
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	/**
	 * 获取可以使用的分类
	 * @return Ambigous <boolean, mixed>
	 */
	public static function getCategoryForUser(){
		return self::_getDao()->getBy(array('status'=>1), array('sort'=>'DESC'));
	}
	
	/**
	 * 获取单条分类数据
	 * @param integer $id
	 * @return boolean|mixed
	 */
	public static function getCategory($id){
		if (!intval($id)) return false;
		return self::_getDao()->get(intval($id));
	}
	
	/**
	 * 添加分类数据
	 * @param array $data
	 * @return boolean|Ambigous <boolean, number>
	 */
	public static function addCategory($data){
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->insert($data);
	}
	
	/**
	 * 修改分类数据。
	 * @param array $data
	 * @param integer $id
	 * @return boolean|Ambigous <boolean, number>
	 */
	public static function updateCategory($data, $id){
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 * 删除分类数据。
	 * @param integer $id
	 * @return Ambigous <boolean, number>
	 */
	public static function deleteCategory($id){
		return self::_getDao()->delete(intval($id));
	}
	
	/**
	 * get by
	 */
	public static function getBy($params, $sort) {
		if (!is_array($params) || !is_array($sort)) return false;
		return self::_getDao()->getBy($params);
	}
	
	/**
	 *
	 * @param array $params
	 * @return array
	 */
	public static function getsBy($params, $sort) {
		if (!is_array($params) || !is_array($sort)) return false;
		$ret = self::_getDao()->getsBy($params, $sort);
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	/**
	 * 过滤数据
	 * @param array $data
	 * @return multitype:number unknown
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['sort'])) $tmp['sort'] = intval($data['sort']);
		if(isset($data['title'])) $tmp['title'] = $data['title'];
		if(isset($data['version_type'])) $tmp['version_type'] = $data['version_type'];
		if(isset($data['status'])) $tmp['status'] = intval($data['status']);
		return $tmp;
	}
	
	/**
	 *
	 * @return Store_Dao_Category
	 */
	private static function _getDao() {
		return Common::getDao("Store_Dao_Category");
	}
}