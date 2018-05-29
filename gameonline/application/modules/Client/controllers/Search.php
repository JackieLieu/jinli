<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

class SearchController extends Client_BaseController {
	
	public $actions =array(
			'indexUrl' => '/client/search/index',
			'detailtUrl' => '/client/search/detail',
			'errorUrl' => '/client/search/error',
			'engineUrl' => '/client/search/engine',
			'enginetUrl' => '/client/search/enginet',
			'tjUrl' => '/client/search/index/tj',
	);
	public $perpage = 10;
	
	/**
	 * 
	 */
	public function indexAction() {
		$sp = $this->getInput('sp');
		$imei = end(explode('_',$sp));
		$tj = explode('_',$sp);
		Util_Cookie::set("imei", $imei, true, Common::getTime() + strtotime("+1 days"));
		Util_Cookie::set("version", $tj[1], true, Common::getTime() + strtotime("+1 days"));
		$page = intval($this->getInput('page'));
		$keyword = html_entity_decode($this->getInput('keyword'));
		$intersrc = $this->getInput('intersrc');
		$webroot = Common::getWebRoot();
		if ($page < 1) $page = 1;
		
		//关键字过滤
		$action = true;
		$filter = Game_Service_Config::getValue('game_search_filter');
		$filter = explode('|', $filter);
		if(in_array($keyword, $filter)) $action = false;
		
		$params = array();
		$search = array();
		if($keyword && $action) {
			$params['name'] = $keyword;
		}
		//获取本地所有游戏
		//过滤
		/*
		if($this->filter){
			$params['id'] = array('NOT IN', $this->filter);
		}*/
		$params['status'] = 1;
		list($total, $local_games) = Resource_Service_Games::adminSearch(1, $this->perpage, $params);
		$tmp = array();
		$games = array();
		if($local_games && $action){
			$games = Resource_Service_Games::getGameList($local_games, Common::getAttachPath());
			$from = 'gn';
		} else {
			$baiduApi = new Api_Baidu_Game();
			list($total, $baidu_games) = $baiduApi->engineList($keyword,1,$this->perpage);
			if($baidu_games && $action){
				$games = Common::resetKey($baidu_games, 'id');
				$from = 'baidu';
			} else {
				$games = array();
				$this->redirect($webroot. '/client/search/error?keyword='.$keyword);
				exit;
			}
		}
		
		//判断游戏大厅版本
		$checkVer = $this->checkAppVersion();
		$data = array();
		if($from == 'gn'){
			foreach($games as $key=>$value) {
				if ($checkVer >= 2) {
					//增加评测信息
					$value['pc_info'] = Client_Service_IndexAdI::getEvaluationByGame($value['id']);
					//增加礼包信息
					$value['gift_info'] = Client_Service_IndexAdI::haveGiftByGame($value['id']);
					//增加攻略信息
					$value['strategy_info'] = Client_Service_IndexAdI::getStrategyByGame($value['id']);
				}
				$data[] = $value;
			}
			$games = $data;
		}

		$hasnext = (ceil((int) $total / $this->perpage) - 1) > 0 ? true : false;
		$this->assign('hasnext', $hasnext);
		$this->assign('page', $page);
		$this->assign('games', $games);
		$this->assign('checkver', $checkVer);
		$this->assign('sp', $sp);
		$this->assign('keyword', $keyword);
		$this->assign('intersrc', $intersrc);
		$this->assign('total', $total);
		$this->assign('source', $this->getSource());
		$this->assign('from', $from);
	}
	
