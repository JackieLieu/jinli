<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * @author ljp
 *
 */

class TaskController extends Api_BaseController {

	public $perpage = 10;
	
	const START_DOWNLOAD_GAME_FLAG = 1;
	const DOWNLOAD_GAME_FINISHED_FLAG = 2;
	const SETUP_GAME_FINISHED_FLAG =3;
	
	const SEND_PROP_PROBABILITY = 100;
	
	
	/**
	 * 福利任务列表接口
	 */
	public function myTaskListAction(){
		$info = $this->getInput(array('version', 'puuid', 'uname', 'imei','clientId', 'sp'));
		$server_version = Game_Service_Config::getValue('Weal_Version');
	 
	    $clientVersion = Common::parseSp($info['sp'], 'game_ver');
		if(strnatcmp($clientVersion, '1.5.5') < 0){
			$this->clientOutput(array());
		}
		
		//获得缓存
		$cache = Common::getCache();
		$web_root = Common::getWebRoot();
		//任务的总条数
		$task_num   = $cache->get('weal_task_num');
		//任务的总金额
		$taskTotal = $cache->get('weal_task_total');
		//任务进度
		$task_process = $cache->hGet($info['puuid'].'_user_info','wealTaskProcess');
		
		$taskIdArr = json_decode($cache->hGet($info['puuid'].'_user_info','finishTaskid'),true);
		//首页福利任务提示开关
		$flag = Game_Service_Config::getValue('WELFARE_TASK_CONFIG');
		
		$online = false;
		if(trim($info['uname']) && $info['imei']){
			$online = Account_Service_User::checkOnline($info['uname'], $info['imei']);
		} 		 
		$data['account']  = $online?$info['uname']:'';  
		$data['version']  = $server_version;
		$data['sign']     = 'GioneeGameHall';
		$data['showInHome'] = $flag ? strval($flag) : strval(0);
		$data['progress']    = intval($task_process).'/'.$task_num;
		$data['explainUrl'] =$web_root.'/client/task/intro';
		$data['data'] = array();
			
		$this->loginSend ( $info, $taskIdArr);
		$this->myTaskList ( $taskTotal, $taskIdArr, $data );

	}
	/**
	 * @param info
	 * @param task_id_arr
	 * @param configArr
	 * @param login_class
	 */
	 private function loginSend($info, $taskIdArr ) {
	 	if(!$info['puuid'] || !$info['uname'] || !$info['clientId']){
	 		return false;
	 	}
		//登录赠送
		$rs = Common::verifyClientEncryptData($info['puuid'], $info['uname'], $info['clientId']);
		if($rs){
			//验证客户上报的数据
			if($info['puuid'] && !in_array(Util_Activity_Context::WEAL_TASK_LOING_TASK_ID, $taskIdArr)){
				$configArr = array('uuid'=>$info['puuid'],
						           'type'=>Util_Activity_Context::TASK_TYPE_WEAK_TASK,
						           'task_id'=>Util_Activity_Context::WEAL_TASK_LOING_TASK_ID);
				$login_class = new Util_Activity_Context(new Util_Activity_Login($configArr));
				$login_class ->sendTictket();
			}
		}
		return true;
	}

	/**
	 * @param task_total
	 * @param task_id_arr
	 * @param data
	 * @param weal_task_params
	 */
	 private function myTaskList($taskTotal, $taskIdArr, $data) {
		//列表页面
		$weal_task_params['status'] = 1;
		$weal_task_rs = Client_Service_WealTaskConfig::getsBy($weal_task_params);
		$remain_total = 0;
		foreach ($weal_task_rs as $key => $val){
			$award_json = json_decode($val['award_json'],true);
			$total = 0;
			foreach ($award_json as $va){
				$total +=$va['denomination'];
				if((!in_array($val['id'], $taskIdArr) && $data['account']) ){
					$remain_total +=$va['denomination'];
				}
			}
			
			if($val['id'] == 1){
				$data['data'][] = array('id'=>$val['id'],
						'title'=>html_entity_decode($val['task_name'], ENT_QUOTES),
						'detail'=>'说明：'.html_entity_decode($val['resume'],ENT_QUOTES)."\r\n奖励：".$total.'A券',
						'value'=>$total,
						'done'=> (in_array($val['id'], $taskIdArr))? '1':'0',
						'viewType'=>'onPluginEvent',
						'autoDone'=>'0',
						'param'=>array(
								'action'=>'gamehall.account'
						)
				);
			}elseif (in_array($val['id'], array(2,3,4,5,6))){
				$data['data'][] = array('id'=>$val['id'],
						'title'=>html_entity_decode($val['task_name'],ENT_QUOTES),
						'value'=>$total,
						'detail'=>'说明：'.html_entity_decode($val['resume'],ENT_QUOTES)."\r\n奖励：".$total.'A券',
						'done'=> (in_array($val['id'], $taskIdArr))? '1':'0',
						'viewType'=>'TopicDetailView',
						'autoDone'=>'0',
						'param'=>array('contentId' => $val['subject_id']?$val['subject_id']:'0'
								      ),
	
				);
			}
		}
		$data['total']    = $data['account']?$remain_total:$taskTotal;
		$data['title']    = $data['account']?('完成剩余任务送'.$data['total'].'A券'):('完成福利任务领'.$data['total'].'A券');
		$this->clientOutput($data);
	}

	

