<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 单独推荐
 * Game_Service_SingleListAlone
 * @author wupeng
 */
class Game_Service_SingleListAlone {

	public static function getSingleListAloneListBy($searchParams, $sortParams = array()) {
	    return self::getDao()->getsBy($searchParams, $sortParams);
	}

	public static function getSingleListAloneBy($searchParams, $sortParams = array()) {
	    return self::getDao()->getBy($searchParams, $sortParams);
	}

	public static function getPageList($page = 1, $limit = 10, $searchParams = array(), $sortParams = array()) {
		if ($page < 1) $page = 1; 
		$start = ($page - 1) * $limit;
		$ret = self::getDao()->getList($start, $limit, $searchParams, $sortParams);
		$total = self::getDao()->count($searchParams);
		return array($total, $ret);
	}

	public static function getSingleListAlone($id) {
		if (!$id) return null;		
		$keyParams = array('id' => $id);
		return self::getDao()->getBy($keyParams);
	}

	public static function updateSingleListAlone($data, $id) {
		if (!$id) return false;
		$data = self::checkField($data);
		if (!is_array($data)) return false;
		$keyParams = array('id' => $id);
		return self::getDao()->updateBy($data, $keyParams);
	}

	public static function updateSingleListAloneBy($data, $searchParams) {
	    $data = self::checkField($data);
	    if (!is_array($data)) return false;
	    return self::getDao()->updateBy($data, $searchParams);
	}

	public static function deleteSingleListAlone($id) {
		if (!$id) return false;
		$keyParams = array('id' => $id);
		return self::getDao()->deleteBy($keyParams);
	}

	public static function deleteSingleListAloneList($keyList) {
		if (!is_array($keyList)) return false;
		return self::getDao()->deletes('id', $keyList);
	}

	public static function deleteSingleListAloneBy($searchParams) {
		if (!$searchParams) return false;
		return self::getDao()->deleteBy($searchParams);
	}

	public static function addSingleListAlone($data) {
		if (!is_array($data)) return false;
		$data = self::checkNewField($data);
		return self::getDao()->insert($data);
	}

	public static function addMutiSingleListAlone($list) {
	    if (! $list) return false;
	    foreach ($list as $key => $data) {
	        $list[$key] = self::checkNewField($data);
	    }
	    return self::getDao()->mutiFieldInsert($list);
	}

	public static function checkNewField($data) {
	    $record = array();
		$record['recommend_id'] = isset($data['recommend_id']) ? $data['recommend_id'] : "";
		$record['link_type'] = isset($data['link_type']) ? $data['link_type'] : 0;
		$record['link'] = isset($data['link']) ? $data['link'] : "";
		return $record;
	}

	private static function checkField($data) {
	    $record = array();
		if(isset($data['id'])) $record['id'] = $data['id'];
		if(isset($data['recommend_id'])) $record['recommend_id'] = $data['recommend_id'];
		if(isset($data['link_type'])) $record['link_type'] = $data['link_type'];
		if(isset($data['link'])) $record['link'] = $data['link'];
		return $record;
	}

	private static function getDao() {
		return Common::getDao("Game_Dao_SingleListAlone");
	}

}
