<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Client_Dao_News
 * @author lichanghua
 *
 */
class Client_Dao_News extends Common_Dao_Base{
	protected $_name = 'game_news';
	protected $_primary = 'id';
	
	/**
	 * 
	 * @param unknown_type $params
	 * @return string
	 */
	public function _cookParams($params) {
		$sql = ' ';
		$time = Common::getTime();
		if (is_array($params['ids']) && count($params['ids'])) {
			$sql .= " AND out_id IN " . Db_Adapter_Pdo::quoteArray($params['ids']);
		}
		if (is_array($params['aid']) && count($params['aid']) && $params['status']) {
			$sql .= " AND status = 1 AND (( ctype = 1 ) OR ( create_time <= '".$time."' AND ctype = 2 ))  AND id IN " . Db_Adapter_Pdo::quoteArray($params['aid']);
			unset($params['status']);
		} else if(is_array($params['aid']) && count($params['aid'])){
			$sql .= " AND id IN " . Db_Adapter_Pdo::quoteArray($params['aid']);
		}
		if($params['status']){
			$sql .= " AND status = 1 AND (( ctype = 1 ) OR ( create_time <= '".$time."' AND ctype = 2 )) ";
			unset($params['status']);
		}
		if ($params['title']) {
			$sql .= " AND title like '%" . Db_Adapter_Pdo::filterLike($params['title']) . "%'";
		}
		if ($params['st'] == '1') {
			$sql .= " AND id = 0 ";
		}
		unset($params['ids']);
		unset($params['aid']);
		unset($params['title']);
		unset($params['create_time']);
		unset($params['st']);
		return Db_Adapter_Pdo::sqlWhere($params).$sql;
	}
	
	/**
	 *
	 * @param unknown_type $variable
	 * @return string
	 */
	public function quoteInArray($variable) {
		if (empty($variable) || !is_array($variable)) return '';
		$_returns = array();
		foreach ($variable as $value) {
			$_returns[] = Db_Adapter_Pdo::quote($value);
		}
		return '(' .'out_id'.','. implode(', ', $_returns) . ')';
	}
	
	/**
	 *
	 * @param unknown_type $variable
	 * @return string
	 */
	public function quoteAdInArray($variable) {
		if (empty($variable) || !is_array($variable)) return '';
		$_returns = array();
		foreach ($variable as $value) {
			$_returns[] = Db_Adapter_Pdo::quote($value);
		}
		return '(' .'id'.','. implode(', ', $_returns) . ')';
	}
}
