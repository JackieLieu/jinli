<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 游戏大厅排行榜 -- 最新榜
 * Client_Dao_NewRank
 * @author lichanghua
 *
 */
class Client_Dao_NewRank extends Common_Dao_Base{
	protected $_name = 'dlv_game_rank_new';
	protected $_primary = 'day_id';
	
	public function getLastDayId() {
		$sql = sprintf('SELECT  day_id FROM %s ORDER BY day_id DESC LIMIT 1',$this->getTableName());
		return $this->fetcthAll($sql);
	}
	
	public function delByBeforDay($date) {
		$sql = sprintf('DELETE FROM %s WHERE day_id <= %s',$this->getTableName(), $date);
		return Db_Adapter_Pdo::execute($sql, array(), false);
	}
}