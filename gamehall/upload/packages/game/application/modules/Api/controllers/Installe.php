<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author lichanghua
 *
 */
class InstalleController extends Api_BaseController{

	public $perpage = 10;
	
	/**
	 * get game list as more
	 */
	public function indexAction(){
	    $page = intval($this->getInput('page'));
	    $sp = $this->getInput('sp');
		if ($page < 1) $page = 1;
		
		$intersrc = $this->getInput('intersrc');
		if(!$intersrc) $intersrc = 'ness';
		$webroot = Common::getWebRoot();
	    $imei = array_shift(explode('_',$sp));
		$model = $imei; 
		$params  = $search = $tmp = $group_ids = array();
		$params['status'] = 1;
		
	    $group = Resource_Service_Pgroup::getPgroupBymodels($model);  //根据机型找出分组

	    if($group){ 
    		foreach($group as $key=>$value){
    			$temp = array();
    			$temp = explode(',',$value['p_title']);
    			if(in_array($model,$temp)){
    				$group_ids[] = $value['id'];
    			}
    		}
    		if($group_ids){
    			$params['gtype'] = array('IN',$group_ids);
    		} else {
    			$params['gtype'] = 1;
    		}
    	} else {
    		$params['gtype'] = 1;
    	}
    	$info = Client_Service_Installe::getInstalleByGtypes($params);  //找出分组信息
    	if(!$info){
    		$info = Client_Service_Installe::getInstalleByGtypes(array('gtype'=>1,'status'=>1));  //找出分组信息
    	}
       
    	list($total, $game_ids) = Client_Service_Installe::getIdxList($page, $this->perpage, array('status'=>1,'game_status'=>1,'installe_id'=>$info['id']),array('sort'=>'DESC','game_id'=>'DESC'));
    	
    	$game_ids = Common::resetKey($game_ids, 'game_id');
    	$game_ids = array_unique(array_keys($game_ids));
    	
	    foreach($game_ids as $key=>$value) {
    			$info = Resource_Service_Games::getGameAllInfo(array('id'=>$value));
	    		$games[] = $info; 
    	}
   	
    	//判断游戏大厅版本
    	$temp = array();
    	$checkVer = $this->checkAppVersion();
    	
		foreach($games as $key=>$value) {
			$href = urldecode($webroot. '/client/index/detail/?id=' . $value['id'].'&pc=1&intersrc='.$intersrc.'_GID'.$value['id'].'&t_bi='.$this->getSource());
			
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
			
			$data = array(
						'id'=>$value['id'],
						'name'=>$value['name'],
						'resume'=>$value['resume'],
						'size'=>$value['size'].'M',
						'link'=>Common::tjurl($webroot.'/client/index/tj', $value['id'], $intersrc, $value['link']),
						'alink'=>urldecode($webroot. '/client/installe/index?id=' . $value['id'].'&pc=1&intersrc='.$intersrc.'_GID'.$value['id'].'&t_bi='.$this->getSource()),
						'img'=>urldecode($value['img']),
						'profile'=>$value['name'].','.$href.','.$value['id'].','.$value['link'].','.$value['package'].','.$value['size'].','.$value['min_sys_version_title'].','.$value['min_resolution_title'].'-'.$value['max_resolution_title'],
			);
			
			if ($checkVer >= 2) {
				//js a 标签 data-infpage 参数数据
				$data_info = '游戏详情,'.$href.','.$value['id'];
				$data['profile'] = $evaluationId ? $data_info . $evaluationUrl : $data_info;
				$data['attach'] = ($attach) ? implode(',', $attach) : '';
				$data['device'] = $value['device'];
				$data['data-type'] = 1;
			}
			$temp[] = $data;
		}
	
		$hasnext = (ceil((int) $total / $this->perpage) - $page) > 0 ? true : false;
		$this->output(0, '', array('list'=>$temp, 'hasnext'=>$hasnext, 'curpage'=>$page));
	}
}