	/**
	 * 
	 */
	public function moreAction() {
		$page = intval($this->getInput('page'));
		$keyword = html_entity_decode($this->getInput('keyword'));
		$intersrc = $this->getInput('intersrc');
		if (!$intersrc)	$intersrc = 'SEARCH';
		$from = $this->getInput('from');
		$webroot = Common::getWebRoot();
		if ($page < 1) $page = 1;
		if($keyword) {
			$params['name'] = $keyword;
		}
		//过滤
		/*
		if($this->filter){
			$params['id'] = array('NOT IN', $this->filter);
		}*/
		//获取本地所有游戏
		$params['status'] = 1;
		list($total, $local_games) = Resource_Service_Games::adminSearch($page, $this->perpage, $params);
		
	    $temp = array();
	    if($local_games){
	    	$games = Resource_Service_Games::getGameList($local_games, Common::getAttachPath());
	    } else {
	    	$baiduApi = new Api_Baidu_Game();
	    	list($total, $baidu_games) = $baiduApi->engineList($keyword,$page,$this->perpage);
	    	if($baidu_games){
				$games = Common::resetKey($baidu_games, 'id');
			} else {
				$games = '';
				$this->redirect($webroot.'/client/error/index');
				exit;
			}
	    }
	    
	    //判断游戏大厅版本
	    $temp = array();
	    $checkVer = $this->checkAppVersion();
	   
	    foreach($games as $key=>$value){
	    	$href = urldecode($webroot.$this->actions['detailtUrl']. '?from='.$from.'&id=' . $value['id'].'&gname='.$value['name'].'&keyword='.$keyword.'&pc=1&intersrc='.$intersrc.'&t_bi='.$this->getSource());
	    	
	    	if ($from == 'gn') {
	    		if ($checkVer >= 2) {
	    			//加入评测链接
	    			$evaluationId = Client_Service_IndexAdI::getEvaluationByGame($value['id']);
	    			$evaluationUrl = '';
	    			if ($evaluationId) {
	    				$evaluationUrl = ',评测,' . $webroot . '/client/evaluation/detail/?id=' . $evaluationId . '&pc=3&intersrc=' . $intersrc . '&t_bi=' . $this->getSource();
	    			}
	    			//附加属性处理
	    			$attach = array();
	    			if ($evaluationId)	array_push($attach, '评');
	    			if (Client_Service_IndexAdI::haveGiftByGame($value['id'])) array_push($attach, '礼');
	    		}
	    	}
	    	
	    	$data = array(
	    			'id'=>$value['id'],
	    			'name'=>$value['name'],
	    			'resume'=>$value['resume'],
	    			'size'=>$value['size'].'M',
	    			'link'=>$href,
	    			'alink'=>urldecode($webroot.$this->actions['detailtUrl']. '?from='.$value['from'].'&id='.$value['id'].'&gname='.html_entity_decode($value['name']).'&keyword='.$keyword.'&pc=1&intersrc='.$intersrc.'&t_bi='.$this->getSource()),
	    			'img'=>($value['from'] ? $value['img'] : urldecode($value['img'])),
	    			'profile'=>$value['name'].','.$href.','.$value['id'].','.$value['link'].','.$value['package'].','.$value['size'].','.'Android'.$value['version'].','.$value['min_resolution'].'-'.$value['max_resolution'],
	    	);
	    	
   			if ($checkVer >= 2) {
   				$data['profile'] = '游戏详情,'.$href.','.$value['id'];
   				$data['data-type'] = 1;
   				if ($from == 'gn') {
	    			//js a 标签 data-infpage 参数数据
	    			$data['profile'] = $evaluationId ? $data['profile'] . $evaluationUrl : $data['profile'];
	    			$data['attach'] = ($attach) ? implode(',', $attach) : '';
	    			$data['device'] = $value['device'];
	    		}
	    	}
	    	
	    	$temp[] = $data;
	    	
	    }
	
		$hasnext = (ceil((int) $total / $this->perpage) - ($page)) > 0 ? true : false;
		$this->output(0, '', array('list'=>$temp, 'hasnext'=>$hasnext, 'curpage'=>$page));
		$this->assign('hasnext', $hasnext);
		$this->assign('page', $page);
		$this->assign('games', $temp);
		$this->assign('keyword', $keyword);
		$this->assign('intersrc', $intersrc);
		$this->assign('total', $total);
		$this->assign('source', $this->getSource());
		$this->assign('from', $from);
	}
	
	
	public static function errorAction() {
	}
	
   /**
	 * 
	 * get game detail info
	 */
	public function detailAction() {
		$id = intval($this->getInput('id'));
		//获取系统模版目录
		$tplPath = $this->getViewpath() ;
		//游戏大厅版本判断
		$checkVer = $this->checkAppVersion();
		//1.4.8之前版本
		$tpl= 'detail/v0';
		$this->_detailv0($id);
		
		if($checkVer >= 2){
			//1.4.8之后版本
			$tpl= 'detail/v1';
			$this->_detailv1($id);
		}
		
		if($checkVer >= 3){
			//1.5.1之后版本
			$tpl= 'detail/v2';
			$this->_detailv2($id);
		}
		
		if($checkVer >= 4){
			//1.5.2之后版本
			$tpl= 'detail/v3';
		}
		
		$this->display($tpl);
		exit;
	}
	