	 /**
	  * 我的A币和A券的总数
	  */
	 public function myBalanceAction(){
	 	$uuid    = $this->getInput('puuid');
	 	$uname   = $this->getInput('uname');
	 	$sp   = $this->getInput('sp');
	 	/* if(!$uuid){
	 		$this->clientOutput(array());
	 	} */
	 	
	 	//检验用户的登录状态
	 	$sp = Common::parseSp($sp);
	 	$online = true;
	 	$imei = $sp['imei'];
	 	if($uuid && $imei){
	 		$online = Account_Service_User::checkOnline($uuid, $imei, 'uuid');
	 	}
	 	$this->saveMyBalanceBehaviour($imei);
	 	
	 	$data['success']     = true;
	 	$data['msg']         = '';
	 	$data['sign']        = 'GioneeGameHall';
	 	$data['data']        = array();
	 	if(!$online){
	 		$data['code']        = '0';
	 		$this->clientOutput($data);
	 	} 
	
	 	
	 	$payment_arr = Common::getConfig('paymentConfig','payment_send');
	 	$api_key    = $payment_arr['api_key'];
	 	$url      = $payment_arr['coin_url'];
	 	$ciphertext= $payment_arr['ciphertext'];
	 	//"ACoin": "100.00","ATick": "200.00"
	 	
	 	//加密的密文
	 	$temp['api_key'] = $api_key;
	 	$temp['uuid']    = $uuid;
	 	$token = md5($ciphertext.$api_key.$uuid);
	 	$temp['token']   = $token;
	 	$data['data']  = array();
	 	//post到支付服务器
	 	//A券的余额
	 	$json_data = json_encode($temp);
	 	$result = Util_Http::post($url, $json_data, array('Content-Type' => 'application/json'));
	 	$rs_list = json_decode($result->data,true);
 		//A券
 		$data['data']['ATick'] = $rs_list['voucher']?strval(number_format($rs_list['voucher'], 2, '.', '' )):strval(0);
 		//A币
 		$data['data']['ACoin'] = $rs_list['gold_coin']?strval(number_format($rs_list['gold_coin'], 2, '.', '')):strval(0);
 		//积分
        $userInfo = Account_Service_User::getUserInfo(array('uuid' => $uuid));
        $data['data']['points'] = intval($userInfo['points']);
 		
	 	$this->clientOutput($data);
	 	
	 }	
	 
	 private function saveMyBalanceBehaviour($imei) {
	     if (!$imei) {
	         return;
	     }
	     $clientPkg = trim($this->getInput('client_pkg'));
	     $behaviour = new Client_Service_ClientBehaviour($imei, $clientPkg);
	     $behaviour->saveBehaviours(Client_Service_ClientBehaviour::ACTION_MY_BALANCE);
	 }
	 
	 /**
	  * 我的A币消费记录
	  * @author yinjiayan
	  */
	 public function myConsumeMoneyListAction() {
	     $uuid = $this->getInput('puuid');
	     $this->checkOnline($uuid);
	     
	     $page = $this->getPageInput();
	     $params = array(
	                     'uuid' => $uuid,
	                     'page_no' => strval($page),
	                     'channels' => $this->getConsumeChannles()
	     );
	     list($total, $srcListData) = $this->postPayServer('coin_consume_url', $params);
	     
	     $list = array();
	     foreach ($srcListData as $value) {
	         $item = array();
	         $item['time'] = $value['close_time'];
	         $item['subject'] = $value['subject'];
	         $trade = $value['trade'];
	         if ($trade) {
	         	foreach ($trade as $tradeItem) {
	         	    $channel = $tradeItem['channel'];
	         	    if (100 == $channel) {
	         	    	$item['ACoin'] = $tradeItem['amount'];
	         	    } else if (204 == $channel) {
	         	    	$item['ATick'] = $tradeItem['amount'];
	         	    }
	         	}
	         }
	         array_push($list, $item);
	     }
	     
	     $data = array();
	     $data['list'] = $list;
	     $data['hasnext'] =$this->hasNext($total, $page);
	     $data['curpage'] = $page;
	     $data['totalCount'] = $total;
	     $this->localOutput(0, '', $data);
	 }
	 
	 private function  getConsumeChannles() {
	     $channels = '100';
	     $spStr = $this->getInput('sp');
	     if($spStr){
	         $sp = explode('_', $spStr);
	         $version = $sp[1];
	         if(strnatcmp($version, '1.5.5') >= 0) {
	             $channels = '100,204';
	         }
	     }
	     return $channels;
	 }
	 
	 /**
	  * 我的A币 充值记录
	  * @author yinjiayan
	  */
	 public function myPaymentMoneyListAction() {
	     $uuid = $this->getInput('puuid');
	     $this->checkOnline($uuid);
	     
	     $page = $this->getPageInput();
	     $params = array(
	                     'uuid' => $uuid,
	                     'page_no' => strval($page),
	                     'channels' => $this->getPaymentChannels(),
	     );
	     list($total, $srcListData) = $this->postPayServer('coin_payment_url', $params);
	     
	     $payment_type = Common::getConfig('paymentConfig','payment_type');
	     $list = array();
	     foreach ($srcListData as $value) {
	         $item = array();
	         $item['time'] = $value['close_time'];
	         $subject = $payment_type[$value['channel']];
	         $item['subject'] = $subject ? $subject : '';
	         $item['ACoin'] = $value['amount'];
	         array_push($list, $item);
	     }
	     
	     $data = array();
	     $data['list'] = $list;
	     $data['hasnext'] =$this->hasNext($total, $page);
	     $data['curpage'] = $page;
	     $data['totalCount'] = $total;
	     $this->localOutput(0, '', $data);
	 }
	 
	 private function getPaymentChannels() {
	     $payment_type = Common::getConfig('paymentConfig','payment_type');
	     $channelsArr = array();
	     foreach ($payment_type as $key => $value) {
	         if ($key != 203 && $key != 204) {
	         	$channelsArr[] = $key;
	         }
	     }
	     return implode(',', $channelsArr);
	 }
	 
