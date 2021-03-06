<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 游戏大厅排行榜 -- 游戏飙升榜
 * Enter description here ...
 * @author lichanghua
 *
 */
class Client_Service_SoaringRank{
	
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
		$ret = self::_getDao()->getList($start, $limit, $params, array('score'=>'DESC'));
		$total = self::_getDao()->count($params);
		return array($total, $ret);
	}
	
	public static function getLastDayId() {
		$ret = self::_getDao()->max('day_id');
		return $ret;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public static function getRank($id) {
		if (!intval($id)) return false;
		return self::_getDao()->get(intval($id));
	}

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	private static function _cookData($data) {
		$tmp = array();
		if(isset($data['day_id'])) $tmp['day_id'] = intval($data['day_id']);
		if(isset($data['game_id'])) $tmp['game_id'] = intval($data['game_id']);
		if(isset($data['score'])) $tmp['score'] = $data['score'];
		return $tmp;
	}
	
	/**
	 * 
	 * @return Client_Dao_OlgRank
	 */
	private static function _getDao() {
		return Common::getDao("Client_Dao_SoaringRank");
	}
}
