<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * @author rainkid
 *
 */
class Browser_Dao_News extends Common_Dao_Base{
	protected $_name = 'browser_news';
	protected $_primary = 'id';
	
	/**
	 *
	 * 获取分页列表数据
	 * @param array $params
	 * @param int $page
	 * @param int $limit
	 */
	public function getList($start = 0, $limit = 20, $params = array()) {
		$where = count($params) ? Db_Adapter_Pdo::sqlWhere($params) : 1;
		$sql = sprintf('SELECT * FROM %s WHERE 1 AND %s ORDER BY istop DESC, status DESC, sort DESC, ontime DESC LIMIT %d,%d', $this->getTableName(), $where, intval($start), intval($limit));
		return Db_Adapter_Pdo::fetchAll($sql, $params);
	}
	
	/**
	 * 
	 */
	public function getCanUseNews() {
		$time = Common::getTime();
		$sql = sprintf('SELECT * FROM %s WHERE 1 AND status = 1 AND start_time <= '.$time.' ORDER BY istop DESC, sort DESC, ontime DESC', $this->getTableName());
		return Db_Adapter_Pdo::fetchAll($sql);
	}
	
	/**
	 *
	 * 根据分类删除所有数据
	 */
	public function deleteByType($type) {
		$sql = sprintf('DELETE FROM %s WHERE type_id =  %s ORDER BY id ASC', $this->getTableName(), Db_Adapter_Pdo::quote($type));
		return Db_Adapter_Pdo::execute($sql, array(), true);
	}
	
	/**
	 * 根据分类取新闻
	 */
	public function getListByType($type) {
		$sql = sprintf('SELECT * FROM %s WHERE 1 AND type_id = %s ORDER BY ontime DESC', $this->getTableName(), $type);
		return Db_Adapter_Pdo::fetchAll($sql);
	}
	
	/**
	 * 指修改新闻显示状态
	 * @param array $ids
	 */
	public function updateStatusByIds($ids, $status) {
		$sql = sprintf('UPDATE %s SET status = %s WHERE id in %s', $this->getTableName(), $status, Db_Adapter_Pdo::quoteArray($ids));
		return Db_Adapter_Pdo::execute($sql);
	}
	
	/**
	 * 指修改新闻置顶状态
	 * @param array $ids
	 */
	public function updateTopById($id, $status) {
		$sql = sprintf('UPDATE %s SET istop = %s WHERE id = %s', $this->getTableName(), $status, Db_Adapter_Pdo::quote($id));
		return Db_Adapter_Pdo::execute($sql);
	}
	
	/**
	 *
	 * 按条件取内容
	 * @param array $params
	 * @param int $page
	 * @param int $limit
	 */
	public function getNewsList($types, $limit = 20, $params = array()) {
		$where = count($params) ? Db_Adapter_Pdo::sqlWhere($params) : 1;
		$sql = sprintf('SELECT * FROM %s WHERE type_id in %s AND %s ORDER BY id DESC, sort DESC, ontime DESC LIMIT 0,%d', $this->getTableName(), Db_Adapter_Pdo::quoteArray($types), $where, intval($limit));
		return Db_Adapter_Pdo::fetchAll($sql, $params);
	}
}