	 private function postPayServer($urlKey, $params) {
	     $payment_arr = Common::getConfig('paymentConfig','payment_send');
	     $url      = $payment_arr[$urlKey];
	     $api_key    = $payment_arr['api_key'];
	     $ciphertext= $payment_arr['ciphertext'];
	     
	     $params['api_key'] = $api_key;
	     $params['start_time'] = date('YmdHis', 1);
	     $params['end_time'] = date('YmdHis', time());
	     $params['page_size'] = strval(self::PERPAGE);
	     $params['sort_order'] = '0';
	     ksort($params);
	     $valueStr = $ciphertext;
	     foreach ($params as $key=>$value) {
	         $valueStr = $valueStr.$value;
	     }
	     $token = md5($valueStr);
	     $params['token']   = strtoupper($token);
	     $jsonParams = json_encode($params);
	     
	     $result = Util_Http::post($url, $jsonParams, array('Content-Type' => 'application/json'));
	     $resultData = json_decode($result->data,true);
	     $state = $result -> state;
	     $total = intval($resultData['total']);
	     $srcListData = json_decode($resultData['data'], true);
	     return array($total, $srcListData, $state, $url, $params, $result);
	 }
	 
// 	/**
// 	 * 我的A币消费记录
// 	 */
// 	public function myConsumeMoneyListAction(){

// 		$uuid    = $this->getInput('puuid');
// 		$page    = intval($this->getInput('page'));
// 		if($page < 1 ) $page = 1;
		
// 		$data['success']     = true;
// 		$data['msg']         = '';
// 		$data['sign']        = 'GioneeGameHall';
// 		$data['data']        = array();
		
// 		$params['uuid'] = $uuid;
// 		$params['event'] = 1;
// 		list($total, $trade_list) = Client_Service_MoneyTrade::getList($page, $this->perpage, $params);
// 		$hasnext = (ceil((int) $total / $this->perpage) - $page) > 0 ? true : false;
// 		$temp = array();
// 		foreach ($trade_list as $val){
// 			if($val['api_key']){
// 				$game_info = Resource_Service_Games::getBy(array('api_key'=>$val['api_key']));
// 				$subject = html_entity_decode($game_info['name']);
// 			}else{
// 				$subject = '';
// 			} 
// 			$temp[] = array('time' => date('YmdHis', $val['trade_time']),
// 					'ACoin'=> $val['money'],
// 					'subject'=>$subject
						
// 			);
// 		}
		
// 		$data['data']['list'] = $temp;
// 		$data['data']['hasnext'] = $hasnext ;
// 		$data['data']['curpage'] = intval($page) ;
// 		$data['data']['totalCount'] = intval($total) ;
// 		$this->clientOutput($data);			
// 	}
	
// 	/**
// 	 * 我的A币充值列表
// 	 */
// 	public function myPaymentMoneyListAction(){
// 		$uuid    = $this->getInput('puuid');
// 		$page    = intval($this->getInput('page'));
// 		if($page < 1 ) $page = 1;
				
// 		$data['success']     = true;
// 		$data['msg']         = '';
// 		$data['sign']        = 'GioneeGameHall';
// 		$data['data']        = array();
		
// 		$payment_arr = Common::getConfig('paymentConfig','payment_type');
// 		$params['uuid'] = $uuid;
// 		$params['event'] = 2;
// 		$temp = array();
// 		list($total, $trade_list) = Client_Service_MoneyTrade::getList($page, $this->perpage, $params);
// 		$hasnext = (ceil((int) $total / $this->perpage) - $page) > 0 ? true : false;
// 		foreach ($trade_list as $val){
// 			$temp[] = array('time' => date('YmdHis', $val['trade_time']),
// 					        'ACoin'=> $val['money'],
// 					        'subject'=>$payment_arr[$val['type']]
					
// 					);
// 		}
		
// 		$data['data']['list'] = $temp;
// 		$data['data']['hasnext'] = $hasnext ;
// 		$data['data']['curpage'] = intval($page) ;
// 		$data['data']['totalCount'] = intval($total) ;
		
// 		$this->clientOutput($data);
// 	}	
	
	/**
	 * 连续登录接口
	 */
	public function continueLoginAction(){
		$uuid     = $this->getInput('puuid');
		$uname    = $this->getInput('uname');
		$imei     = $this->getInput('imei');
		$systemTime     = $this->getInput('systemTime');
		
		$data['success']     = true;
		$data['msg']         = '';
		$data['sign']        = 'GioneeGameHall';
		$data['data']['offLine'] = 'false';
		$online = Account_Service_User::checkOnline($uuid, $imei, 'uuid');
		
		$time = Common::getTime();
		$cache = Common::getCache();
		$cacheKey = $uuid.'_user_info' ; //获取用户的uuid
		$lastLoginTime = $cache->hGet($cacheKey,'lastLoginTime'); //最后登录时间
		$days = Common::diffDate($lastLoginTime, $time);
		
	
		
		if($systemTime >= date('Ymd')){
			$data['data']['account']    = $uname?$uname:'';
			if($online){
				$data['data']['offLine'] = 'true';
			}
			$this->clientOutput($data);
		}
		$data['data']['systemTime'] = $online?date('Ymd', strtotime($lastLoginTime)):date('Ymd');
		$data['data']['account']    = $online?$uname:'';
		//连续登录的配置
		$taskParams['id'] = 1;
		$taskParams['status'] = 1;
		$taskConfig = Client_Service_ContinueLoginCofig::getBy($taskParams);
		if(!$taskConfig){
			$this->clientOutput($data);
		}
		//连续登录是否开启
		$data['data']['awardStatus']= $taskConfig?1:0;
		//判断连续登录的节日活动是否开启,是否在有效期
		$activityParams['status'] = 1;
		$activityParams['start_time'] = array('<=', $time);
		$activityParams['end_time']   = array('>=', $time);
		$activityConfig = Client_Service_ContinueLoginActivityConfig::getBy($activityParams);
		//连续登录的节日活动是否开启
		$data['data']['awardType']= ($taskConfig && $activityConfig)?1:0;
		
		//连续登录的天数
		$continueLoginDay = intval($cache->hGet($cacheKey,'continueLoginDay')); 
		if(!$online || $continueLoginDay < 1){
			$continueLoginDay = 1;
		}
		$data['data']['awardIndex']= $continueLoginDay;
		
		//节日活动中的图片
		$attachRoot = Common::getAttachPath();
		$data['data']['imageUrl']= $activityConfig['img']?$attachRoot.$activityConfig['img']:'';
		
		//基本奖励配置
		$basePrizeConfig = json_decode($taskConfig['award_json'], true);
		
	
	
		//七天数据数组
		$data['data']['items'] = $this->getContinueLoginDayItem($basePrizeConfig, $online, $continueLoginDay);
		
		//当次获得奖励
		list($currentPoints, $currentTicket) = $this->getContinueCurrentPrizeValue($basePrizeConfig, $online, $continueLoginDay);
		if($currentPoints > 0 ){
			$currentAardsDesc = $currentPoints.'积分';
		}elseif($currentTicket > 0){
			$currentAardsDesc = $currentTicket.'A券';
		}
		
		$data['data']['awardDes'] = $currentAardsDesc;
		//奖励提示
		$data['data']['awardTips'] =html_entity_decode($taskConfig['tips'],ENT_QUOTES);
		$this->clientOutput($data);
	}
	
