<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

class GiftController extends Api_BaseController {
	
	private $mPageLimit = 10;
	private $mOnline = NULL;
	private $mGiftBrickItemLimit = 6;
	
	const DEAFULT_INTERSRC = 'olg_libao';
	
	
	/**
	 * 旧版本的礼包列表 
	 */
	public function indexAction() {
		
	    $info = $this->getInputParams ();
	    
		$this->saveGiftListBehaviour($info['imei']);
		
		list($total, $giftListData) = $this->getGiftListCacheData($info['page'], intval($info['gameId']));
		$outputData =  $this->getOutputData($info, $giftListData, $total);
		
		$data['success'] = 'true';
		$data['msg']     = '';
		$data['sign']    = 'GioneeGameHall';
		$data['data']    = $outputData;
		$this->clientOutput($data);
		
	}
	
	private function getGiftListCacheData($page, $gameId ){
	
		$dataVersion = Client_Service_Gift::getGiftListVersion ();
				
		$localCache = Cache_Factory::getCache(Cache_Factory::ID_LOCAL_APCU);
		$dataKeyName = Client_Service_Gift::getGiftListKeyName ($page, $gameId, $dataVersion);
		$giftListData = $localCache->get($dataKeyName);
		if($giftListData === false ){
			 $giftListData = $this->getGiftListFromDb ($page, $gameId);
			$localCache->set($dataKeyName,  $giftListData, Client_Service_Gift::GIFT_LIST_EXPIRE);
		}
		return  $giftListData;
	}
	

	
	private function getOutputData($info, $giftListData, $total) {	
		$webroot = Common::getWebRoot();
		$intersrc = ($info['intersrc']) ?  $info['intersrc']: self::DEAFULT_INTERSRC;
		//1.5.0 账号加入
		$this->mOnline = Account_Service_User::checkOnline($info['uname'], $info['imei']);
		$giftData = array();
		foreach($giftListData as $key=>$value) {	
			$isGrabGift = $this->isGrabGift ($info, $value['id']);
			//剩下的激活码数量
			$leftNum = Client_Service_Gift::getGiftRemainNum($value['id']);
			//总激活码数量
			$totalNum = Client_Service_Gift::getGiftTotal($value['id']);
			//已使用激活的数量
			$grabNum = $totalNum - $leftNum;
			$gameInfo = Resource_Service_GameData::getBasicInfo($value['game_id']);
			$href = urldecode($webroot."/client/gift/detail/". '?id=' . $value['game_id'].'&pc=2&intersrc=' . $intersrc . $value['id'] . '&t_bi='.$this->getSource());
			$giftData[] = array('title'=>$value['name'],
					            'isGrab'=>$isGrabGift?'true':'false',
								'data-type'=>2,
					            'viewType'=>'GiftDetailView',
					            'img'=>urldecode($gameInfo['img']),
					            'data-infpage'=>$value['name'].','.$href.','.$value['game_id'].','.$value['id'],
					            'id'=>$value['id'],
					            'game_id'=>$value['game_id'],
					            'totalNum'=>$totalNum,
					            'leftNum'=>$leftNum,
					            'grabNum'=>$grabNum
					);
		}
		$hasnext      = Client_Service_Gift::PAGE_LIMIT * $info['page'] <  $total ? true : false;
		$returnData   = array('list'    => $giftData,
				        	  'hasnext' => $hasnext,
				              'curpage' => intval($info['page'])
				             );
		return $returnData;
	
	}
	
	private function getInputParams() {
		$info = $this->getInput(array('page',
				'intersrc',
				'id',
				'isDetail',
				'uname',
				'sp'));
		if (intval($info['page'])  < 1){
			$info['page']  = 1;
		}
		$spArr = Common::parseSp($info['sp']);
		$info['imei'] = $spArr['imei'];
		$info['clientVersion'] = $spArr['game_ver'];
		
		$info['gameId'] = 0;
		if($info['isDetail']){
			$info['gameId'] = $info['id'];
		}		
		return $info;
	}

	
	private function getGiftListFromDb($page, $gameId) {
		//礼包列表
		$parmas['status'] = Client_Service_Gift::GIFT_STATE_OPENED ;
		$parmas['game_status'] = Client_Service_Gift::GAME_STATE_ONLINE;
        $currTime = Util_TimeConvert::floor(Common::getTime(), 
        		                            Util_TimeConvert::RADIX_DAY);
		$parmas['effect_start_time'] = array('<=', $currTime);
		$parmas['effect_end_time']   = array('>=', $currTime);
		if($gameId){
			$parmas['game_id'] = $gameId;
		} 	
		$orderBy = array('game_sort'=>'DESC', 
				         'game_id'=>'DESC',
				         'sort'=>'DESC',
				         'effect_start_time' => 'DESC',
				         'id' => 'DESC');

		list($total, $giftsList) = Client_Service_Gift::getList($page, 
				                                                Client_Service_Gift::PAGE_LIMIT, 
				                                                $parmas, 
				                                                $orderBy);
		return array($total, $giftsList);
	}

	
	private function isGrabGift($info, $giftId ) {
		//1.5.1 之前兼容
		if( (strnatcmp($info['clientVersion'], '1.5.0')  <  0) 
				&& ($info['imei'] && ($info['imei'] != Util_Imei::EMPTY_IMEI) )){
			$imeicrc  = crc32($info['imei']);
			$giftData = self::getMyGiftIdFromCache($imeicrc, $giftId, $type='imei');
		} else {
			//1.5.0 账号加入
			if($this->mOnline) {
				$giftData = self::getMyGiftIdFromCache($info['uname'], $giftId, $type='uname');
			}
		}	
		
		if(!is_array($giftData)){
			return false;
		}
		return in_array($giftId, $giftData) ? true : false ;
	}
	
	/**
	 * 获取用户的礼包id
	 */
	private function getMyGiftIdFromCache($user, $giftId, $type='uname'){
		if(!$user || !$giftId){
			return array();
		}		
		$keyName = $this->getMyGiftIdKeyName($user);
		$cache = Cache_Factory::getCache();
		$returnData  = $cache->get($keyName);
		if(in_array($giftId, $returnData)){
			return $returnData;
		}
		if($this->getMyGiftLog($user, $giftId, $type)){
			//首次没有缓存数据
			if($returnData === false){
				$returnData = array($giftId);
			}else{				
				array_push($returnData, $giftId);
			}			
			$cache->set($keyName, $returnData, Client_Service_Giftlog::KEY_MY_GIFT_ID_EXPRIE);		
		}	
		return $returnData;
	}
	