	private function _detailv0($gameid){
		$from = $this->getInput('from');
		$app_version = Util_Cookie::get('version', true);
		$t_bi = $this->getInput('t_bi');
		if (!$t_bi) {
			$this->setSource();
			$t_bi = $this->getSource();
		}
		$intersrc = $this->getInput('intersrc');
		if($from == 'baidu'){
			$baiduApi = new Api_Baidu_Game();
			$info = $baiduApi->getInfo($gameid, $from);
		} else {
			$info = Resource_Service_Games::getGameAllInfo(array('id'=>$gameid));
			$from == 'gn';
			//相关推荐
			$games = $this->_besttjData($gameid);
		}
		
		
		$this->assign('from', $from);
		$this->assign('info', $info);
		$this->assign('games', $games);
		$this->assign('gameid', $gameid);
		$this->assign('source', $this->getSource());
		$this->assign('intersrc', $intersrc);
		$this->assign('app_version', $app_version);
		
		if ($this->isAjax()) {
			$this->output(0, '', array('info'=>$info, 'gimgs'=>$info['img']));
		}
	}
	
	private function _detailv1($gameid){
		$t_bi = $this->getInput('t_bi');
		if (!$t_bi) {
			$this->setSource();
			$t_bi = $this->getSource();
		}
		$intersrc = $this->getInput('intersrc');
		//增加礼包模块
		$params = array(
				'game_id' => intval($gameid),
				'status'=>1,
				'effect_start_time' => array('<=', Common::getTime()),
				'effect_end_time' => array('>', Common::getTime())
		);
		$gifts = Client_Service_Gift::getsBy($params);
		$this->assign('gifts', $gifts);
		
		//加入评测
		$params = array(
				'ntype' =>2,
				'status' => 1,
				'game_id' => intval($gameid),
				'create_time'=> array('<=', Common::getTime()),
		);
		list(,$evaluation) = Client_Service_News::getList(1, 1, $params);
		$this->assign('evaluation', $evaluation);
		
		//加入攻略
		$params = array(
				'ntype' =>4,
				'status' => 1,
				'game_id' => intval($gameid),
				'create_time'=> array('<=', Common::getTime()),
		);
		list(,$strategy) = Client_Service_News::getList(1, 1, $params);
		$this->assign('strategy', $strategy);
		
		//相关推荐
		$games = $this->_besttjData($gameid);
		$webroot = Common::getWebRoot();
		$temp = array();
		foreach ($games as $key => $value) {
			//加入评测链接
			$evaluationId = Client_Service_IndexAdI::getEvaluationByGame($value['id']);
			$evaluationUrl = '';
			if ($evaluationId) {
				$evaluationUrl = ',评测,' . $webroot . '/client/evaluation/detail/?id=' . $evaluationId . '&pc=3&intersrc=' . $intersrc . '&t_bi=' . $t_bi;
			}
		
			$href = $webroot . '/client/search/detail/?id=' . $value['id'] . '&pc=1&intersrc=gltj' . $gameid.'_GID'.$value['id'] . '&t_bi=' .$t_bi ;
			$tempStr = sprintf("%s,%s,%s",'游戏详情',$href,$value['id']);
			if($evaluationId) $tempStr .= $evaluationUrl;
			$value['infpage-v2'] = $tempStr;
			$temp[] = $value;
		}
		$this->assign('gameid', $gameid);
		$this->assign('games', $temp);
		
	}
	
	private function _detailv2($gameid){
		//增加活动
		$params['game_id'] = $gameid;
		$params['start_time'] = array('<=',Common::getTime());
		$params['status'] = 1;
		$orderBy = array('sort'=>'DESC','start_time'=>'DESC');
		list(,$hd) = Client_Service_Hd::getList(1, 1, $params, $orderBy);
		$this->assign('gameid', $gameid);
		$this->assign('hd', $hd);
	}
	
	/**
	 * 推荐
	 * @param unknown_type $gameid
	 * @return array
	 */
	private function _besttjData($gameid){
		if(!$gameid) return '';
		$game_ids = Client_Service_Recommend::getRecommendGames(array('GAMEC_RESOURCE_ID'=>$gameid));
		if($game_ids){
			foreach($game_ids as $key=>$value){
				$tmp = array();
				$tmp = Resource_Service_Games::getGameAllInfo(array('id'=>$value['GAMEC_RECOMEND_ID']));
				if($tmp['status']){
					$games[] = $tmp;
				}
			}
		}
		return $games;
	}
	
}