	private function getContinueLoginDayItem($basePrizeConfig, $online, $continueLoinDay){
		//取得用户的七天登录周期
		if($online){
			$loginDateTime =  Common::loginDate($continueLoinDay);
		}else{
			$loginDateTime =  Common::loginDate(1);
		}
		
		//取得配置日期的节目名称
		$festival_arr = Common::getConfig('paymentConfig','festival_config');
		$tmp = array();
		foreach ($basePrizeConfig as $key=>$val){
			$activityConfig = '';
			if($val['send_object'] == 1){
				$str ='积分';
			}else{
				$str ='A券';
			}
			$name = date('n.j', strtotime($loginDateTime[$key]));
			if($online){
				$time = strtotime($loginDateTime[$key]);
			}else{
				$time = Common::getTime();
			}
			$activityParams['status'] = 1;
			$activityParams['start_time'] = array('<=', $time);
			$activityParams['end_time']   = array('>=', $time);
			$activityConfig = Client_Service_ContinueLoginActivityConfig::getBy($activityParams);
			if($activityConfig){
				$data[] =  array('festival' =>1,
						'value'    =>$val['denomination'].$str,
						'name'     =>array_key_exists($name, $festival_arr)?$festival_arr[$name]:$name,
						'type'    =>intval($activityConfig['award_type']),
						'number'  =>intval($activityConfig['award'])
							
				);
			}else{
				$data[] =  array('festival' =>0,
						'value'    =>$val['denomination'].$str,
						'name'     =>$name,
						'type'    =>0,
						'number'  =>0		
				);
			}	
		}
		return $data;
	}
	
   /**
    * 获取当次奖励的的积分
    */	
   private function getContinueCurrentPrizeValue($basePrizeConfig, $online, $continueLoinDay){
	   	//取得用户的七天登录周期
	   	$loginDateTime =  Common::loginDate($continueLoinDay);
	   	$basePrize = $basePrizeConfig[$continueLoinDay-1];
   		$activityParams['status'] = 1;
   		$activityParams['start_time'] = array('<=', strtotime($loginDateTime[$continueLoinDay-1]));
   		$activityParams['end_time']   = array('>=', strtotime($loginDateTime[$continueLoinDay-1]));
   		$activityConfig = Client_Service_ContinueLoginActivityConfig::getBy($activityParams);
   		$currentTicket = 0;
   		$currentPoints = 0;
   		if($activityConfig){
   			//award_type=1 加，2乘
   			if($activityConfig['award_type'] == 1){
   				//send_object=1 积分 2 A券
   				if($basePrize['send_object'] == 1){
   					$currentPoints = $basePrize['denomination'] + $activityConfig['award'];
   				}else{
   					$currentTicket = $basePrize['denomination'] + $activityConfig['award'];
   				}
   			}else{
   				//send_object=1 积分 2 A券
   				if($basePrize['send_object'] == 1){
   					$currentPoints = $basePrize['denomination']*$activityConfig['award'];
   				}else{
   					$currentTicket = $basePrize['denomination']*$activityConfig['award'];
   				}
   			}
   			
   		}else{
   			//send_object=1 积分 2 A券
   			if($basePrize['send_object'] == 1){
   				$currentPoints = $basePrize['denomination'];
   			}else{
   				$currentTicket = $basePrize['denomination'];
   			}
   		}
   	 return array($currentPoints, $currentTicket);
   }

	/**
	 * 游戏安装完成赠送
	 */
	public function uploadDataAction(){
		$uuid      = $this->getInput('puuid');
		$uname = $this->getInput('uname');
		$package   = $this->getInput('packageName');
		$type      = intval($this->getInput('type'));
		$sp        = $this->getInput('sp');
		$clientId  = $this->getInput('clientId');
		
		$usrInfo = array();
		$usrInfo['imei'] = $this->getInput('imei');
		$usrInfo['serverId'] = $this->getInput('serverId');
		$usrInfo['version'] = $this->getInput('version');
	
		if($type < 1 || !$uuid || $type > 3){
			$this->clientOutput(array());
		}
		
		$result = Common::verifyClientEncryptData($uuid, $uname, $clientId);
		if(!$result){
			return false;
		}
		
		$ret = $this->inspectorIsValidRequest($usrInfo, $uuid, $uname);
		if(!$ret){
		    return;
		}
	
		//获取游戏的ID
		$gameParams['package'] = $package;
		$gameParams['status']  = 1;
		$gameInfo = Resource_Service_Games::getBy($gameParams);
		$gameId = $gameInfo['id'];
		if(!$gameId) {
		    $this->clientOutput(array());
		}	
	
		//记录每日任务日志
		$this->saveDailyTaskLog($type, $gameId, $uuid);
		//开始下载的标志
		if($type == 1){
			$this->startDownloadGame($gameId, $uuid);
		}elseif($type == 2){
			$this->downloadGameFinished($gameId, $uuid);
		}else{
			$this->setupGameFinished($sp, $package, $uuid, $uname, $gameId);
		}
		//$this->output(0,'成功');
	}
	
	/**
	 * @param info
	 * @param imeiDecrypt
	 * @param ret
	 */
	private function inspectorIsValidRequest($usrInfo, $uuid, $uname) {
		if(strnatcmp($usrInfo['version'], '1.5.7') >= 0 ){
			if(!$usrInfo['serverId']){
				return false;
			}
	
			$apiName = strtoupper('uploadData');
			$verifyInfo = array();
			$imeiDecrypt = Util_Imei::decryptImei($usrInfo['imei']);
			$verifyInfo['uuid'] = $uuid;
			$verifyInfo['uname'] = $uname;
			$verifyInfo['apiName'] = $apiName;
			$verifyInfo['imei'] = $imeiDecrypt;
			$verifyInfo['serverId'] = $usrInfo['serverId'];
			$verifyInfo['version'] = $usrInfo['version'];
			$ret = $this->verifyEncryServerId($verifyInfo);
			if(!$ret){
				return false;
			}
			return true;
		}
		return true;
	}
	