	/**
	 * 更新礼包ID到缓存
	 */
	private function updateGiftIdToCache($user, $giftId){
		if(!$user || !$giftId){
			return ;
		}
		
		$keyName = $this->getMyGiftIdKeyName($user);
		$cache = Cache_Factory::getCache();
		$giftIdData  = $cache->get($keyName);
		if(in_array($giftId, $giftIdData)){
			return ;
		}
		if($giftIdData === false){
			$giftIdData = array($giftId);
		}else{
			 array_push($giftIdData, $giftId);
		}
		$cache->set($keyName, $giftIdData, Client_Service_Giftlog::KEY_MY_GIFT_ID_EXPRIE);
		
	}
	
	/**
	 * 我的礼包日志
	 * @param unknown_type $user
	 * @param unknown_type $giftId
	 * @param unknown_type $type
	 * @return boolean
	 */
	private function getMyGiftLog($user, $giftId, $type){
		if($type == 'uname'){
			$logs = Client_Service_Giftlog::getByUnameGiftId($user, $giftId);
		}else{
			$logs = Client_Service_Giftlog::getByImeiGiftId($user, $giftId);
		}		
		return $logs?true:false;
	}
	
	
	public function getMyGiftIdKeyName($data) {
		if(!$data) {
			return ;
		}
		$api = Util_CacheKey::getApi(Util_CacheKey::GIFT, Util_CacheKey::MY_GIFT_ID);
		if(!is_array($api)){
			return ;
		}
		$keyName = $api[Util_CacheKey::CLASS_NAME] . '::' . $api[Util_CacheKey::METHOD_NAME];
		$keyName = $keyName.'_'.$data;
		return $keyName;
	}

	
	private function saveGiftListBehaviour($imei) {
	    if (!$imei) {
	        return;
	    }
	    $clientPkg = trim($this->getInput('client_pkg'));
	    $behaviour = new Client_Service_ClientBehaviour($imei, $clientPkg);
	    $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_GIFT_LIST);
	}
	
    /**
     * 我的礼包列表
     */
    public function mygiftAction() {
    	$imei = $this->getInput('imei');
    	$uname = $this->getInput('uname');
    	$version = $this->getInput('version');
    	if(!$imei) $this->clientOutput(array());
    	$imeicrc = crc32(trim($imei));
    	$webroot = Common::getWebRoot();
	   	//imei为空 并且版本 小于等于1.5.0 特殊处理
    	if((strnatcmp($version, '1.5.0') < 0) && ($imei == 'FD34645D0CF3A18C9FC4E2C49F11C510')){
    		$this->clientOutput(array());
    	} 
    	
    	//1.5.0 通过账号获取礼包信息
    	if($uname) {
    		//不在线处理
    		$online = Account_Service_User::checkOnline($uname, $imei);
    		if(!$online) $this->clientOutput(array('sign'=>'GioneeGameHall', 'code'=>'0'));
    	}
    	list(,$logs) = Client_Service_Giftlog::getListLogs($uname, $imeicrc, '', $version);
    	
    	
	    //判断是否有数据
	    if(!$logs) $this->clientOutput(array());
    	$tmp = array();
    	$tmp['sign'] = 'GioneeGameHall';
    	if($uname) $tmp['uname'] = $uname;
    	
    	foreach($logs as $key=>$value){
    		    $giftInfo = Client_Service_Gift::getGiftBaseInfo($value['gift_id']);
	    		$gameInfo = Resource_Service_GameData::getBasicInfo($value['game_id']);
	    		$detail = $downinfo = array();
	    		$detail = array(
	    				'title' => "礼包详情",
	    				'viewType' => "GiftDetailView",
	    				'gameId' =>  $value['game_id'],
	    				'url' => sprintf("%s/client/gift/detail/?gift_id=%d&pc=2&intersrc=%s&t_bi=%d", $webroot, $value['gift_id'], 'libao_my'.$value['gift_id'], self::getSource()),
	    				'giftId' => $value['gift_id'],
	    				'param' => array(
	    						   'contentId'=>$value['gift_id'],
	    						   'gameId'=>$value['game_id'],
	    						),
	    		);
	    		$temp[] =  array(
	    				'iconUrl' => $gameInfo['img'] ? $gameInfo['img'] : $this->getOffLineGameIcon($value['game_id']),
	    				'giftName' => html_entity_decode($giftInfo['name'], ENT_QUOTES),
	    				'giftLimitTime' => date('Y-m-d', $giftInfo['use_start_time'])."至".date('Y-m-d', $giftInfo['use_end_time']),
	    				'giftKey' => $value['activation_code'],
	    				'giftDetail' => $detail
	    		);
    	}
    	$tmp['data'] = $temp;
	    $this->clientOutput($tmp);
    }
    
    /**
     * 1.5.6我的礼包列表接口
     */
    public function myGiftListAction(){
    	
    	$imei = trim($this->getInput('imei'));
    	$uname = $this->getInput('uname');
    	$version = $this->getInput('version');
    	$page = $this->getInput('page');
    	
    	if(!$uname){
    		$this->clientOutput(array());
    	} 
    	
    	if(!$imei){
    		$this->clientOutput(array());
    	} 
    	
    	if ($page < 1) $page = 1;
    	
    	$online = Account_Service_User::checkOnline($uname, $imei);
    	if (!$online){
    		$data['code'] = '0';
    		$data['success'] = '-1';
    		$data['msg'] = '用户登录过期';
    		$data['sign'] = 'GioneeGameHall';
    		$data['data'] = array();
    		$this->clientOutput($data);
    	} 

    	$this->organizeMyList ( $uname, $imei, $page, $version);

    }
    
    
    /**
     * 礼包详情
     */
	/**
	 * @param uname
	 * @param page
	 * @param params
	 * @param data
	 */
	 private function organizeMyList($uname, $imei, $page, $version) {
		$params['uname'] = $uname;
		$imeicrc = crc32($imei);
    	list($total, $giftList)  = Client_Service_Giftlog::getListLogs($uname, $imeicrc, $page, $version);
    	$hasNext = (ceil((int) $total / $this->mPageLimit) - $page) > 0 ? true : false;
    	$temp = array();
    	$data['success'] = true;
    	$data['sign'] = 'GioneeGameHall';
    	foreach ($giftList as $value){
    		list($giftInfo, $giftName, $gainType) = $this->getGiftListInfo($value);
    		$gameInfo = Resource_Service_GameData::getBasicInfo($value['game_id']);
    		$temp['list'][]=array(
    				'iconUrl'=> $gameInfo['img'] ? $gameInfo['img'] : $this->getOffLineGameIcon($value['game_id']),
    		        'gainType'=>$gainType,
    				'giftName'=>$giftName,
    				'giftStartTime'=>$giftInfo['use_start_time'],
    				'giftEndTime'=>$giftInfo['use_end_time'],
    				'giftKey'=>$value['activation_code'],
    				'giftId'=>$value['gift_id'],
    				'gameId'=>$value['game_id']
    		);
    	}
    	$data['data'] = $temp;
    	$data['hasNext'] = $hasNext ;
    	$data['curPage'] = intval($page) ;
    	$data['totalCount'] = intval($total) ;
    	$this->clientOutput($data);
	}
	
	private function getOffLineGameIcon($gameId) {
	   return Resource_Service_GameData::getOffLineGameIcon($gameId);
	}
	
	public function getGiftListInfo($myGiftLog) {
	    if($myGiftLog['log_type'] == Client_Service_Giftlog::GRAB_GIFT_LOG){
	        $giftInfo = Client_Service_Gift::getGiftBaseInfo($myGiftLog['gift_id']);
	        $giftName = html_entity_decode( $giftInfo['name'], ENT_QUOTES);
	        $gainType = 'grab';
	    } else {
	        $giftInfo = Client_Service_GiftActivity::getGiftBaseInfo($myGiftLog['gift_id']);
	        $giftName = html_entity_decode( $giftInfo['title'], ENT_QUOTES);
	        $gainType = 'activitySend';
	    }
	    return array($giftInfo, $giftName, $gainType);
	}

    
    public function detailAction() {
    	$data  = $this->getInput(array('gift_id', 'imei', 'uname','version', 'client_pkg', 'gainType'));
    	$giftId =  intval(trim($data['gift_id']));
    	$imeicrc =  crc32(trim($data['imei']));
    	$gainType = $data['gainType'];
    	$imei = trim($data['imei']);
    	$uname = trim($data['uname']);
    	$version = $data['version'];
    	
    	if(!$imei){
    		$this->clientOutput(array());
    	}
		$behaviour = new Client_Service_ClientBehaviour($imei, $data['client_pkg']);
        $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_GET_GIFT_DETAIL);

        if ($gainType == 'activitySend') {
           $info = Client_Service_GiftActivity::getGiftBaseInfo($giftId);
           $giftName = $info['title'];
           $log = Client_Service_GiftActivityLog::getBy(array('uname'=>$uname,'gift_id'=>$giftId));
        } else {
        	$info = Client_Service_Gift::getGift($giftId);
        	$giftName = $info['name'];
        	
        	//礼包领取记录
        	$log = '';
        	//1.5.0以下特殊处理
        	if(strnatcmp($version, '1.5.0') < 0){
        		if ($imei && $imei != 'FD34645D0CF3A18C9FC4E2C49F11C510') {
        			$log = Client_Service_Giftlog::getByImeiGiftId($imeicrc, $giftId);
        		} else {
        			$log = '';
        		}
        	}
        	//1.5.0 通过账号获取礼包信息
        	$online = false;
        	if($uname) {
        		$online = Account_Service_User::checkOnline($uname, $imei);
        	}
        	if($uname) {
        		//更新指定imei下空账号信息
        		if($online) $log = Client_Service_Giftlog::getByUnameGiftId($uname, $giftId);
        	}
        	 
        	//剩下的激活码数量
        	$giftRemainNum = Client_Service_Gift::getGiftRemainNum($giftId);
        	//总激活码数量
        	$giftTotal = Client_Service_Gift::getGiftTotal($giftId);
        }
        
    	//获取游戏信息
    	$gameInfo = Resource_Service_GameData::getGameAllInfo($info['game_id']);
    	
    	$tmp = array();
    	$tmp['sign'] = 'GioneeGameHall';
    	$tmp['iconUrl'] = $gameInfo['img'] ? $gameInfo['img'] : $this->getOffLineGameIcon($info['game_id']);
    	$tmp['title'] = "礼包详情";
    	$tmp['giftName'] = html_entity_decode($giftName, ENT_QUOTES);
    	$tmp['isGrab'] = ($log ? "true" : "false");
    	$tmp['giftNum'] = ($info['status'] ? ($giftRemainNum."/".$giftTotal) : "0/0");
    	$tmp['giftKey'] = ($log ? $log['activation_code'] : "");
    	
    	$temp = array();
    	$temp[] = array (
    				'title'=>"礼包内容",
    				'content' => Common::replaceHtmlAndJs(html_entity_decode($info['content'], ENT_QUOTES))
    	);
    	$temp[] = array (
    				'title'=>"使用时间",
    				'content' => date("Y年m月d日 H:i:s",$info['use_start_time'])."-".date("Y年m月d日 H:i:s",$info['use_end_time'])
    	);
    	$temp[] = array (
    				'title'=>"使用方法",
    				'content' => Common::replaceHtmlAndJs(html_entity_decode($info['method'], ENT_QUOTES))
    	);
    	$tmp['data'] = $temp;
    	
    	$tmp['giftBrickData'] = $this->makeGiftBrickData($giftId, $gameInfo);

    	$this->clientOutput($tmp);
    }

    private function makeGiftBrickData($giftId, $gameInfo) {
        $data = array();
        $data['title'] = '相关游戏礼包';
        $data['giftItems'] = array();
        if(!$gameInfo){
            return $data;
        }
        
    	$pageIndex = 1;
    	list($total, $giftListData) = $this->getGiftListCacheData($pageIndex, intval($gameInfo['id']));	
    	$count = 0;
    	foreach ($giftListData as $gift) {
    		if($count == $this->mGiftBrickItemLimit) {
    			break;
    		}
    		
    		if ($giftId == $gift['id']) {
    			continue;
    		}
    		
    		$data['giftItems'][] = array('gameId'=>$gift['game_id'],
    		                             'gameName'=>html_entity_decode($gameInfo['name'], ENT_QUOTES),
    		                             'iconUrl'=>$gameInfo['img'],
    		                             'giftId'=>$gift['id'], 
    		                             'giftName'=>html_entity_decode($gift['name'], ENT_QUOTES));
    		$count++;
    	}
    	
    	$data['total'] = $count;
    	return $data;
    }
    
	private function debugGrab($inputData, $reason, $fileName = '_gift_grab.log') {
        $grabLogStatus = Client_Service_Gift::getGrabGiftLogSwitch();
        if($grabLogStatus){
        	$imeiDecrypt = Util_Imei::decryptImei($inputData['imei']);
        	$debugMsg = $inputData;
        	$debugMsg['imeiDecrypt'] = $imeiDecrypt;
        	$debugMsg['reason'] = $reason;
            Common::log($debugMsg, date('Y-m-d'). $fileName);
        }
	}
    
    /**
     * 抢礼包
     */
    public function grabAction() {
    	$data  = $this->getInput(array('gift_id',
										'imei',
										'uname',
										'version',
										'client_pkg',
										'sp',
										'puuid',
										'clientId',
										'serverId'));
    	$data['gift_id'] = intval($data['gift_id']);
    	$imeicrc = crc32(trim($data['imei']));
    	$data['imei'] = trim($data['imei']);
    	$imei = trim($data['imei']);
    	$uname = trim($data['uname']);
    	$version = $data['version'];
    	
    	Util_Log::info(__CLASS__, 'grab.log', $data);
        //非法判断
    	$tmp = array('sign'=>'GioneeGameHall', 
    			             'giftKey'=>'', 
    			             'giftNum'=>'0/0');
        $this->checkRequestParams($data, $tmp);
        Util_Log::info(__CLASS__, 'grab.log', array('请求验证完毕'));

    	//有账号未登录直接退出 1.5.0之前没有账号
    	if($uname){
    		$online = Account_Service_User::checkOnline($uname, $imei);
    		if(!$online) {
    		    $outputData = array('sign'=>'GioneeGameHall', 'code'=>'0');
    		    $this->setOutPutMsg($outputData, '$online = false');
    		    $this->clientOutput($outputData);
    		}
    	}
    	
        if (!$this->isRealUser($imei, $version, $data['client_pkg'])) {
			$this->debugGrab($data, 'is not a real user');
			if (Util_Environment::isOnline()) {
                $this->outputPhonyKey($data['uname'], $data['gift_id'], 'isRealUser : false');
			} else {
                $this->setOutPutMsg($tmp, 'isRealUser : false');
                $this->clientOutput($tmp);
			}
        }
        Util_Log::info(__CLASS__, 'grab.log', array('用户行为验证完毕'));

    	$time = Common::getTime();
    	//判断是否上线
    	$giftInfo = Client_Service_Gift::getGiftBaseInfo($data['gift_id']);
    	if (!$giftInfo['status']) {
			$this->debugGrab($data, ' status offline');
    	    $this->setOutPutMsg($tmp, 'giftInfo[status] : false');
    	    $this->clientOutput($tmp);
    	}
    	if ($giftInfo['effect_start_time'] > $time) {
    	    $this->setOutPutMsg($tmp, 'giftInfo[effect_start_time] > time');
    	    $this->clientOutput($tmp);
    	}
    	if ($giftInfo['effect_end_time'] < $time) {
    	    $this->setOutPutMsg($tmp, 'giftInfo[effect_end_time] < time');
    	    $this->clientOutput($tmp); 
    	}
    	if (!$giftInfo['game_status']) {
			$this->debugGrab($data, ' game offline');
    	    $this->setOutPutMsg($tmp, 'giftInfo[game_status] : false');
    	    $this->clientOutput($tmp);
    	}

    	//剩下的激活码数量
    	$remainGiftNum = Client_Service_Gift::getGiftRemainNum($data['gift_id']);
    	//总激活码数量
    	$giftTotal = Client_Service_Gift::getGiftTotal($data['gift_id']);
    
    	Util_Log::info(__CLASS__, 'grab.log', array('剩余激活码:'.$remainGiftNum, '总激活码：'.$giftTotal));
    	//没有礼包激活码
    	if(intval($remainGiftNum) <= 0 ) { 	
    		$tmp['giftKey'] = '';
    		$tmp['giftNum'] = '0/'.($giftTotal);
			$this->debugGrab($data, ' no gift code');
    		$this->setOutPutMsg($tmp, 'no gift code!');
    		$this->clientOutput($tmp);
    	}
    	
    	//大于1.5.0 通过账号获取礼包信息
    	if($uname) {
    		$tmp['uname'] = $uname;
    		//更新指定imei下空账号信息
    		if($imei) Client_Service_Giftlog::updateByGiftLog(array('uname' => $uname), array('uname'=>'', 'imei'=>$imei,'log_type'=>Client_Service_Giftlog::GRAB_GIFT_LOG));
    		$logs = Client_Service_Giftlog::getByUnameGiftId($uname, $data['gift_id']);
    	}else{
    		//礼包已经领取，直接返回领取信息
    		$logs = Client_Service_Giftlog::getByImeiGiftId($imeicrc, $data['gift_id']);
    	}	
    	//用户抢过此礼包
    	if ($logs) {
    		$tmp['giftKey'] = $logs['activation_code'];
    		$tmp['giftNum'] = $remainGiftNum.'/'.($giftTotal);
			$this->debugGrab($data, ' user has this gift');
    		$this->setOutPutMsg($tmp, 'user has this gift!');
    		$this->clientOutput($tmp);
    	}
    	
    
    	//抢礼包开始,获取激活码
    	$giftAcitivityCode = Client_Service_Giftlog::getBy(array('status'=>0,'gift_id'=>$data['gift_id'],'log_type'=>Client_Service_Giftlog::GRAB_GIFT_LOG),array('send_order'=>'ASC','id'=>'ASC'));	
    	if(!$giftAcitivityCode['activation_code']){
    		$tmp['giftKey'] = '';
    		$tmp['giftNum'] = $remainGiftNum."/".($giftTotal);
			$this->debugGrab($data, ' this gift has no code');
    		$this->setOutPutMsg($tmp, 'giftAcitivityCode[activation_code] : false');
    		$this->clientOutput($tmp);
    	}
    
    	$lockKey = 'lock::giftAcitivityCode::'.$data['gift_id'];
    	if(!Client_Service_Gift::lock($lockKey)){
    		$this->clientOutput($tmp);
    	}
    	
    	Util_Log::info(__CLASS__, 'grab.log',$giftAcitivityCode);
    	$updata = array('uname' => $uname ? $uname : '',
    			       'imei'=>$imei, 
    			       'imeicrc'=>$imeicrc,
    			       'create_time'=>$time,
    			       'status'=>1);
    	$ret = Client_Service_Giftlog::updateGiftlog($updata, $giftAcitivityCode['id']);
    	//加入并发锁定，防止并非抢同一个礼包
    	Client_Service_Gift::unlock($lockKey);
    	if($ret){
    		$remainGiftNum = Client_Service_Gift::reduceRemainActivitionCodeCache($data['gift_id'],
    				                                                              $remainGiftNum);
    		if($remainGiftNum == 0){
    			$this->giftEmaiToQueue($giftInfo, 0, '礼包零预警');
				Client_Service_Gift::updateGiftStatus(array($data['gift_id']),
						Client_Service_Gift::GIFT_STATE_CLOSEED);
                //刷新游戏礼包附加属性
                $this->refreshCache($giftInfo['game_id']);
            }
    		if($uname){
    			$this->updateGiftIdToCache($uname, $data['gift_id']);
    		}else{
    			$this->updateGiftIdToCache($imeicrc, $data['gift_id']);
    		}

    	}
    	$tmp['giftKey'] = ($ret ? $giftAcitivityCode['activation_code']: '');
    	$tmp['giftNum'] =  $remainGiftNum."/".($giftTotal);
    	
       //预警提示,多个邮箱有逗号隔开
    	$temp_arr = array();
    	$giftNum = Game_Service_Config::getValue('game_gift_num');
    	$num =  intval($giftTotal*($giftNum/100));
    	if($giftNum && $remainGiftNum == intval($num) ){
    		$this->giftEmaiToQueue($giftInfo, $remainGiftNum, '剩余礼包比例预警');
    	}
    	Util_Log::info(__CLASS__, 'grab.log',$tmp);
    	$this->clientOutput($tmp);
    }
    
    private function setOutPutMsg(&$outPutData, $msg) {
        if(Util_Environment::isOnline()) {
            return;
        }
        
        $outPutData['msg'] = $msg;
    }
    
    private function isWithGiftFeature($version, $uname, $clientPkg) {
        if (Client_Service_ClientBehaviour::CLIENT_SDK == $clientPkg) {
        	return true;
        }
        
        $version = substr($version, 0, 5);
        $versionList = $this->getVersionList($clientPkg);
        if ($uname && Client_Service_ClientBehaviour::CLIENT_HALL == $clientPkg) {
            if (strnatcmp('1.5.0', $version) >= 0) {
                return false;
            }
        }
        if (in_array($version, $versionList)) {
            return true;
        } else {
            return false;
        }
    }
    
    private function getVersionList($clientPkg) {
        if(Client_Service_ClientBehaviour::CLIENT_SDK == $clientPkg) {
            return array(
                         '3.0.1',
                         '3.0.2'
                        );
        } else if(Client_Service_ClientBehaviour::CLIENT_HALL == $clientPkg) {
            return array(
                         '1.5.0',
                         '1.5.1',
                         '1.5.2',
                         '1.5.4',
                         '1.5.5',
                         '1.5.6',
                         '1.5.7',
                         '1.5.8',
                         '1.5.9',
                         '1.6.0'
                         );
        } else if(!$clientPkg){
            return array(
                         '1.4.8',
                         '1.4.9',
						 '1.5.0'
            );
        }
        return array();
    }

    private function checkRequestParams($data, $error){
        $imei = $data['imei'];
        $version = $data['version'];
        $uname = $data['uname'];
        
        if(!$imei || !$data){
			$this->debugGrab($data, ' imei not inputed');
        	$this->setOutPutMsg($error, 'imei not inputed');
        	$this->clientOutput($error);
        }

        if(!$this->isWithGiftFeature($version, $uname, $data['client_pkg'])) {
			$this->debugGrab($data, '  isWithGiftFeature false');
            $this->outputPhonyKey($data['uname'], $data['gift_id'], 'isWithGiftFeature false');
        }
        
        //sp parmes start from 1.4.9
        if((strnatcmp($version, '1.4.9') > 0)){
	        if(!$data['sp']) {
			    $this->debugGrab($data, 'no sp');
	            $this->setOutPutMsg($error, 'no sp param!');
	            $this->clientOutput($error);
	        }
	
	        $sp = Common::parseSp($data['sp']);
	        $spVersion = $sp['game_ver'];
	        $spImei = $sp['imei'];
	
	        if($spImei != $imei || $spVersion != $version){
			    $this->debugGrab($data, 'check imei and version failed');
	            $this->setOutPutMsg($error, 'imei or version is differ from sp!');
	            $this->clientOutput($error);
	        }
        }
        
        $imeiDecrypt = null;
        if ($imei != Util_Imei::EMPTY_IMEI) {
            $imeiDecrypt = Util_Imei::decryptImei($imei);
            if (!Util_Imei::isValidDeviceId($imeiDecrypt)) {
			    $this->debugGrab($data, ' invalid imei');
                $this->outputPhonyKey($data['uname'], $data['gift_id'], 'imei invalid');
            }
        } else if (strnatcmp($version, '1.5.0') < 0){
        	$this->setOutPutMsg($error, 'version < 1.5.0 and imei is empty');
        	$this->clientOutput($error);
        }
        
        if((strnatcmp($version, '1.5.7') > 0)){
        	if(!$imeiDecrypt){
        	    $imeiDecrypt = Util_Imei::decryptImei($imei);
        	}
        	
        	$verifyInfo = $data;
        	$verifyInfo['imei'] = $imeiDecrypt;
        	$pass = $this->verifyEncryServerId($verifyInfo);
        	if(!$pass){
			    $this->debugGrab($data, '  check serverId failed');
        		$this->outputPhonyKey($data['uname'], $data['gift_id'], 'ServerId invalid');
        	}
        }

        if ($this->isRobber($imei)) {
			$this->debugGrab($data, '  is a robber');
            $this->setOutPutMsg($error, 'isRobber : true');
            $this->clientOutput($error);
        }
    }
 
    private function outputPhonyKey($uname, $giftId, $msg) {
        $phony['sign'] = 'GioneeGameHall';
        $phony['giftKey'] = $this->genPhonyKey($giftId);

        $remainKey = Client_Service_Gift::getGiftRemainNum($giftId);
        $remainKey = abs($remainKey - rand(1, 10));
        $totalKey = Client_Service_Gift::getGiftTotal($giftId);

        $phony['giftNum'] = $remainKey . '/' . $totalKey;
        $phony['uname'] = $uname;
        $this->setOutPutMsg($phony, $msg);
        $this->clientOutput($phony);
    }

    private function genPhonyKey($giftId) {
        $queryFreeLog['gift_id'] = $giftId;
        $queryFreeLog['log_type'] = Client_Service_Giftlog::GRAB_GIFT_LOG;
        $log = Client_Service_Giftlog::getBy($queryFreeLog, array('id'=>'ASC'));
        $log2 = Client_Service_Giftlog::getBy($queryFreeLog, array('id'=>'DESC'));

        $keyPrefix = $this->findStrPrefix($log['activation_code'], $log2['activation_code']);

        $prefixLen = strlen($keyPrefix);
        if ($prefixLen > 0) {
            $keyStr = $log['activation_code'];
            $realKey = substr($keyStr, $prefixLen, strlen($keyStr) - $prefixLen);
        } else {
            $realKey = $log['activation_code'];
        }

        $length = strlen($realKey);

        $max = strlen($realKey) - 1;

        $str = $keyPrefix;
        for($i = 0; $i < $length; $i++){
            $str .= $realKey[rand(0, $max)];
        }

        $query['gift_id'] = $giftId;
        $query['log_type'] = Client_Service_Giftlog::GRAB_GIFT_LOG;
        $query['activation_code'] = $str;
        $log = Client_Service_Giftlog::getBy($query);
        if ($log) {
            $str = '';
        }
        return $str;
    }

    private function findStrPrefix($str1, $str2) {
        $prefix = '';
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        for ($i = 0; $i < $len1; $i++) {
            if ($i < $len2 && $str1[$i] == $str2[$i]) {
                $prefix .= $str1[$i];
            } else {
                break;
            }
        }

        return $prefix;
    }

    private function _spAnalyze($sp){
    	return explode('_',$sp);
    }
    
    private function decryptImei($imei){
    	$cryptAES = new Util_CryptAES();
  
    	$decryptImei = $cryptAES->decrypt($imei);
    	
    	if(false === $decryptImei) {
    		return null;
    	}

    	if(!preg_match("/^[0-9]{15}$/i", $decryptImei)) {
    		return null;
    	}
    	
    	return $decryptImei;
    }

    private function verifyEncryServerId($verifyInfo) {
    	$keyParam = array(
    			'apiName' => strtoupper('grab'),
    			'imei' => $verifyInfo['imei'],
    			'uname' => $verifyInfo['uname'],
    	);
    	$ivParam = $verifyInfo['puuid'];
    	$serverId = $verifyInfo['serverId'];
    	$serverIdParam = array(
    			'clientVersion' => $verifyInfo['version'],
    			'imei' => $verifyInfo['imei'],
    			'uname' => $verifyInfo['uname'],
    	);
    	return Util_Inspector::verifyServerId($keyParam, $ivParam, $serverId, $serverIdParam);
    }
    
    private function isRobber($imei) {
        $search = array();

        $currHour = Util_TimeConvert::floor(Common::getTime(), Util_TimeConvert::RADIX_HOUR);
        $yestoday = $currHour - Util_TimeConvert::SECOND_OF_DAY;
        $search['create_time'] = array('>', $yestoday);
        $search['imeicrc'] = crc32($imei);
        $search['log_type'] = Client_Service_Giftlog::GRAB_GIFT_LOG;
        list($total, $giftLogs) = Client_Service_Giftlog::getList(1, 15, $search);

        if (empty($giftLogs)) {
            return false;
        }

        $unameList = array();
        foreach ($giftLogs as $key => $value) {
            if ($imei == $value['imei']) {
                $unameList[] = $value['uname'];
            }
        }

        $unameCount = count(array_unique($unameList));
        if ($unameCount >= 5) {
            return true;
        }

        return false;
    }

    private function isRealUser($imei, $version, $clientPkg) {
        $behaviour = new Client_Service_ClientBehaviour($imei, $clientPkg);
        return $behaviour->isRealUser($version);
    }

    /**
     * 把要发邮件的礼包警告信息写入信息队列中
     */
    public function giftEmaiToQueue($giftInfo, $remainGiftNum, $emailSubject){
    	$email = html_entity_decode(Game_Service_Config::getValue('game_gift_eamil'));
    	if($email){
    		$gameInfo = Resource_Service_GameData::getGameAllInfo($giftInfo['game_id']);
    		$temp_arr = array('gift_id'=>$giftInfo['id'],'game_id'=>$giftInfo['game_id'],'game_name'=>$gameInfo['name'],'gift_name'=>$giftInfo['name'],'remain_gifts'=>$remainGiftNum,'email'=>$email, 'emailSubject'=>$emailSubject);
    		Common::getQueue()->push('game_gift',$temp_arr);
    	}
    }
    
    
    /**
     * 游戏详情页
     */
    public function gameinfoAction() {
    	$game = $tmp  = array();
    	$game_id  = $this->getInput('game_id');
    	$from  = $this->getInput('from');
    	//v1.6.0 add
    	$activityId  = intval($this->getInput('activityId'));
    	//v1.5.4 add
    	$gameName  = $this->getInput('gameName');
    	if($from == "gn" || !$from){
    	    $game = $this->getGameInfo($gameName, $game_id);
    	} else if($from == "baidu"){
    		$baiduApi = new Api_Baidu_Game();
    		$game = $baiduApi->getInfo($game_id, $from);
    	}
    	
    	if(($game['status'] && ($from == "gn" || !$from)) || $from == "baidu"){
    		$tmp['sign'] = 'GioneeGameHall';
    		$tmp['gameId'] = $game['id'];
    		$tmp['gameName'] = html_entity_decode($game['name']);
    		$tmp['downUrl'] = $game['link'];
    		$tmp['packageName'] = $game['package'];
    		$tmp['fileSize'] = $game['size'];
    		$tmp['iconUrl'] = $game['img'];
    		$tmp['sdkVersion'] = 'Android'.(($from == "gn" || !$from) ? $game['min_sys_version_title']: $game['apply_version']);
    		$resolution = (($from == "gn" || !$from) ? ($game['min_resolution_title']."-".$game['max_resolution_title']) : ($game['min_resolution']."-".$game['max_resolution'])); 
    		$tmp['resolution'] = $resolution;
    		//客户端 v1.5.3免流量标识
    		$tmp['freedl'] = $game['freedl'];
    		$tmp['reward'] = $game['reward'];
    		$tmp['score'] = $game['client_star'];
    		$tmp['resume'] = $game['resume'];
    		$tmp['activityType'] = $this->getActivityType($activityId, $from);
    	}
        $this->saveGiftGameInfoBehaviours();
    	$this->clientOutput($tmp);
    }
    
    private function getGameInfo($gameName, $game_id) {
        $game = Resource_Service_GameData::getGameAllInfo(intval($game_id));
        if($gameName == 'com.gionee.gsp'){
            if(Util_Environment::isOnline()){
                $game_id = 1971;
            } else {
                $game_id = 142;
            }
            $game = Resource_Service_GameData::getGameAllInfo($game_id);
        }
        return $game;
    }
    
    private function getActivityType($activityId, $from) {
        if(!$activityId || $from == "baidu"){
            return 'normal';
        }
        
        $info = Client_Service_Hd::getHd($activityId);
        if(!$info){
            return 'normal';
        }
        
        if($info['hd_type'] == Client_Service_Hd::HD_TYPE_COMMENT){
            return 'comment';
        }
    }

    private function saveGiftGameInfoBehaviours() {
        $imei = trim($this->getInput('imei'));
        if (!$imei) {
            $sp = $this->getInput('sp');
            $imei = Common::parseSp($sp, 'imei');
        }
		$behaviour = new Client_Service_ClientBehaviour($imei, Client_Service_ClientBehaviour::CLIENT_HALL);
        $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_GET_GAME_INFO_IN_GIFT);
    }

    public function getGiftsForGamesAction() {
        list($idsInput,$packagesInput) = $this->extractGameIds();

        if($idsInput) {
        	list($gameIds, $listInfo) = $this->fillGamesInfo($idsInput);
        } else if($packagesInput){
         	list($gameIds, $listInfo) = $this->fillGamesInfoByPackages($packagesInput);
        }
        
        $giftListData = $this->fillGiftsInfo($gameIds, $listInfo);
        $data[Util_JsonKey::LIST_ITEMS] = $giftListData;

        $this->localOutput(0, '', $data);
    }
    

    private function extractGameIds() {
        $gameIds = $this->getInput('gameIds');
        $appLists = $this->getInput('appList');
        if(!$gameIds && !$appLists) {
            $this->localOutput(-1, 'not found gameIds or gamePackages field');
            return;
        }

        if($gameIds) $gameIdArr = explode('|', $gameIds);
        if($appLists) $packageArr = explode('|', $appLists);

        return array($gameIdArr,$packageArr);
    }

    private function fillGamesInfo($idsInput) {
        $gameIds = array();
        $listInfo = array();
        foreach ($idsInput as $idKey => $gameId) {
            $gameInfo = Resource_Service_GameData::getBasicInfo($gameId);
            if (empty($gameInfo)) {
                continue;
            }
            $gameIds[] = $gameId;
            $listInfo[$gameId][Util_JsonKey::GAME_ID] = $gameId;
            $listInfo[$gameId][Util_JsonKey::ICON_URL] = $gameInfo['img'];
            $listInfo[$gameId][Util_JsonKey::GAME_NAME] = $gameInfo['name'];
            $listInfo[$gameId][Util_JsonKey::PACKAGE_NAME] = $gameInfo['package'];
        }

        if (empty($gameIds)) {
            $this->localOutput(-1, 'not found valid game id');
        }
        return array($gameIds, $listInfo);
    }
    
    
    private function fillGamesInfoByPackages($packagesInput) {
    	$gameIds = array();
    	$listInfo = array();
    	foreach ($packagesInput as $packageKey => $gamePackage) {
    		$game = Resource_Service_Games::getBy(array('package'=>$gamePackage));
    		$gameId = $game['id'];
    		$gameInfo = Resource_Service_GameData::getBasicInfo($game['id']);
    		if (empty($gameInfo)) {
    			continue;
    		}
    		$gameIds[] = $gameId;
    		$listInfo[$gameId][Util_JsonKey::GAME_ID] = $gameId;
    		$listInfo[$gameId][Util_JsonKey::ICON_URL] = $gameInfo['img'];
    		$listInfo[$gameId][Util_JsonKey::GAME_NAME] = $gameInfo['name'];
    		$listInfo[$gameId][Util_JsonKey::PACKAGE_NAME] = $gameInfo['package'];
    	}
    
    	if (empty($gameIds)) {
    		$this->localOutput(-1, 'not found valid game id');
    	}
    	return array($gameIds, $listInfo);
    }

    private function fillGiftsInfo($gameIds, $giftListData) {
        $search['status'] = Client_Service_Gift::GIFT_STATE_OPENED;
        $currentTime = Util_TimeConvert::floor(Common::getTime(), Util_TimeConvert::RADIX_DAY);
        $search['effect_start_time'] = array('<=', $currentTime);
        $search['effect_end_time'] = array('>=', $currentTime);
        if (count($gameIds) > 1) {
            $search['game_id'] = array('IN', $gameIds);
        } else {
            $search['game_id'] = $gameIds[0];
        }

        $gitfs = Client_Service_Gift::getsBy($search);

        foreach ($gitfs as $giftKey => $giftInfo) {
            $gameId = $giftInfo['game_id'];
            if (!array_key_exists($gameId, $giftListData)) {
                continue;
            }
            if (empty($giftListData[$gameId][Util_JsonKey::GIFT_NUM])) {
                $giftListData[$gameId][Util_JsonKey::GIFT_NUM] = 0;
                $giftListData[$gameId][Util_JsonKey::GIFT_START_TIME] = 0;
            }
            $giftListData[$gameId][Util_JsonKey::GIFT_NUM] += 1;
            if ($giftInfo['effect_start_time'] > 
                    $giftListData[$gameId][Util_JsonKey::GIFT_START_TIME]) {
                $giftListData[$gameId][Util_JsonKey::GIFT_START_TIME] = $giftInfo['effect_start_time'];
            }
        }

        $gameGiftInfoList = array();
        foreach($giftListData as $gameId => $gameGiftInfo) {
            if(empty($gameGiftInfo[Util_JsonKey::GIFT_NUM])) {
                continue;
            }
            $gameGiftInfoList[] = $gameGiftInfo;
        }

        return $gameGiftInfoList;
    }
    
    public function getGiftsForAllGamesAction() {
        $currentPage = $this->getInput('page');
        $numPerPage = $this->getInput('numPerPage');
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        if (empty($numPerPage)) {
            $numPerPage = 21;
        }

        list($total, $gameIds) = $this->getGameIdsWhichHasGiftFromCache($currentPage, $numPerPage);
        list($gameIds, $gameListInfo) = $this->fillGamesInfo($gameIds);
        $listInfo = $this->fillGiftNums($gameListInfo);

        $hasNext = ($currentPage * $numPerPage >= $total) ? false : true;
        $pageData['hasnext'] = $hasNext;
        $pageData['curpage'] = $currentPage;
        $pageData['list'] = $listInfo;

        $this->localOutput(0, '', $pageData);
    }
    
	private  function  getGameIdsWhichHasGiftFromCache($currentPage, $numPerPage){
		$dataVersion = Client_Service_Gift::getGiftListVersion ();
		$localCache = Cache_Factory::getCache(Cache_Factory::ID_LOCAL_APCU);
		$dataKeyName = Client_Service_Gift::getGiftListKeyNameForNew($currentPage,  $dataVersion);
		$giftListData = $localCache->get($dataKeyName);
		if($giftListData === false ){
			$giftListData = $this->getGameIdsWhichHasGift ($currentPage, $numPerPage);
			$localCache->set($dataKeyName,  $giftListData, Client_Service_Gift::GIFT_LIST_EXPIRE);
		}
		return $giftListData ;
		
	}
    
    private function getGameIdsWhichHasGift($currentPage, $perPage) {
        $search['status'] = Client_Service_Gift::GIFT_STATE_OPENED;
        $search['game_status'] = Client_Service_Gift::GAME_STATE_ONLINE;
        $currentTime = Util_TimeConvert::floor(Common::getTime(), Util_TimeConvert::RADIX_DAY);
        $search['effect_start_time'] = array('<=', $currentTime);
        $search['effect_end_time'] = array('>=', $currentTime);
        list($total, $giftList) = Client_Service_Gift::getGameList($currentPage, $perPage, $search);

        foreach($giftList as $key => $value) {
            $gameIds[] = $value['game_id'];
        }
        return array($total, $gameIds);
    }

    private function fillGiftNums($gameListInfo) {
        $listInfo = array();

        foreach ($gameListInfo as $gameId => $gameInfo) {
            $giftInfo = Client_Service_Gift::getGiftByGameId($gameId);
            $giftNum = Client_Service_Gift::getGiftNumByGameId($gameId);
            $item = $gameInfo;
            $item[Util_JsonKey::GIFT_NUM] = $giftNum;
            $listInfo[] = $item;
        }

        return $listInfo;
    }

    public function getHotGiftListAction() {
        $page = intval($this->getInput('page'));
        if (!$page) {
        	$page = 1;
        }
        $userName = $this->getInput('uname');
        $sp = $this->getInput('sp');
        $imei = end(explode('_',$sp));

    	$this->saveGiftListBehaviour($imei);
        $onLine = false;
        if($userName) {
           $onLine = Account_Service_User::checkOnline($userName, $imei);
        }
        $perPage = 10;
        
        $currentTime = Util_TimeConvert::floor(Common::getTime(), Util_TimeConvert::RADIX_DAY);
        $search['effect_start_time'] = array('<=', $currentTime);
        $search['effect_end_time'] = array('>=', $currentTime);
        $search['status'] = Client_Service_Gift::GIFT_STATE_OPENED;
        $search['game_status']  = Resource_Service_Games::STATE_ONLINE;
        list($total, $hotGiftList) = Client_Service_GiftHot::getList($page, $perPage, $search);

        $listData = $this->fillHotGiftsData($onLine, $userName, $hotGiftList);

        $countLoaded = $page * $perPage;
        $hasNext = ($countLoaded >= $total) ? false : true;

        $hotGiftsData[Util_JsonKey::HAS_NEXT] = $hasNext;
        $hotGiftsData[Util_JsonKey::CUR_PAGE] = $page;
        $hotGiftsData[Util_JsonKey::LIST_ITEMS] = $listData;

        $this->localOutput(0, '', $hotGiftsData);
    }

    private function fillHotGiftsData($onLine, $userName, $hotGiftList) {
        $listData = array();
        foreach($hotGiftList as $key=>$value) {
            $giftId = $value['gift_id'];
            $gameId = $value['game_id'];

            $gameInfo = Resource_Service_GameData::getBasicInfo($gameId);
            if (empty($gameInfo)) {
                continue;
            }

            if($onLine) {
                $log = Client_Service_Giftlog::getByUnameGiftId($userName, $giftId);
            }

            $item[Util_JsonKey::GAME_ID] = $gameId;
            $item[Util_JsonKey::GAME_NAME] = $gameInfo['name'];
            $item[Util_JsonKey::ICON_URL] = $gameInfo['img'];

            $item[Util_JsonKey::GIFT_ID] = $giftId;
            $item[Util_JsonKey::GIFT_NAME] = html_entity_decode($value['gift_name'], ENT_QUOTES);
            $item[Util_JsonKey::GIFT_KEY_TOTAL] = Client_Service_Gift::getGiftTotal($giftId);
            $item[Util_JsonKey::GIFT_KEY_REMAINS] = Client_Service_Gift::getGiftRemainNum($giftId);
            $item[Util_JsonKey::IS_GRAB] = ($log ? "true" : "false");
            $item[Util_JsonKey::GIFT_KEY] = ($log ? $log['activation_code'] : "");
            $listData[] = $item;
        }

        return $listData;
    }
    
    /**
     * 单个游戏礼包列表
     */
    public function getGiftListByGameIdAction(){
    	$page = $this->getInput('page');
    	if (!$page) {
    		$page = 1;
    	}
    	$imei = $this->getInput('imei');
    	$uname = $this->getInput('uname');
    	$gameId = intval($this->getInput('gameId'));
    		
    	if($uname) {
    		$online = Account_Service_User::checkOnline($uname, $imei);
    	}
    	
    	$this->saveGiftListBehaviour($imei);
    	$this->organizeMyGiftListByGameId ( $uname, $page, $gameId, $online);

    }
	/**
	 * @param uuid
	 * @param page
	 * @param gameId
	 */
	 private function organizeMyGiftListByGameId($uname, $page, $gameId, $online) {
    	$params['game_id'] = $gameId;
    	$params['status'] = 1;
    	$params['game_status'] = 1;
        $currTime = Util_TimeConvert::floor(Common::getTime(), Util_TimeConvert::RADIX_DAY);
        $params['effect_start_time'] = array('<=', $currTime);
		$params['effect_end_time'] = array('>=', $currTime);
    	list($total, $giftList)  = Client_Service_Gift::getList($page, 10, $params, array('sort'=>'DESC', 'effect_start_time' => 'DESC','id'=>'DESC'));
    	$hasNext = (ceil((int) $total / $this->mPageLimit) - $page) > 0 ? true : false;
    	$data = array();
    	$newStartTime = 0;
    	foreach ($giftList as $value){
    		$log = array();
    		$giftInfo = Client_Service_Gift::getGiftBaseInfo($value[Util_JsonKey::ID]);
    		$gameInfo = Resource_Service_GameData::getGameAllInfo($value['game_id']);
    		if($online) {
    			$log = Client_Service_Giftlog::getByUnameGiftId($uname, $value[Util_JsonKey::ID]);
    		}
    		$data[Util_JsonKey::LIST_ITEMS][]=array(
    				Util_JsonKey::ICON_URL=>$gameInfo[Util_JsonKey::IMAGE],
    				Util_JsonKey::GIFT_NAME=>html_entity_decode($giftInfo[Util_JsonKey::NAME], ENT_QUOTES),
    				Util_JsonKey::GIFT_KEY_TOTAL=>Client_Service_Gift::getGiftTotal($value[Util_JsonKey::ID]),
    				Util_JsonKey::GIFT_KEY_REMAINS=> Client_Service_Gift::getGiftRemainNum($value[Util_JsonKey::ID]),
    				Util_JsonKey::GIFT_ID=>$value[Util_JsonKey::ID],
    				Util_JsonKey::GAME_ID=>$value['game_id'],
    				Util_JsonKey::IS_GRAB=>($log ? "true" : "false"),
    				Util_JsonKey::GIFT_KEY=>($log ? $log['activation_code'] : "")
    		);
    		if ($giftInfo['effect_start_time'] > $newStartTime) {
    			$newStartTime = $giftInfo['effect_start_time'];
    		}
    	}
    	
    	$data[Util_JsonKey::HAS_NEXT] = $hasNext ;
    	$data[Util_JsonKey::GIFT_START_TIME] = $newStartTime ;
    	$data[Util_JsonKey::CUR_PAGE] = intval($page) ;
    	$data[Util_JsonKey::TOTAL_COUNT] = intval($total) ;
    	$this->localOutput(0, '', $data);
	}

    private function refreshCache($gameId){
        //更新游戏礼包附加属性
        Resource_Service_GameExtraCache::refreshGameGift($gameId);
        //更新礼包列表的缓存
        Client_Service_Gift::updateGiftListVersionToCache($gameId);
        //异步更新
        Async_Task::execute('Async_Task_Adapter_GameListData', 'updteListItem', $gameId);
    }
    
}
