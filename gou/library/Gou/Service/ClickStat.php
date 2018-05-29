<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 
 * Enter description here ...
 * @author tiansh
 *
 */
class Gou_Service_ClickStat{
	

    /**
     *
     * 分成渠道：1
     * 购物大厅—广告管理:2
     * 购物大厅—渠道管理:3
     * 购物大厅—专题管理:4
     * 购物大厅—导购管理:5
     * 购物大厅—消息通知:6
     * 购物大厅—商品管理:7
     * 货到付款—广告管理:8
     * 货到付款—导购管理:9
     * 专区——分类管理：10
     * 专区——广告管理：11
     * 资源管理：12
     * 饭饭主题：27
     * 知物：28
     * 教程：29
     * 热门活动：33
     * 教程：29
     * 闪屏：34
     * @param $type_id
     * @param $item_id
     * @return bool|int
     */
    public static function increment($type_id, $item_id) {
		$ret = self::_getDao()->getBy(array('dateline'=>date('Y-m-d'), 'type_id'=>$type_id, 'item_id'=>$item_id));
		if($ret) {
			return self::updateClickStat(array('number'=>$ret['number'] + 1), $ret['id']);
		} else {
			return self::addClickStat(array('type_id'=>$type_id, 'item_id'=>$item_id, 'number'=>1, 'dateline'=>date('Y-m-d')));
		}
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $id
	 */
	public static function updateClickStat($data, $id) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->update($data, intval($id));
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function addClickStat($data) {
		if (!is_array($data)) return false;
		$data = self::_cookData($data);
		return self::_getDao()->insert($data);
	}	
	
	
	/**
	 *
	 * Enter description here ...
	 * @param array $params
	 * @param int $page
	 * @param int $limit
	 */
	public static function search($page = 1, $limit = 10, $params = array()) {
		$params = self::_cookData($params);
		if ($page < 1) $page = 1;
		$start = ($page - 1) * $limit;
		$sqlWhere  = self::_getDao()->_cookParams($params);
		$ret = self::_getDao()->searchBy($start, $limit, $sqlWhere, array('id'=>'DESC'));
		$total = self::_getDao()->searchCount($sqlWhere);
		$sum = self::_getDao()->sumCount($sqlWhere);
		return array($sum, $total, $ret);
	}
	
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['type_id'])) $tmp['type_id'] = intval($data['type_id']);
		if(isset($data['item_id'])) $tmp['item_id'] = intval($data['item_id']);
		if(isset($data['number'])) $tmp['number'] = intval($data['number']);
		if(isset($data['dateline'])) $tmp['dateline'] = $data['dateline'];
		
		if(isset($data['start_time'])) $tmp['start_time'] = $data['start_time'];
		if(isset($data['end_time'])) $tmp['end_time'] = $data['end_time'];
		return $tmp;
	}
	
		
	/**
	 *
	 * @return Gou_Dao_ClickStat
	 */
	private static function _getDao() {
		return Common::getDao("Gou_Dao_ClickStat");
	}
}