	/**
	 * @param info
	 * @param imeiDecrypt
	 * @param ret
	 */
	private function verifyEncryServerId($verifyInfo) {
		$keyParam = array(
				'apiName' => $verifyInfo['apiName'],
				'imei' => $verifyInfo['imei'],
				'uname' => $verifyInfo['uname'],
		);
		$ivParam = $verifyInfo['uuid'];
		$serverId = $verifyInfo['serverId'];
		$serverIdParam = array(
				'clientVersion' => $verifyInfo['version'],
				'imei' => $verifyInfo['imei'],
				'uname' => $verifyInfo['uname'],
		);
		return Util_Inspector::verifyServerId($keyParam, $ivParam, $serverId, $serverIdParam);
	}
	
	/**
	 * 安装完成
	 * @param unknown_type $sp
	 * @param unknown_type $package
	 * @param unknown_type $uuid
	 * @param unknown_type $uname
	 * @param unknown_type $gameId
	 */
	private function setupGameFinished($sp, $package, $uuid, $uname, $gameId){
		//下载游戏报名
		$data = $this->getDataSp($sp);
		$data['package'] = $package;
		$data['uuid'] = $uuid;
		$data['user_name'] = $uname;
		$data['sp'] = $sp;
		
		$cache = Common::getCache();
		$cacheKey = $uuid.'_user_info' ;
		$clientVersion = $cache->hGet($cacheKey,'clientVersion');
		if(strnatcmp($clientVersion, '1.5.5') >= 0 ){
			//完成福利任务
			$this->tryToDownloadWealTask($uuid, $gameId);
			//完成每日任务
			$this->tryToDownloadDailyTask($uuid, $gameId);			
			//节日活动
			$this->festivalPropsSend($uuid, $gameId, $clientVersion) ;

		}
		//保存下载记录
		$ret = Client_Service_Download::insert($data);		
			
	}

	/**
	 * 节日活动道具赠送
	 * @param unknown_type $uuid
	 * @param unknown_type $gameId
	 */
	private function festivalPropsSend($uuid, $gameId, $clientVersion){
		
		$msg = array('uuid' => $uuid, ',gameId='=>$gameId,',clientVersion='.$clientVersion);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		    
	    $cache =  $this->getCache();
	    //开始下载的标志
	    $startDownGameID  = $cache->get( $uuid.'_festival_1');
	    //下载完成的标志
	    $downFinishGameID = $cache->get( $uuid.'_festival_2');
			
	    if(!in_array($gameId, $startDownGameID) || !in_array($gameId, $downFinishGameID) ){
	      	 return false;
	     }
		//是否有活动
		$festivalInfo = $this->getEffectFestivalInfo ();
		$festivalId = $festivalInfo[Festival_Service_BaseInfo::FIELD_ID];
        if(!$festivalId){
        	return false;
        }
        $msg = array('有效活动的活动festivalId' => $festivalId, '活动的基本信息'=>$festivalInfo);
        Util_Log::info(__CLASS__, 'festival.log', $msg);
        
        //检查客户端
        $result = Festival_Service_BaseInfo::checkClientVersion ( $clientVersion, $festivalInfo );
        if(!$result){
        	return false;
        }
        $msg = array('检查客户端版本的结果'=>$result);
        Util_Log::info(__CLASS__, 'festival.log', $msg);
        
        //是否领取过此游戏道具
        $isReceiveProps = $this->isReceivePropsByGameId ($gameId, $uuid, $festivalInfo);
        if($isReceiveProps){
        	return false;
        }  
        $msg = array('是否领取过此游戏的道具'=>$isReceiveProps);
        Util_Log::info(__CLASS__, 'festival.log', $msg);
      
        //获取游戏的能得到的道具
        $propIds = $this->getPropsList( $gameId, $festivalId);
        if(!$propIds){
        	return false;
        }
        $msg = array('propIds' => '获取游戏的能得到的道具='.$propIds);
        Util_Log::info(__CLASS__, 'festival.log', $msg);
        
        //取得要发送的随机的道具ID
        $propId = $this->getRandPropId ( $propIds );
		if(!$propId){
			return false;
		}
		$msg = array('msg' => '随机的道具ID='.$propId);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		//道具的信息
		$propBaseInfo = $this->getPropBaseInfo ( $festivalId, $propId );
		if(!$propBaseInfo){
			return  false;
		}
		$msg = array('msg'=>'取得道具信息', $propBaseInfo);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		//已发送的道具
		$isCanSenduserProps = $this->isCanSenduserProps ( $festivalId, $propId );
		if($isCanSenduserProps == false){
			return false;
		}
		$msg = array('msg'=>'是否能够发道具=', 'isCanSenduserProps'=>$isCanSenduserProps);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		//道具的上一个获取时间
		$lastPropGrantTime = $this->getLastPropGrantTime ($festivalInfo, $propId );
		$msg = array('msg'=>'上一个道具获取的时间', 'lastPropGrantTime'=>$lastPropGrantTime);
		Util_Log::info(__CLASS__, 'festival.log', $msg);

		//用户是否能获取道具。
		$isGrantProp       =  $this->isCanGrantProp($propBaseInfo, $lastPropGrantTime);
		if(!$isGrantProp){
			return false;
		}
		$msg = array('msg'=>'用户是否能获取道具', 'isGrantProp'=>$isGrantProp);
		Util_Log::info(__CLASS__, 'festival.log', $msg);

		//赠送用户道具
		$result = $this->sentUserProp ( $uuid, $gameId, $festivalId, $propId );
		$msg = array('msg'=>'赠送道具的结果', 'result'=>$result);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
	}
	
	
	private function isCanSenduserProps($festivalId, $currentSendPropId) {
		
		$msg = array('进入方法'=>__METHOD__, '参数'=>func_get_args());
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		$prizeParams[Festival_Service_Prizes::FIELD_FESTIVAL_ID] = $festivalId;
		$prizeInfo = Festival_Service_Prizes::getsBy($prizeParams);
		$pirzeData = array();
		foreach ($prizeInfo as $prize){
			$propIds = $prize[Festival_Service_Prizes::FIELD_CONDITION];
			$propIdArr = explode(',', $propIds);
			$total = 1 * $prize[Festival_Service_Prizes::FIELD_TOTAL];
			foreach ($propIdArr as $propId){
				$pirzeData[$propId] += $total;
			}
		}
		$msg = array('进入方法'=>__METHOD__, '奖品的各个道具发送数量'=>$pirzeData);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		$propParams[Festival_Service_PropsGrant::FIELD_FESTIVAL_ID] = $festivalId;
		$propResult = Festival_Service_PropsGrant::getsBy($propParams);
		$propData = array();
		foreach ($propResult as $val){
			$propData[$val[Festival_Service_PropsGrant::FIELD_PROP_ID]] += 1;
		}
		$msg = array('进入方法'=>__METHOD__, '已经获取的各个道具数量'=>$propData);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		
		if(intval($propData[$currentSendPropId]) >= intval($pirzeData[$currentSendPropId])){
			return false;
		}
		return true;
	}


