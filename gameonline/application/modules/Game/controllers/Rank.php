<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author lichanghua
 *
 */
class RankController extends Game_BaseController{
	
	public $actions = array(
		'listUrl' => '/rank/index',
		'detailUrl' => '/index/detail/',
		'tjUrl' => '/index/tj'
	);

	public $perpage = 10;
	/**
	 * 
	 * index page view
	 */
public function indexAction() {
		$flag = intval($this->getInput('flag'));
		$page = intval($this->getInput('page'));
		$intersrc = $this->getInput('intersrc');
		
		$configs = Game_Service_Config::getAllConfig();
		unset($configs['game_react']);
		$this->assign('configs', $configs);
		
		if ($page < 1) $page = 1;
		
		$games = array();
		$resource_games = array();
		
		if($flag == 1){
			//最新游戏
			$limit = Game_Service_Config::getValue('game_rank_newnum');
			$offset = min($limit, $this->perpage);
			$params = array('status'=> 1,'game_status'=>1,'start_time'=>array('<=',Common::getTime()));
			list($total, $resource_games) = Client_Service_Taste::getList(1, $offset, $params, array('start_time' => 'DESC','game_id' => 'DESC'));
		 	$total = min($limit, $total);
		} else {
			$limit = Game_Service_Config::getValue('game_rank_mostnum');
			list(, $bi_games) = Client_Service_Rank::getMostGames($limit,'',$this->filter);
			$resource_ids = Common::resetKey($bi_games, 'GAME_ID');
			$resource_ids = array_unique(array_keys($resource_ids));
			
			$this->perpage = min($this->perpage, $limit);
			
			if($resource_ids){
				list($total, $resource_games) = Resource_Service_Games::search(1, $this->perpage, array('id'=>array('IN', $resource_ids),'status'=>1));
			}
			$total = min($limit, $total);
		}
		foreach($resource_games as $key=>$value) {
			$info = Resource_Service_Games::getGameAllInfo(array('id'=>$value['game_id']?$value['game_id']:$value['id']));
			if ($info) $games[] = $info; 
		}
		$this->assign('games', $games);
		$hasnext = (ceil((int) $total / $this->perpage) - 1) > 0 ? true : false;
		$this->assign('hasnext', $hasnext);
		$this->assign('page', $page);
		$this->assign('flag', $flag);
		$this->assign('source', $this->getSource());
		$this->assign('intersrc', $intersrc);
	}
	
	/**
	 * get game list as more
	 */
	public function moreAction(){
		$flag = intval($this->getInput('flag'));
	    $page = intval($this->getInput('page'));
	    $intersrc = $this->getInput('intersrc');
	    $this->assign('configs', Game_Service_Config::getValue('game_rank_mostnum'));
	    
		if ($page < 1) $page = 1;
		
		$games = array();
		$resource_games = array();
		$resource_ids = array();
		$bi_games = array();
		
		if($flag == 1){
			//最新游戏
			$limit = Game_Service_Config::getValue('game_rank_newnum');
			$offset = min($limit, $this->perpage);
			$params = array('status'=> 1,'game_status'=>1,'start_time'=>array('<=',Common::getTime()));
		 	list($total, $resource_games) = Client_Service_Taste::getList($page, $offset, $params, array('start_time' => 'DESC','game_id' => 'DESC'));
		 	$limit = Game_Service_Config::getValue('game_rank_newnum');
		 	$total = min($total, $limit);
		} else {
			$limit = Game_Service_Config::getValue('game_rank_mostnum');
			list(, $bi_games) = Client_Service_Rank::getMostGames($limit,'',$this->filter);
			$resource_ids = Common::resetKey($bi_games, 'GAME_ID');
			$resource_ids = array_unique(array_keys($resource_ids));
			$this->perpage = min($this->perpage, $limit);
			if($resource_ids){
				list($total, $resource_games) = Resource_Service_Games::search($page, $this->perpage, array('id'=>array('IN', $resource_ids),'status'=>1));
			}
			$total = min($limit, $total);
		}
		
		foreach($resource_games as $key=>$value) {
			$info = Resource_Service_Games::getGameAllInfo(array('id'=>($flag == 1)?$value['game_id']:$value['id']));
			if ($info) $games[] = $info;
		}
		$temp = array();
		$webroot = Common::getWebRoot();

		if($intersrc){
			$t_id = $intersrc;
		} else {
			if($flag == 1){
				$t_id = 'Newrelease';
			} else {
				$t_id = 'Mostdownload';
			}
		}
		
		$i = 0;
		foreach($games as $key=>$value) {
			$data = array();
			$num = $i + (($page - 1) * $this->perpage);
			if ($num >= $total) break;
			
			$href = urldecode($webroot.$this->actions['detailUrl']. '?id=' . $value['id'].'&flag='.$flag.'&intersrc='.$t_id.'&t_bi='.$this->getSource());
			$temp[] = array(
						'id'=>$value['id'],
						'name'=>$value['name'],
						'resume'=>$value['resume'],
						'size'=>$value['size'].'M',
						'link'=>Common::tjurl($this->actions['tjUrl'], $value['id'], $intersrc, $value['link']),
						'alink'=>urldecode($webroot.$this->actions['detailUrl']. '?id=' . $value['id'].'&intersrc='.$intersrc.'&t_bi='.$this->getSource()),
						'img'=>urldecode($value['img']),
						'profile'=>$value['name'].','.$href.','.$value['id'].','.$value['link'].','.$value['package'].','.$value['size'].','.$value['min_sys_version_title'].','.$value['min_resolution_title'].'-'.$value['max_resolution_title'],
			);
			
			$i++;
		}
		
		$hasnext = (ceil((int) $total / $this->perpage) - $page) > 0 ? true : false;
		$this->output(0, '', array('list'=>$temp, 'hasnext'=>$hasnext, 'curpage'=>$page));
	}
}
