<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 升级峰值
 * Resource_Service_Upgrade
 * @author wupeng
 */
class Resource_Service_Upgrade {
    /**缓存key*/
    const CACHE_KEY = "game_resource_upgrade_cache";
    /**缓存时间*/
    const CACHE_EXPIRE = 604800;
    
	/**
	 * 返回所有记录
	 * @return array
	 */
	public static function getAllUpgrade() {
		return array(self::getDao()->count(), self::getDao()->getAll());
	}
	
	/**
	 * 分页查询
	 * @param int $page
	 * @param int $limit
	 * @param array $searchParams
	 * @param array $sortParams
	 * @return array
	 */	 
	public static function getPageList($page = 1, $limit = 10, $searchParams = array(), $sortParams = array()) {
		if ($page < 1) $page = 1; 
		$start = ($page - 1) * $limit;
		$ret = self::getDao()->getList($start, $limit, $searchParams, $sortParams);
		$total = self::getDao()->count($searchParams);
		return array($total, $ret);
	}
	
	/**
	 * 根据主键查询一条记录
	 * @param int $id
	 * @return array
	 */
	public static function getUpgrade($id) {
		if (!$id) return null;		
		$keyParams = array('id' => $id);
		return self::getDao()->getBy($keyParams);
	}
	
	/**
	 * 根据主键更新一条记录
	 * Enter description here ...
	 * @param array $data
	 * @param int $id
	 * @return boolean
	 */
	public static function updateUpgrade($data, $id) {
		if (!$id) return false;
		$dbData = self::checkField($data);
		if (!is_array($dbData)) return false;
		$keyParams = array('id' => $id);
		return self::getDao()->updateBy($dbData, $keyParams);
	}
	
	/**
	 * 根据主键删除一条记录
	 * @param int $id
	 * @return boolean
	 */
	public static function deleteUpgrade($id) {
		if (!$id) return false;
		$keyParams = array('id' => $id);
		return self::getDao()->deleteBy($keyParams);
	}
	
	/**
	 * 根据主键删除多条记录
	 * @param array $keyList
	 * @return boolean
	 */
	public static function deleteUpgradeList($keyList) {
		if (!is_array($keyList)) return false;
		return self::getDao()->deletes('id', $keyList);
	}
	
	/**
	 * 添加一条记录
	 * @param array $data
	 * @return boolean
	 */
	public static function addUpgrade($data) {
		if (!is_array($data)) return false;
		$dbData = self::checkField($data);
		return self::getDao()->insert($dbData);
	}
	
	/**
	 * 删除升级配置缓存key
	 */
	public static function deleteUpgradeCacheKey() {
	    $cache = Cache_Factory::getCache();
	    $cache->delete(self::CACHE_KEY);
	}
	
	/**
	 * 获取升级配置缓存数据
	 * @return array:
	 */
	public static function getUpgradeCacheData() {
	    $cache = Cache_Factory::getCache();
	    $dataList =  $cache->get(self::CACHE_KEY);
	    if($dataList === false){
	        $dataList = self::getDao()->getAll();
	        $cache->set(self::CACHE_KEY, $dataList, self::CACHE_EXPIRE);
	    }
	    return $dataList;
	}
	
	/**
	 * 检查数据库字段
	 * @param array $data
	 * @return array
	 */
	private static function checkField($data) {
		$dbData = array();
		if(isset($data['id'])) $dbData['id'] = $data['id'];
		if(isset($data['phone_ram_min'])) $dbData['phone_ram_min'] = $data['phone_ram_min'];
		if(isset($data['phone_ram_max'])) $dbData['phone_ram_max'] = $data['phone_ram_max'];
		if(isset($data['max_apk'])) $dbData['max_apk'] = $data['max_apk'];
		return $dbData;
	}

	/**
	 * @return Resource_Dao_Upgrade
	 */
	private static function getDao() {
		return Common::getDao("Resource_Dao_Upgrade");
	}
	
}