	 private function sentUserProp($uuid, $gameId, $festivalId, $propId) {
		//赠送用户道具
		$currentTime = Common::getTime();
		Common_Service_Base::beginTransaction();
		$grantPropData[Festival_Service_PropsGrant::FIELD_UUID] = $uuid;
		$grantPropData[Festival_Service_PropsGrant::FIELD_FESTIVAL_ID] = $festivalId;
		$grantPropData[Festival_Service_PropsGrant::FIELD_GAME_ID] = $gameId;
		$grantPropData[Festival_Service_PropsGrant::FIELD_PROP_ID] = $propId;
		$grantPropData[Festival_Service_PropsGrant::FIELD_CREATE_TIME] = $currentTime;
		$result = Festival_Service_PropsGrant::insert($grantPropData);
		if(!$result){
			Common_Service_Base::rollBack();
			return false;
		}
		
		$queryParams = array(Festival_Service_PropsTotals::FIELD_UUID=>$uuid,
							 Festival_Service_PropsTotals::FIELD_FESTIVAL_ID=>$festivalId,
							 Festival_Service_PropsTotals::FIELD_PROP_ID=>$propId);
		$queryReult  = Festival_Service_PropsTotals::getBy($queryParams);
		if($queryReult){
			$result = Festival_Service_PropsTotals::increment(Festival_Service_PropsTotals::FIELD_GRANT_TOTAL, $queryParams);
			if(!$result){
				Common_Service_Base::rollBack();
				return false;
			}
			$result = Festival_Service_PropsTotals::increment(Festival_Service_PropsTotals::FIELD_REMAIN_TOTAL, $queryParams);
		}else{
			$propTotalData[Festival_Service_PropsTotals::FIELD_UUID]=$uuid;
			$propTotalData[Festival_Service_PropsTotals::FIELD_FESTIVAL_ID]=$festivalId;
			$propTotalData[Festival_Service_PropsTotals::FIELD_PROP_ID]=$propId;
			$propTotalData[Festival_Service_PropsTotals::FIELD_GRANT_TOTAL]=Festival_Service_PropsTotals::ONE;
			$propTotalData[Festival_Service_PropsTotals::FIELD_COSUME_TOTAL]=Festival_Service_PropsTotals::ZERO;
			$propTotalData[Festival_Service_PropsTotals::FIELD_REMAIN_TOTAL]=Festival_Service_PropsTotals::ONE;
			$propTotalData[Festival_Service_PropsTotals::FIELD_CREATE_TIME]=$currentTime;
			$result = Festival_Service_PropsTotals::insert($propTotalData);
		}

		if(!$result){
			Common_Service_Base::rollBack();
			return false;
		}
		Common_Service_Base::commit();
		return true;
	}

	
	
	private function isCanGrantProp($propBaseInfo, $lastPropGrantTime){ 
			
		$time = Common::getTime();
		$notPrizeProbability = self::SEND_PROP_PROBABILITY - $propBaseInfo['probability'];
		
		$msg = array('msg'=>'道具概率', 'probability'=>$propBaseInfo['probability']);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		//奖项数组
		$prizeArr = array(1=>$propBaseInfo['probability'],
				          0=>$notPrizeProbability);
		//发放时间间隔
		$interval =  $time - intval($lastPropGrantTime);
		$msg = array( '上次物品发送时间'=>$lastPropGrantTime,'发送道具的间隔'=>$interval, '奖品设置的间隔时间'=>$propBaseInfo['interval']);
		Util_Log::info(__CLASS__, 'festival.log', $msg);
		if($interval > $propBaseInfo['interval'] || $lastPropGrantTime == 0){
			$prizeResult = $this->randPrize($prizeArr);
			if($prizeResult){
				return true;
			}
		}
		return false;		
	}
	
	
	function randPrize($prizeArr) {
		$result = 0;
		//概率数组的总概率精度
		$prizeSum = array_sum($prizeArr);
		//概率数组循环
		foreach ($prizeArr as $key => $val) {
			$randNum = mt_rand(1, $prizeSum);
			if ($randNum <= $val) {
				$result = $key;
				break;
			} else {
				$prizeSum -= $val;
			}
		}
		unset ($prizeArr);
		return $result;
	}
	
	
	private function getLastPropGrantTime($festivalInfo, $propId) {
		//获取此道具上一个获取时间
		$startTime = $festivalInfo[Festival_Service_BaseInfo::FIELD_START_TIME];
		$endTime = $festivalInfo[Festival_Service_BaseInfo::FIELD_END_TIME];
		$params[Festival_Service_PropsGrant::FIELD_CREATE_TIME][0] = array('>=', $startTime);
		$params[Festival_Service_PropsGrant::FIELD_CREATE_TIME][1] = array('<=', $endTime);
		$params[Festival_Service_PropsGrant::FIELD_FESTIVAL_ID] = $festivalInfo[Festival_Service_BaseInfo::FIELD_ID];
		$params[Festival_Service_PropsGrant::FIELD_PROP_ID] = $propId;
		$orderBy = array('create_time'=>'DESC');
		$lastPropInfo = Festival_Service_PropsGrant::getBy($params, $orderBy);
		if($lastPropInfo){
			return $lastPropInfo['create_time'];
		}
		return 0 ;
	}


