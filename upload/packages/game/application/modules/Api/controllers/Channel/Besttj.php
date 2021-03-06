<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

class Channel_BesttjController extends Api_BaseController {
	
	public $perpage = 10;
	
    
    /**
     * 闪屏API
     */
    public function IndexAction() {
        $webroot = Common::getWebRoot();
    	$params = $flashs = $search = $tmp = $grpus = $game_ids = $games = array();
    	$flash = $this->getInput('apk_flash_recommend');
    	$flashs = explode('|',$flash);
    	if($flashs){
    		$group = Resource_Service_Pgroup::getPgroupBymodels($flashs[2]);  //根据机型找出分组
    		$group = Common::resetKey($group, 'id');
    		$ids = array_unique(array_keys($group));
    		if($ids){ 
    			foreach($group as $key=>$value){
    				$temp = array();
    				$temp = explode(',',$value['p_title']);
    				if(in_array($flashs[2],$temp)){
    					$game_ids[] = $value['id'];
    				}
    			}

    			if($game_ids){
    				$params['gtype'] = array('IN',$game_ids);
    			} else {
    			    $params['gtype'] = 1;
    		    }	
    		} else {
    			$params['gtype'] = 1;
    		}
    		$time = Common::getTime('Y-m-d H:i');
    		$params['start_time'] = array('<=', $time);
    		$params['status'] = 1;
    		$params['btype'] = 2;
    		
    		$info = Client_Service_Besttj::getBesttjByGtypes($params);  //找出分组信息
    		unset($params['start_time']);
    		unset($params['status']);
    			
    		if(!$info){
    			$info = Client_Service_Besttj::getBesttjByGtypes(array('gtype'=>1,'status'=>1));  //找出分组信息
    		}
    		$game_ids = Client_Service_Besttj::getIdxBesttjByOnlineBesttjId(intval($info['id']));
    		
    		
    		$game_ids = Common::resetKey($game_ids, 'game_id');
    		$game_ids = array_unique(array_keys($game_ids));
    		if ($game_ids) {
	    		foreach($game_ids as $key=>$value){
		    		$infos = array();
					$infos = Resource_Service_Games::getGameAllInfo(array('id'=>$value));
					if($infos['status']){
						$games[] = $infos;
					}
				}
    		}
    		
    		if($flashs[1] < 0 || $flashs[1] < $info['update_time']){
    			$tmp['sign'] = 'GioneeGameHall';
    			$tmp['version'] = $info['update_time'];
    			$tmp['title'] = html_entity_decode($info['title']);
    			$tmp['descript'] = html_entity_decode($info['guide']);
    			$temp = array();
    			$i = 0;
    			foreach($games as $key=>$value){
    				$temp[$i]['PageUrl']  = $webroot.'/channel/index/detail/?id='.$value['id'].'&pc=0&intersrc=flashrecom'.$info['id'].'&t_bi='.self::getSource();
    				$temp[$i]['GameId']  = $value['id'];
    				$temp[$i]['GameName']  = $value['name'];
    				$temp[$i]['GameUrl']  = $value['link'];
    				$temp[$i]['GamePackage']  = $value['package'];
    				$temp[$i]['GameSize']  = $value['size'];
    				$temp[$i]['SDKVersion']  = $value['version'];
    				$temp[$i]['Resolution']  = $value['min_resolution_title']."-".$value['max_resolution_title'];
    				$temp[$i]['IconUrl']  = $value['img'];
    				$i++;
    			}
    			$tmp['data'] = $temp;
    		}
    		exit(json_encode($tmp));
    	}
    }
    
   
}