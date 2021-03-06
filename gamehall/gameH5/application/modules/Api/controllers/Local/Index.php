<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 *
 * 游戏大厅 1.5.1 版本 开始使用
 * API V1
 * @author lichanghua
 *
 */
class Local_IndexController extends Api_BaseController {
	public $perpage = 10;
	
	public $cachedKeys = array(
		'dataList'=>'RECOMMEND:DATA:LIST:',
		'adList'	=>'RECOMMEND:AD:LIST:',
		'offset'	=>'RECOMMEND:AD:OFFSET:',
		'limit'		=>'RECOMMEND:AD:LIMIT:',
		'interval'	=>'RECOMMEND:AD:INTERVAL:',
	);
	/**
	 * 首页layout
	 */   
	public function frameAction() {
		$labelConfig =  Common::getConfig("apiConfig", 'layout');
		$items = $labelConfig['items'];
		//获取客户端状态开启的各个排行榜
		$client_rank = Game_Service_Config::getConfigRank('client', true);
	
		$this->saveColumnBehaviour();

		$temp = array();
		//默认的排行榜
		$rankDefault =  Common::getConfig("apiConfig", 'clientRank');
		foreach($client_rank as $value){
			$temp[] = $rankDefault[$value['key']];
		}
		if ($temp && count($temp) < 2)  {       //少于2个导航
			unset($items[2]['items']);
			$items[2]['title'] = $temp[0]['title'];
			$items[2]['viewType'] = $temp[0]['viewType'];
			$items[2]['url'] = $temp[0]['url'];
			$items[2]['source'] = $temp[0]['source'];
		} else if($temp && count($temp) >= 2){  //大于等于2个导航
			$items[2]['items'] = $temp;
		}
		$version = Game_Service_Config::getValue('rank_config_time');
		$v = intval($this->getInput('v'));
		if($version ==  $v) return array(); 
		$tmp['items'] = $items;
		exit(json_encode(array(
		'success' => $tmp  ? true : false ,
		'msg' => '',
		'sign' => 'GioneeGameHall',
		'version' => $version,
		'data' => $tmp
		)));
	}
	
	private function getImei() {
		$imei = trim($this->getInput('imei'));
		if (!$imei) {
			$sp = $this->getInput('sp');
			$imei = Common::parseSp($sp, 'imei');
		}
		return $imei;
	}