	 private function getPropBaseInfo($festivalId, $propId) {
	 	if(!$festivalId || !$propId){
	 		return false;
	 	}
		$params[Festival_Service_Props::FIELD_ID]  = $propId;
		$params[Festival_Service_Props::FIELD_FESTIVAL_ID]  = $festivalId;
		return Festival_Service_Props::getBy($params);
	}

	
	
	private function getRandPropId($propIds) {
		if(!$propIds){
			return false;
		}
		$propIds = explode(',', $propIds);
		if(!is_array($propIds)){
			return false;
		}
        $key  = array_rand($propIds);
        return $propIds[$key];
	}

	
	
	private function getPropsList($gameId, $festivalId) {
		if(!$gameId || !$festivalId){
			return false;
		}
		$params[Festival_Service_GameProps::FIELD_GAME_ID]     = $gameId;
        $params[Festival_Service_GameProps::FIELD_FESTIVAL_ID] = $festivalId;
        $result = Festival_Service_GameProps::getBy($params);
        if($result[Festival_Service_GameProps::FIELD_PROP_IDS]){
        	return $result[Festival_Service_GameProps::FIELD_PROP_IDS];
        }
        return false;
	}

	
	
	 private function isReceivePropsByGameId($gameId, $uuid, $festivalInfo) {
	 	if(!$gameId || !$uuid){
	 		return false;
	 	}
		//是否领取过
       	$startTime = $festivalInfo[Festival_Service_BaseInfo::FIELD_START_TIME];
       	$endTime = $festivalInfo[Festival_Service_BaseInfo::FIELD_END_TIME];
        $params[Festival_Service_PropsGrant::FIELD_CREATE_TIME][0] = array('>=', $startTime);
        $params[Festival_Service_PropsGrant::FIELD_CREATE_TIME][1] = array('<=', $endTime);
        $params[Festival_Service_PropsGrant::FIELD_GAME_ID] = $gameId;
        $params[Festival_Service_PropsGrant::FIELD_FESTIVAL_ID] = $festivalInfo[Festival_Service_BaseInfo::FIELD_ID];
        $params[Festival_Service_PropsGrant::FIELD_UUID]    = $uuid;
        $isHadFestivalActivity = Festival_Service_PropsGrant::getBy($params);
        if($isHadFestivalActivity){
        	return true;
        }
        return false;
		
	}

	
	
	 private function getEffectFestivalInfo() {
		return Festival_Service_BaseInfo::getEffectFestivalInfo();
	 }

	
	
	private function sendGiftActivationCode($userInfo, $gameId){
	    $ret = $this->checkIsCorrectRequestParams($userInfo);
	    if(!$ret){
	        return;
	    }
	    
	    $debugMsg = array('msg' => "进入赠送 ", 'event'=> 'login install start send');
		Util_Log::info(__CLASS__, Client_Service_GiftActivity::LOG_FILE, $debugMsg);
	    Client_Service_GiftActivity::sendGiftActivationCode($userInfo, $gameId);
	}
	
	private function checkIsCorrectRequestParams($userInfo){
	    $imei = $userInfo['imei'];
	    $uname = $userInfo['uname'];
	    
	    $online = Account_Service_User::checkOnline($uname, $imei);
	    if(!$online) {
	        return false;
	    }
	    if($imei == Util_Imei::EMPTY_IMEI){
	        return true;
	    }
	    
	    return Util_Imei::isEncryptImeiValid($imei);
	}

	/**
	 * 完成福利任务
	 */
	private function tryToDownloadWealTask($uuid, $gameId){
		$cache = Common::getCache();
		$cacheKey = $uuid.'_user_info' ;
		//任务的进度
		$wealTaskProcess = $cache->hGet($cacheKey,'wealTaskProcess');
		//开始下载的标志
		$startDownGameID  = json_decode($cache->hGet($cacheKey, 'startDownGameID'), true);
		//下载完成的标志
		$downFinishGameID = json_decode($cache->hGet($cacheKey, 'downFinishGameID'), true);
		//写日志
		$path = Common::getConfig('siteConfig', 'logPath');
		$fileName = date('Y-m-d').'_download.log';
		$logData= '进入下载uuid='.$uuid.',startDownGameID='.json_encode($startDownGameID).',downFinishGameID='.json_encode($downFinishGameID).',game_id='.$gameId.',wealTaskProcess='.$wealTaskProcess;
		Common::WriteLogFile($path, $fileName, $logData);
	
		//福利任务下载处理
		if(in_array($gameId, $startDownGameID) && in_array($gameId, $downFinishGameID) ){
			//下载赠送 单击游戏
			$configArr = array('uuid'=>$uuid,
					'game_id'=>$gameId,
					'type'=>Util_Activity_Context::TASK_TYPE_WEAK_TASK,
					'task_id'=>2);
			$downloadObject = new Util_Activity_Context(new Util_Activity_Download($configArr));
			$downloadObject ->sendTictket();
			//下载赠送 棋牌游戏
			$configArr = array('uuid'=>$uuid,
					'game_id'=>$gameId,
					'type'=>Util_Activity_Context::TASK_TYPE_WEAK_TASK,
					'task_id'=>3);
			$downloadObject->setStrategy(new Util_Activity_Download($configArr));
			$downloadObject->sendTictket();
			//下载赠送 棋牌游戏
			$configArr = array('uuid'=>$uuid,
					'game_id'=>$gameId,
					'type'=>Util_Activity_Context::TASK_TYPE_WEAK_TASK,
					'task_id'=>6);
			$downloadObject->setStrategy(new Util_Activity_Download($configArr));
			$downloadObject->sendTictket();
		}
			
	}
	