	private function saveColumnBehaviour() {
		$imei = $this->getImei();
		$behaviour = new Client_Service_ClientBehaviour($imei, Client_Service_ClientBehaviour::CLIENT_HALL);
        $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_GET_COLUMN);
	}

	private function saveFirstPageBehaviour() {
		$imei = $this->getImei();
		$behaviour = new Client_Service_ClientBehaviour($imei, Client_Service_ClientBehaviour::CLIENT_HALL);
        $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_GET_FIRST_PAGE);
	}

	/**
	 * 首页广告
	 */
	public function indexAdAction() {
		$this->saveFirstPageBehaviour();
		$webroot = Common::getWebRoot();
		$version = intval($this->getInput('v'));
		$server_version = Game_Service_Config::getValue('Ad_Version');
		if ($version == $server_version){	//数据版本一致则不返回数据
			$this->output(0, '', array());
		}
		
		list(, $ads) = Client_Service_Ad::getCanUseNormalAds(1, 12, array('ad_type'=>1, 'status'=>1, 'hits'=>1));
		$i = 1;
		foreach($ads as $key=>$value) {
			$info = Local_Service_IndexAd::cookClientAd($value, "ad1", $i++);
			$ads[$key] = $info;
		}
		
		$tmp = $temp = $stmp =array();
		
		//广告
		foreach($ads as $key=>$value){
			if($value['viewType'] && $value['ad_id']){
				$stmp[] = array(
	    					'viewType'=>$value['viewType'],
	    					'title'=>html_entity_decode($value['title']),
	    					'imageUrl'=>$value['img'],
	    					'param'=>$value['param'],
						    'ad_id'=>$value['ad_id'],
	    			);
			}
			if(count($stmp) == 6) break;
		}
		
		//频道bannel
		$channel =  array(
    					0 => array(
    							'viewType'=>'LatestView',
    							'url'=>$webroot.'/client/rank/index?flag=1',
    							'source'=>'newon',
    							),
						1 => array(
								'viewType'=>'ClassicView',
								'url'=>$webroot.'/Api/Local_Installe/installeList',
								'source'=>'classic',
						),
						2 => array(
								'viewType'=>'GuessView',
								'url'=>$webroot.'/Api/Local_Guess/guessList',
								'source'=>'glike',
						),
    	);
		
		//活动
		$params = $temp = array();
		$params['ad_type'] = 8;
		list(, $announce) = Client_Service_Ad::getCanUseNormalAds(1, 1, $params);
	    $announce = Local_Service_IndexAd::cookClientAd($announce[0], "ad3");
	    
	    if($announce['viewType']){
	    	$hdItem[] = array(
	    			'viewType' => $announce['viewType'],
	    			'title' => "textAnnounce",
	    			'content'=>html_entity_decode($announce['title']),
	    			'param'=> $announce['param'],
	    			'source'=>'homeevent',
	    	);
	    }
		//v1.5.4-福利任务提示
		
		$temp['slideItems'] = $stmp;
		$temp['channelItems'] = $channel;
		$temp['activityItem'] = $hdItem ? $hdItem : array();
		
		
		$data = $this->_IndexList(1);
		$adlist = json_encode(array(
				'success' => $data ? true : false ,
				'msg' => '',
				'sign' => 'GioneeGameHall',
				'data' => $data,
		));
		$temp['listData'] = $adlist;
		$this->localOutput('','',$temp);
		
	}
	
	/**
	 * 首页推荐列表
	 */
	public function IndexListAction() {
		$page = intval($this->getInput('page'));
		$data = $this->_IndexList($page);
		$this->localOutput('','',$data);
	}
	
	
	private  function _IndexList($page) {
		//推荐专题
		if ($page < 1) $page = 1;
		$status = Game_Service_Config::getValue('client_picture_status');//添加推荐图片
		$interval = Game_Service_Config::getValue('client_picture_space');
		
		//游戏列表信息
		$params =  $insertData = array();
		$params['ad_type'] = 3;
		$params['status'] = 1;
		//当前页面游戏信息
		list($total, $subjects) = Client_Service_Ad::getCanUseApiAds($page, $this->perpage, $params);
		$hasnext = (ceil((int) $total / $this->perpage) - ($page)) > 0 ? true : false;
		foreach($subjects as $key=>$value) {
			$subject_games[$key] = Local_Service_IndexAd::cookClientAd($value, "ad4");
		}
		//插图广告开关
		if($status){
			//获取插图广告
			$recAds = Client_Service_InsertAdData::getInsertPicAd();
			//当前页面广告位置
			list($adUsedCount, $previousPageGamesAfterLastAd) = Client_Service_InsertAdData::getAdPos($page,  $interval);
			//当前页面的广告
			$adsUnused = Client_Service_InsertAdData::getCurrpageAd($adUsedCount, $recAds, $interval);
			//插入当前数据
			$insertData['subjectGames'] = $subject_games;
			$insertData['adUnusedArr'] = $adsUnused;
			$insertData['interval'] = $interval;
			$insertData['previousPageGamesAfterLastAd'] = $previousPageGamesAfterLastAd;
			$pageData = Client_Service_InsertAdData::insertAdData($page, $insertData);
		}
		$data  = $status ? $pageData : $subject_games;
		$data =  $this->_jsonData($data, 'ad4' ,$page, $hasnext);
		return $data;
	}
	
	/**
	 * 首页活动
	 */
	public function activityAction() {
		$webroot = Common::getWebRoot();
		$version = $this->getInput('version');
		$id = $this->getInput('id');
		$info = Client_Service_Hd::getHd($id);
		if(!$info) $this->localOutput('','',array());
		
		$items = array(
				'0' => array(
						'title'=>'活动介绍',
						'viewType'=>'Webview',
						'source'=>'eventinfo',
						'url'=>$webroot . '/client/activity/hdetail/?id=' . $info['id'],
				),
				'1' => array(
						'title'=>'中奖公告',
						'viewType'=>'Webview',
						'source'=>'placard',
						'url'=>$webroot . '/client/activity/announce/?id=' . $info['id'],
				)
		);
		
		$temp = $temp1 = array();
		$time = Common::getTime();
		$activityState = 'open';
		if($info['end_time'] < $time) $activityState = 'close';
		array_push($temp,$items[0]);
		array_push($temp,$items[1]);
		
		if($info['start_time'] > $time) {                           //活动未开始，不显示活动介绍和中奖公告 
			$activityState = 'notstart';
			unset($temp);
		}
		
		$tmp = array(
				'viewType'=>'ActivityDetailView',
				'title'=>'活动详情',
				'activityName'=>html_entity_decode($info['title']),
				'activityTime'=>'时间:'.date("Y-m-d",$info['start_time']).' 至 '.date("Y-m-d",$info['end_time']),
				'activityState'=>$activityState,
				'gameId'=>$info['game_id'],
				'items'=>$temp,
		);
		
		$this->localOutput('','',$tmp);
	}
	
	/**
	 * 游戏详情页
	 */
	public function gameInfoAction() {
		$version = $this->getInput('version');
		$game_id = intval($this->getInput('id'));
		$sp = $this->getInput('sp');
		$from = $this->getInput('from');
		$intersrc = $this->getInput('intersrc');
		$webroot = Common::getWebRoot ();
		
		if($from == 'gn'){
			$info = Resource_Service_GameData::getGameAllInfo($game_id);
		} else if ($from == 'baidu'){
			$baiduApi = new Api_Baidu_Game();
			$baidu_game = $baiduApi->getInfo($game_id, 'baidu');
			if(!$baidu_game) $this->localOutput('','',array());
			$info = $baidu_game;
		}
		
		//礼包
		$params = array('game_id'=>$game_id,'status' => 1, 'game_status'=>1);
		$params['effect_start_time'] = array('<=', Common::getTime());
		$params['effect_end_time'] = array('>=', Common::getTime());
		$gifts = Client_Service_Gift::getsBy($params);
		//评测
		$evaluationId = Client_Service_IndexAdI::getEvaluationByGame($game_id);
		//攻略
		$strategyId = Client_Service_IndexAdI::getStrategyByGame($game_id);
		//客户端详情页
		$detail = $webroot. '/client/index/detail/?id='.$game_id.'&pc=1';
		//百度走向搜索详情页
		if ($from == 'baidu') $detail = $webroot. '/client/search/detail/?id='.$game_id.'&pc=1&from='.$from;
		
		if($intersrc) $detail = $detail.'&intersrc='.$intersrc;
		$items = array(
				'info' => array(
						'title'=>'介绍',
						'viewType'=>'Webview',
						'source' =>'content',
						'url'=>$detail,
				),
				'gift' => array(
						'title'=>'礼包',
						'viewType'=>'Webview',
						'source' =>'giftlist',
						'url'=>$webroot. '/client/gift/index?id='.$game_id.'&isDetail=1',
				),
				'evaluation' => array(
						'title'=>'评测',
						'viewType'=>'Webview',
						'source' =>'evaluation',
						'url'=>$webroot. '/client/evaluation/detail/?id='.$evaluationId,
				),
				'strategy' => array(
						'title'=>'攻略',
						'viewType'=>'Webview',
						'source' =>'strategy',
						'url'=>$webroot. '/client/strategy/index/?id='.$game_id,
				)
		);
		
		$items['comment'] = array(
				"title" => "评论",
				"viewType" => "CommentView",
				"source" => "commentdetail",
				"url" => $webroot."/Api/Comment/Index?"
		);
		$temp = array();
		array_push($temp,$items['info']);
		if(!$baidu_game && $gifts)  array_push($temp, $items['gift']);
		//增加评论
		if((strnatcmp($version, '1.5.2') >= 0)){
			if(!$baidu_game)  array_push($temp, $items['comment']);
		} else {
			//评测
			if(!$baidu_game && $evaluationId)  array_push($temp, $items['evaluation']);
		}
		//攻略
		if(!$baidu_game && $strategyId)  array_push($temp, $items['strategy']);
		
		$tmp = array(
				'viewType'=>'GameDetailView',
				'title'=>'游戏详情',
				'gameName'=>html_entity_decode($info['name']),
				'gameSize'=>$info['size'].'M',
				'gameDeveloper'=>$info['developer'],
				'gameCategory'=>($baidu_game ? $baidu_game['category'] : $info['category_title']),
				'gameLanguage'=>$info['language'],
				'gameIconUrl'=>$info['img'],
				'gameId'=>$info['id'],
				'gameSecurityCertificate'=>'',
				'items'=>$temp,
		);
		
		$this->localOutput('','',$tmp);
	}
	
	/**
	 * 客服联系电话
	 */
	public function csAction() {
		$phone = Game_Service_Config::getValue('game_service_tel');
		$tmp['cuphone'] = $phone;
		$this->localOutput('','',$tmp);
	}
	
	/**
	 * 游戏icon图标
	 */
	public function iconAction() {
		$game_ids = $this->getInput('ids');
		$ids = explode(',',$game_ids);
		$temp = array();
		foreach($ids as $key=>$value){
			$info = Resource_Service_Games::getBy(array('id'=>$value));
			if($info)  $tmp[] = $info['img'];
		}
		if($tmp){
			$temp['imgUrl'] = $tmp;
		}
		$this->localOutput('','',$temp);
	}
	
	/**
	 * 组装数据
	 * @param unknown_type $ads
	 * @param unknown_type $name
	 * @param unknown_type $page
	 * @param unknown_type $hasnext
	 * @return array
	 */
	private  function _jsonData($ads, $name, $page, $hasnext) {
		$data = array('list'=>$ads, 'hasnext'=>$hasnext, 'curpage'=>$page);
		return $data;
	}
	
	
	/**
	 * 新栏目配置接口
	 */
	public function columnAction() {	

		$this->saveColumnBehaviour();
		
		$data = array();
		//取得客户的版本号
		$app_flag = $this->checkAppVersion();
		if($app_flag >= 5){
			$column_version = '1.5.3' ;
			$data['data']['minVer'] = '1.5.3.a';
		}elseif($app_flag == 4){
			$column_version = '1.5.2' ;
			$data['data']['minVer'] = '1.5.2.a';
			$data['data']['maxVer'] = '1.5.3.a';
		}
		if(!$column_version) $this->clientOutput(array());
		
		$log_param['column_version'] = $column_version;
		$log_param['status'] = 1;
		$log_param['step'] = 4;
		$log_param['is_deafault'] = 1;
		$log_param['start_time'] = array('<=',Common::getTime());
		$log_ret = Client_Service_ColumnLog::getBy($log_param, array('start_time'=>'DESC','id'=>'DESC'));
		
		//取得日志的ID
		if(!$log_ret) $this->clientOutput(array());
		$log_id = $log_ret['id'];
		
		$columnVersion = $log_ret['update_time'];
		//版本验证
		$version = $this->getInput('v');
		if ($version >= $columnVersion) exit();
		
		
		$data['success'] = true;
		$data['version'] = intval($log_ret['update_time']);
		$data['sign'] = 'GioneeGameHall';
		$data['msg'] = '';
	
		$params['pid'] = 0;
	    $info = Client_Service_ColumnNew::getAllColumn();
	    $api_config = Common::getConfig('apiConfig','layoutnew');
	    $ext_view   = Common::getConfig('apiConfig','ext_type');
	    //支持扩展的类型
	    $ext_arr = array();
	    foreach ($ext_view as $val){
	    	$ext_arr[] = $val['value'];
	    }

	    $attachroot = Common::getAttachPath();
	    //一级
	    $info = Client_Service_ColumnNew::getListBywhere(array('pid'=>0,'status'=>1,'log_id'=>$log_id));
	    $info_count = count($info);
	 
	    //把二级栏目组合
	    $tmp = array();
	    $default_open = 0;
	    $default_arr = array();
	    foreach ($info as $key => $val){
	    	$tmp[$key] = $val;
	    	$childs = Client_Service_ColumnNew::getListBywhere(array('pid'=>$val['position'], 'status'=>1, 'log_id'=>$log_id));
	    	if (!empty($childs)){
	    		$tmp[$key]['items'] = $childs;
	    	}
	    	//默认打开
	    	if($val['default_open']){
	    		$default_open = $val['position'];
	    	}
	    	$default_arr[] = $val['position'];
	    }   
	    //关闭那个位置
	    for ($i = 1; $i <= 5; $i++ ){
	    	if(!in_array($i, $default_arr)){
	    		$close_flag = $i ;
	    		break;
	    	}
	    }
	    
	    //默认打开大于取出条数
	    if( ( $info_count < 5 ) && ( $close_flag < $default_open )  ){
	    	$default_open = $default_open - 1;
	    }
	    //默认配置
	    $data['data']['defaultTabIndex'] = intval($default_open);
	    //精品推荐下
	    $channel_list = Client_Service_ColumnNew::getListBywhere(array('pid'=>6,'status'=>1, 'log_id'=>$log_id));  
	    $channel = array();
	    
	    //拼接热点导航的数据
	    foreach ($channel_list as $val){
	    	//判断是默认还是扩展 1默认2扩展
	    	if($val['channel_type'] == 1){
	    		//月榜周榜是默认的时候
	    		if(in_array($val['relevance'], array('rankweek','rankmonth'))){
	    			$channel [] = array('name'    => $val['name'],
	    					'viewType'=> $api_config['channel'][$val['relevance']]['viewType'],
	    					'source'  => $api_config['channel'][$val['relevance']]['source'],
	    					'url'     => $api_config['channel'][$val['relevance']]['url'],
	    					'imageUrl'  => $attachroot.$val['icon_default']
	    			);
	    		}else{
	    			$channel [] = array('name'    => $val['name'],
	    					'viewType'=> $api_config['channel'][$val['relevance']]['viewType'],
	    					'source'  => $api_config['channel'][$val['relevance']]['source'],
	    					'imageUrl'  => $attachroot.$val['icon_default']
	    			);
	    		}
	    	}else{
	    		$channel [] = array('name'    => $val['name'],
	    				'viewType'=> $api_config[$val['relevance']][$val['link']]['viewType'],
	    				'source'  => $api_config[$val['relevance']][$val['link']]['source'],
	    				'url'  => $api_config[$val['relevance']][$val['link']]['url'],
	    				'imageUrl'  => $attachroot.$val['icon_default']
	    		);
	    	}
	    }
	    $data['data']['channelItems'] = $channel;
	
	    //拼接一级二级频道数据
	    foreach ($tmp as $val ){
	    	// 二级频道大于1个 
	    	if(count($val['items']) > 1){
	    		$channel_arr = array();
	    		foreach ($val['items'] as $va){
	    			//判断二级频道是扩展还是默认
	    			if($va['channel_type'] == 2){
	    				$viewtype = $va['relevance'];
	    			}else{
	    				$viewtype = $api_config['channel'][$va['relevance']]['viewType'];
	    			}
	    			//月榜周榜是默认的时候
	    			if(in_array($va['relevance'], array('rankweek','rankmonth'))){
	    				$channel_arr[] = array('title'=>$va['name'],
	    						'viewType'=>$viewtype,
	    						'source'=>$api_config['channel'][$va['relevance']]['source'],
	    						'url'=>$api_config['channel'][$va['relevance']]['url']
	    				);
	    			}elseif(in_array($va['relevance'], $ext_arr)){
	    				$channel_arr[] = array('title'=>$va['name'],
	    									   'viewType'=>$api_config[$va['relevance']][$va['link']]['viewType'],
	    						               'source'  =>$api_config[$va['relevance']][$va['link']]['source'],
	    						                'url'    =>$api_config[$va['relevance']][$va['link']]['url']
	    				);
	    			}else{
	    				$channel_arr[] = array('title'=>$va['name'],
	    						'viewType'=>$viewtype,
	    						'source'=>$api_config['channel'][$va['relevance']]['source']
	    				);
	    			}
	    			
	    		}
    			$data['data']['items'][] =array( 'title'=>$val['name'],
    					'source'=>$api_config['column'][$val['relevance']]['source'],
    					'normalIcon'=>$attachroot.$val['icon_default'],
    					'selectIcon'=>$attachroot.$val['icon_choose'],
    					'items'=>$channel_arr
    			) ;
    		// 二级频道等于1个
	    	}else{ 	    		
	    		if(count($val['items']) < 1){
	    			continue ;
	    		}	
    			if($val['items'][0]['channel_type'] == 2){
    				$view_type = $val['items'][0]['relevance'];
    			}else{
    				if(in_array( $api_config['channel'][$val['items'][0]['relevance']]['viewType'], array('RankView'))){
    					$view_type = $val['items'][0]['relevance'];
    				}else{
    				    $view_type = $api_config['channel'][$val['items'][0]['relevance']]['viewType'];
    				}
    			}
    			//月榜周榜是默认的时候
	    		if(in_array($view_type, array('rankweek','rankmonth'))){
	    				$data['data']['items'][] =array( 'title'=>$val['name'],
	    					'source'=>$api_config['channel'][$val['items'][0]['relevance']]['source'],
	    					'viewType' => $api_config['channel'][$val['items'][0]['relevance']]['viewType'],
	    					'url'=>$api_config['channel'][$val['items'][0]['relevance']]['url'],
	    					'normalIcon'=>$attachroot.$val['icon_default'],
	    					'selectIcon'=>$attachroot.$val['icon_choose']
	    			) ;
	    		}elseif(in_array($view_type, $ext_arr) ){
	    			$data['data']['items'][] =array( 'title'=>$val['name'],
	    					'source'=>$api_config[$val['items'][0]['relevance']][$val['items'][0]['link']]['source'],
	    					'viewType' => $api_config[$val['items'][0]['relevance']][$val['items'][0]['link']]['viewType'],
	    					'url'=>$api_config[$val['items'][0]['relevance']][$val['items'][0]['link']]['url'],
	    					'normalIcon'=>$attachroot.$val['icon_default'],
	    					'selectIcon'=>$attachroot.$val['icon_choose']
	    			) ;
	    		}else{
	    			$data['data']['items'][] =array( 'title'=>$val['name'],
	    					'source'=>$api_config['channel'][$val['items'][0]['relevance']]['source'],
	    					'viewType' => $view_type,
	    					'normalIcon'=>$attachroot.$val['icon_default'],
	    					'selectIcon'=>$attachroot.$val['icon_choose']
	    			) ;
	    		}
    					
	    	}
	     	
	    } 
	    echo json_encode($data);
	    exit;
	}

	
	/**
	 * 精选下导航接口
	 */
	public function chosenAction() {	
		$this->saveFirstPageBehaviour();
		$webroot = Common::getWebRoot();
		$version = intval($this->getInput('v'));
		$server_version = Game_Service_Config::getValue('Ad_Version');
	 	if ($version == $server_version){	//数据版本一致则不返回数据
			$this->output(0, '', array());
		} 
	
		list(, $ads) = Client_Service_Ad::getCanUseNormalAds(1, 12, array('ad_type'=>1, 'status'=>1, 'hits'=>1));
		$i = 1;
		foreach($ads as $key=>$value) {
			$info = Local_Service_IndexAd::cookClientAd($value, "ad1", $i++);
			$ads[$key] = $info;
		}
		$params = $tmp = $stmp = $temp = array();
	
		//轮播广告
		foreach($ads as $key=>$value){
			if($value['viewType'] && $value['ad_id']){
				$stmp[] = array(
						'viewType'=>$value['viewType'],
						'title'=>html_entity_decode($value['title']),
						'imageUrl'=>$value['img'],
						'param'=>$value['param'],
						'ad_id'=>$value['ad_id'],
				);
			}
			if(count($stmp) == 6) break;
		}
	
		//活动
		$params['ad_type'] = 9;
		list(, $announce) = Client_Service_Ad::getCanUseNormalAds(1, 8, $params);
		$j = 1;
		$ads = array();
		foreach($announce as $k=>$v) {
			$info = Local_Service_IndexAd::cookClientAd($v, "ad3", $j++);
			$ads[$k] = $info;
		}

		foreach($ads as $key=>$value){
			if($value['viewType'] && $value['ad_id']){
				$hdItem[] = array(
						'viewType'=>$value['viewType'],
						'title'=>html_entity_decode($value['title']),
						'imageUrl'=>$value['img'],
						'param'=> $value['param'],
						'ad_id'=>$value['ad_id'],
				);
			}
			if(count($hdItem) == 2) break;
		}
		
		//专区免流量活动是否有
		$tmp[] = array(
				'viewType'=>'FreeFlowAreaView',
				'title'=>'免流量专区',
				'imageUrl'=> Common::getAttachPath() . Game_Service_Config::getValue('game_free_img'),
				'param'=> array(
						'url'=>'',
						'contentId'=>'',
						'gameId'=>'',
				),
				'ad_id'=>'0000',
				//'source'=>'freeevent',
		);
		
		
		$temp['slideItems'] = $stmp;
		
		//活动广告必须有２个，否则不显示
		if(count($hdItem) == 2){
			$hdItem = array_merge($hdItem, $tmp);
			$temp['imageAdItems'] = $hdItem;
		}
		
		//v1.5.4活动文字公告
		list(, $textAnnounce) = Client_Service_Ad::getCanUseNormalAds(1, 1, array('ad_type'=>8));
		$textAnnounce = Local_Service_IndexAd::cookClientAd($textAnnounce[0], "ad3");
		if($textAnnounce['viewType']){
			$textItem[] = array(
					'viewType'=>$textAnnounce['viewType'],
					'title'=> "textAnnounce",
					'content'=>html_entity_decode($textAnnounce['title']),
					'param'=> $textAnnounce['param'],
					'source'=>'homeevent',
					'ad_id'=>$value['ad_id'],
			);
		}
		
		$temp['activityItem'] = $textItem ? $textItem : array();
		$data = $this->_IndexList(1);
		$adlist = json_encode(array(
				'success' => $data ? true : false ,
				'msg' => '',
				'sign' => 'GioneeGameHall',
				'data' => $data,
		));
		$temp['listData'] = $adlist;
		$this->localOutput('','',$temp);
	
	}

	
	public function wlanSetAction() {
		//客户端版本
		$version = $this->getInput('dataVersion');
		//最后编辑版本
		$curr_version = Game_Service_Config::getValue('game_set_uptime');
		if($version < $curr_version){
			$game_set_img = Game_Service_Config::getValue('game_set_img');
			$game_set_download = Game_Service_Config::getValue('game_set_download');
			$game_set_del = Game_Service_Config::getValue('game_set_del');
			$game_set_tips = Game_Service_Config::getValue('game_set_tips');
			$thirdPartyPush = Game_Service_Config::getValue('thirdPartyPush');
			$thirdPartyPushGn = Game_Service_Config::getValue('thirdPartyPushGn');
			$pushIntervalDays = Game_Service_Config::getValue('pushIntervalDays');
			$pushInvalidDays = Game_Service_Config::getValue('pushInvalidDays');
			$tmp = array(
					  'wifiShow'=> intval($game_set_img),
					  'wifiDownload'=> intval($game_set_download),
					  'cleanApk'=> intval($game_set_del),
					  'updateCycle'=> intval($game_set_tips),
					  'thirdPartyPush'=> intval($thirdPartyPush),
					  'thirdPartyPushInGn'=> intval($thirdPartyPushGn),
					  'pushIntervalDays'=> intval($pushIntervalDays),
					  'pushInvalidDays'=> intval($pushInvalidDays),
					);
			exit(json_encode(array(
						'success' => $tmp ? true : false ,
						'msg' => '',
						'sign' => 'GioneeGameHall',
						'dataVersion' => $curr_version,
						'data' => $tmp
			)));
		} else {
			return '';
		}
		
	}
	
	/**
	 * 客户端通用配置(v1.5.3)
	 * @return string
	 */
	public function ServerConfigAction() {
	    $this->saveServerConfigBehaviour();
		//客户端版本
		$version = $this->getInput('dataVersion');
		//最后编辑版本
		$curr_version = Game_Service_Config::getValue('game_set_uptime');
		if($version < $curr_version){
			$game_set_img = Game_Service_Config::getValue('game_set_img');
			$game_set_download = Game_Service_Config::getValue('game_set_download');
			$game_set_del = Game_Service_Config::getValue('game_set_del');
			$game_set_tips = Game_Service_Config::getValue('game_set_tips');
			$thirdPartyPush = Game_Service_Config::getValue('thirdPartyPush');
			$thirdPartyPushGn = Game_Service_Config::getValue('thirdPartyPushGn');
			$pushIntervalDays = Game_Service_Config::getValue('pushIntervalDays');
			$pushInvalidDays = Game_Service_Config::getValue('pushInvalidDays');
			
			//客户端ｖ1.5.3之后添加
			$flowReportSize = Game_Service_Config::getValue('flowReportSize');
			$phone = Game_Service_Config::getValue('game_service_tel');
			$qq = Game_Service_Config::getValue('game_service_qq');
			$game_service_worktime = Game_Service_Config::getValue('game_service_worktime');
			$upfreq = Game_Service_Config::getValue('game_client_upfreq');
			$gobackFilter = Game_Service_Config::getValue('game_client_gobackfilter');
			$game_client_deffilter = Game_Service_Config::getValue('game_client_deffilter');
			$newsSource = Game_Service_Config::getValue('news_source');
			$strategySource = Game_Service_Config::getValue('strategy_source');
			if($game_client_deffilter) {
				$gobackFilter = html_entity_decode($game_client_deffilter).'|'.html_entity_decode($gobackFilter);
			}
			
			$feedbackHasFAQ = Game_Service_Config::getValue('game_feedback_user_question');
			$feedbackContentHint = Game_Service_Config::getValue('game_feedback_input');
			$tmp = array(
					'wifiShow'=> intval($game_set_img),
					'wifiDownload'=> intval($game_set_download),
					'cleanApk'=> intval($game_set_del),
					'updateCycle'=> intval($game_set_tips),
					'thirdPartyPush'=> intval($thirdPartyPush),
					'thirdPartyPushInGn'=> intval($thirdPartyPushGn),
					'pushIntervalDays'=> intval($pushIntervalDays),
					'pushInvalidDays'=> intval($pushInvalidDays),
					'flowReportSize'=> intval($flowReportSize),
					'cuphone'=> $phone,
					'cuQQ'=> $qq,
					'cuWorkTime'=> $game_service_worktime,
					'selfUpgradeNotifyPeriod'=> $upfreq ? intval($upfreq) : 0,
					'gobackFilter'=> $gobackFilter ? $gobackFilter : '',
					'newsSource' => $newsSource,
					'strategySource' => $strategySource,
					'feedbackContentHint'=>$feedbackContentHint?html_entity_decode($feedbackContentHint):'',
					'feedbackHasFAQ'=>($feedbackHasFAQ == 1)?'true':'false'
			);
			exit(json_encode(array(
					'success' => $tmp  ? true : false ,
					'msg' => '',
					'sign' => 'GioneeGameHall',
					'dataVersion' => $curr_version,
					'data' => $tmp
			)));
		} else {
			return '';
		}
	
	}
	
private function saveServerConfigBehaviour() {
	    $imei = trim($this->getInput('imei'));
	    if (!$imei) {
	        $sp = $this->getInput('sp');
	        $imei = Common::parseSdkSp($sp, 'imei');
	    }
	    if (!$imei) {
	        return;
	    }
	    $clientPkg = trim($this->getInput('client_pkg'));
	    $behaviour = new Client_Service_ClientBehaviour($imei, $clientPkg);
	    $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_SERVER_CONFIG);
	}
}
	