	/**
	 * 完成每日任务的下载
	 */
	private function tryToDownloadDailyTask($uuid, $gameId){
		//每日任务下载处理
		$configArr = array('uuid'=>$uuid,
					'game_id'=>$gameId,
					'type'=>Util_Activity_Context::TASK_TYPE_DAILY_TASK,
					'task_id'=>Util_Activity_Context::DAILY_TASK_DOWNLOAD_TASK_ID,);
		$downloadObject = new Util_Activity_Context(new Util_Activity_Download($configArr));
		$downloadObject->sendTictket();	
	}
	
	/**
	 * 游戏下载完成
	 * @param unknown_type $gameId
	 * @param unknown_type $uuid
	 */
	private function downloadGameFinished($gameId, $uuid){
		//获取游戏的ID
		if($gameId){
			$cache = Common::getCache();
			$cacheKey = $uuid.'_user_info' ;
			$downFinishGameID = json_decode($cache->hGet($cacheKey,'downFinishGameID'), true);
			if($downFinishGameID){
				if(!in_array($gameId, $downFinishGameID)){
					array_push($downFinishGameID, $gameId);
					$cache->hSet($cacheKey,'downFinishGameID', json_encode($downFinishGameID));
				}
			}else{
				$cache->hSet($cacheKey,'downFinishGameID', json_encode(array($gameId)));
			}
			$this->saveFestivalGameIdToCache($type, $uuid, $version, $gameId);
			
			$this->output(0,'成功');
		}
		//安装完成
		
		
	}
	
	/**
	 * 开始下载游戏
	 */
	private function startDownloadGame($gameId, $uuid){
		$cache = Common::getCache();
		$cacheKey = $uuid.'_user_info' ;
		$startDownGameIDArr = json_decode($cache->hGet($cacheKey, 'startDownGameID'), true);
		if($startDownGameIDArr){
			if(!in_array($gameId, $startDownGameIDArr)){
				array_push($startDownGameIDArr, $gameId);
				$cache->hSet($cacheKey,'startDownGameID', json_encode($startDownGameIDArr));
			}
		}else{
			$cache->hSet($cacheKey,'startDownGameID', json_encode(array($gameId)));
		}
	        $this->saveFestivalGameIdToCache($type, $uuid, $version, $gameId);
		$this->output(0,'成功');

	}
        private function  saveFestivalGameIdToCache($type, $uuid, $version, $gameId){
		$cache = $this->getCache();
		$key = $uuid.'_festival_'.$type ;
		$gameIDArr = $cache->get($key);
		if($gameIDArr){
			if(!in_array($gameId, $gameIDArr)){
				array_push($gameIDArr, $gameId);
				$cache->set($key, $gameIDArr, 864000);
			}
		}else{
			$cache->set($key, array($gameId), 864000);
		}
 	}	
	/**
	 * 记录每日任务日志
	 * @param unknown_type $type
	 * @param unknown_type $gameId
	 * @param unknown_type $uuid
	 */
	private function saveDailyTaskLog($type, $gameId, $uuid){	
		$cache = Common::getCache();
		$dailyLimit = intval($cache->hGet('dailyTask2', 'dailyLimit'));
		//取出对应每日任务的分享是否开启
		if($dailyLimit == 0){
			return false;
		}
		 
		$cacheHash = $uuid.'_user_info' ;
		//每天完成任务次数与时间
		$cacheFinishNumKey     = 'finishDailyTaskNum2';
		$cacheFinishTimeKey    = 'finishDailyTaskTime2';
		$finishDailyTaskNum    = $cache->hGet($cacheHash, $cacheFinishNumKey);
		$finishDailyTaskTime   = $cache->hGet($cacheHash, $cacheFinishTimeKey);
		//任务已经完成到达一定次数
		$days = Common::diffDate($finishDailyTaskTime,  date('Y-m-d H:i:s'));
		if($days == 0  && $finishDailyTaskNum >= $dailyLimit){
			return false;
		}
		
		$params['task_id'] = 2 ;
		$params['uuid'] = $uuid ;
		$params['game_id'] = $gameId ;
		$time = Common::getTime();
		$params['create_time'] = array(array('>=', strtotime(date('Y-m-d 00:00:01')) ),array('<=', strtotime(date('Y-m-d 23:59:59')))) ;
		$logRs = Client_Service_DailyTaskLog::getBy($params);
		if($logRs){
			$data['download_status'] = $type;
			$updateParams['uuid'] = $uuid;
			$updateParams['game_id'] = $gameId;
			$updateParams['task_id'] = 2 ;
			Client_Service_DailyTaskLog::updateBy($data, $updateParams);
		}elseif($type < 3){
			$data['task_id'] = 2;
			$data['uuid'] = $uuid;
			$data['game_id'] = $gameId;
			$data['create_time'] = $time;
			$data['download_status'] = $type;
			Client_Service_DailyTaskLog::insert($data);
		}
	}
	
	private function getDataSp($sp){
		$arr_sp = explode("_", $sp);
		//客户端版本
		$data['game_version'] = is_null($arr_sp[1]) ? '' : $arr_sp[1];
		//gionee rom 版本
		$data['rom_version'] = is_null($arr_sp[2]) ? '' : $arr_sp[2];
		//android 版本
		$data['android_version'] = is_null($arr_sp[3]) ? '' : $arr_sp[3];
		//手机分辨率
		$data['pixels'] = is_null($arr_sp[4]) ? '' : $arr_sp[4];
		//渠道号
		$data['channel'] = is_null($arr_sp[5]) ? '' : $arr_sp[5];
		//网络
		$data['network'] = is_null($arr_sp[6]) ? '' : $arr_sp[6];
		$data['imei'] = is_null($arr_sp[7]) ? '' : $arr_sp[7];
		return $data;
	}
	
	private function getCache(){
		//$cache = Cache_Factory::getCache();
		//正式环境记得替换
		$cache = Common::getCache();
		return $cache;
	
	}


}