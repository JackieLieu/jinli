<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class Client_Ad_PictureController extends Admin_BaseController {
	
	public $actions = array(
		'listUrl' => '/Admin/Client_Ad_Picture/index',
		'addUrl' => '/Admin/Client_Ad_Picture/add',
		'addfreeUrl' => '/Admin/Client_Ad_Picture/addfree',
		'addPostUrl' => '/Admin/Client_Ad_Picture/add_post',
		'addfreePostUrl' => '/Admin/Client_Ad_Picture/addfree_post',
		'editUrl' => '/Admin/Client_Ad_Picture/edit',
		'editPostUrl' => '/Admin/Client_Ad_Picture/edit_post',
		'deleteUrl' => '/Admin/Client_Ad_Picture/delete',
		'uploadUrl' => '/Admin/Client_Ad_Picture/upload',
		'uploadPostUrl' => '/Admin/Client_Ad_Picture/upload_post',
		'batchUpdateUrl'=>'/Admin/Client_Ad_Picture/batchUpdate',
	    

	    'bannerUrl' => '/Admin/Client_Ad_Turn/index',
	    'imgUrl' => '/Admin/Client_Ad_Picture/index',
	    'recImgUrl' => '/Admin/Client_Ad_Recpic/index',
	    'recListUrl' => '/Admin/Client_Ad_Subject/index',
	    'imgLogUrl' => '/Admin/Client_Ad_Recommend/index',
	    'oldListUrl' => '/Admin/Client_Ad_Recommendold/index',
	    
	    
	);
	
	public $perpage = 20;
	public $ad_type = 9;
	
	public $appCacheName = array("APPC_Client_Index_index","APPC_Channel_Index_index","APPC_Kingstone_Index_index");
	//广告链接ID
	public $ad_ptype = array(
			1 => '内容',
			2 => '分类',
			3 => '专题',
			4 => '网址',
			5 => '活动',
	);
	
	public $versionName = 'Ad_Version';
	
	public function indexAction() {
		$page = intval($this->getInput('page'));

		if ($page < 1) $page = 1;
		$s = $this->getInput(array('title','status','start_time','end_time'));
		$params = array();
		
		if ($s['status']) $params['status'] = $s['status'] - 1;
		if ($s['title']) $params['title'] = array('LIKE',$s['title']);
		if ($s['start_time']) $params['start_time'] = array('>=',strtotime($s['start_time']));
		if ($s['end_time']) $params['end_time'] = array('<=',strtotime($s['end_time']));
		$params['ad_type'] = $this->ad_type;
		
	    list($total, $ads) = Client_Service_Ad::getCanUseAds($page, $this->perpage, $params);
		$this->assign('s', $s);
		$this->assign('ads', $ads);
		$this->assign('ad_type', $this->ad_type);
		$this->assign('ad_ptypes', $this->ad_ptype);
		$url = $this->actions['listUrl'].'/?' . http_build_query($s) . '&';
		$this->assign('pager', Common::getPages($total, $page, $this->perpage, $url));
		$this->assign("total", $total);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function editAction() {
		$id = $this->getInput('id');
		$info = Client_Service_Ad::getAd(intval($id));
		$this->assign('ad_type', $this->ad_type);
		$this->assign('ad_ptype', $this->ad_ptype);
		$this->assign('info', $info);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function addAction() {
		$this->assign('ad_type', $this->ad_type);
		$this->assign('ad_ptype', $this->ad_ptype);
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function addfreeAction() {
		$game_free_img = Game_Service_Config::getValue('game_free_img');
		$this->assign('game_free_img', $game_free_img);
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function addfree_postAction() {
		$img = $this->getPost('img');
		$ret = Game_Service_Config::setValue('game_free_img', $img);
		if (!$ret) $this->output('-1', '操作失败.');
		$this->output('0', '操作成功.');
	}
	
	//批量操作
	function batchUpdateAction() {
		$id = $this->getInput('id');
		$info = $this->getPost(array('action', 'ids', 'sort'));
		if (!count($info['ids'])) $this->output(-1, '没有可操作的项.');
		if($info['action'] =='sort'){
			$ret = Client_Service_Ad::sortAd($info['sort']);
		} 
		if (!$ret) $this->output('-1', '操作失败.');
		$this->output('0', '操作成功.');
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function add_postAction() {
		$info = $this->getPost(array('sort', 'title', 'head', 'ad_type', 'ad_ptype', 'link', 'img', 'icon', 'start_time', 'end_time', 'status'));
		$info['ad_type'] = $this->ad_type;
		$info = $this->_cookData($info);
		if($info['ad_ptype'] == 1){
                        $adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_GAME, $info['link']);
			$tip = "内容";
		} else if($info['ad_ptype'] == 2){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_CATEGORY, $info['link']);
			$tip = "分类";
		} else if($info['ad_ptype'] == 3){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_SUBJECT, $info['link'], array('opentime' => $info['start_time'], 'endtime' => $info['end_time']));
			$tip = "专题";
		} else if($info['ad_ptype'] == 5){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_HD, $info['link'], array('opentime' => $info['start_time'], 'endtime' => $info['end_time']));
			$tip = "活动";
		}
		if($info['ad_ptype'] != 4){
			$msg = $this->_getMsg($adInfo, $tip,$info['ad_ptype'],$info['link']);
		}
		$result = Client_Service_Ad::addAd($info);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}	
	
	public function _getMsg($adInfo,$tip,$ad_ptype,$link) {
		if(!$adInfo || $adInfo == false)  {
			$this->output(-1, $tip.'链接ID不存在请选择正确的'.$tip.'ID添加');
		} else {
			$tmp = $this->_getData($ad_ptype);
			if (in_array($link,$tmp)) $this->output(-1, $tip.'链接ID已经存在不能重复添加');
		}
	}
	
	
	public function _getData($ad_ptype) {
		list(, $ads) = Client_Service_Ad::getsBy(array('ad_type'=>9));
		$tmp = array();
		foreach($ads as $key=>$value){
			if(($value['ad_type'] == $this->ad_type) && ($value['ad_ptype'] == $ad_ptype)){
				$tmp[] = $value['link'];
			}
		}
		return $tmp;
	}
	
	public function _getTypeData($type) {
		list(, $ads) = Client_Service_Ad::getsBy(array('ad_type'=>9));
		$tmp = array();
		foreach($ads as $key=>$value){
			if(($value['ad_type'] == $this->ad_type) && ($value['ad_ptype'] == $type)){
				$tmp[] = $value['link'];
			}
		}
		return $tmp;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function edit_postAction() {
		$info = $this->getPost(array('id', 'sort', 'title','head', 'ad_type', 'ad_ptype',  'link', 'img', 'icon', 'start_time', 'end_time', 'status'));
		$info['ad_type'] = $this->ad_type;
		$info = $this->_cookData($info);	
		$id = $this->getInput('id');
		$edit_info = Client_Service_Ad::getAd(intval($id));
		if($info['ad_ptype'] == 1){
                        $adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_GAME, $info['link']);
                        $temp = $this->_getTypeData(1);
			$tip = "内容";
		} else if($info['ad_ptype'] == 2){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_CATEGORY, $info['link']);
                        $temp = $this->_getTypeData(2);
			$tip = "分类";
		} else if($info['ad_ptype'] == 3){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_SUBJECT, $info['link'], array('opentime' => $info['start_time'], 'endtime' => $info['end_time']));
			$temp = $this->_getTypeData(3);
                        $tip = "专题";
		} else if($info['ad_ptype'] == 5){
			$adInfo = Util_CheckInfoStatus::check(Util_CheckInfoStatus::TYPE_HD, $info['link'], array('opentime' => $info['start_time'], 'endtime' => $info['end_time']));
			$temp = $this->_getTypeData(5);
                        $tip = "活动";
		}
		if($info['ad_ptype'] != 4){
			if ((in_array($info['link'],$temp) && $edit_info['link'] != $info['link']) || (in_array($info['link'],$temp) && $edit_info['link'] == $info['link'] && $edit_info['ad_ptype'] != $info['ad_ptype']) ) {
				$this->output(-1, $tip.'链接ID已经存在不能重复添加');
			} else if(!in_array($info['link'],$temp) && !$adInfo){
				$this->output(-1, $tip.'链接ID无效,请选择正确的'.$tip.'ID添加');
			}
		}
		
		$ret = Client_Service_Ad::updateAd($info, intval($info['id']));
		if (!$ret) $this->output(-1, '操作失败');
		$this->output(0, '操作成功.'); 		
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	private function _cookData($info) {
		if(!$info['title']) $this->output(-1, '广告标题不能为空.');
		if(!$info['link']){
			if($info['ad_ptype'] == 1 || $info['ad_ptype'] == 2 || $info['ad_ptype'] == 3 || $info['ad_ptype'] == 5) {
				$this->output(-1, 'ID不能为空.');
			} else if($info['ad_ptype'] == 4){
				$this->output(-1, '链接地址不能为空.');
			}
		} else {
			if($info['ad_ptype'] == 4) {
				if(strpos($info['link'], 'http://') === false || !strpos($info['link'], 'https://') === false) {
					$this->output(-1, '链接地址不规范.');
				}
			}
		}
		if(!$info['img']) $this->output(-1, '广告图片不能为空.');
		if(!$info['start_time']) $this->output(-1, '开始时间不能为空.');
		if(!$info['end_time']) $this->output(-1, '结束时间不能为空.');
		$info['start_time'] = strtotime($info['start_time']);
		$info['end_time'] = strtotime($info['end_time']);
		if($info['end_time'] <= $info['start_time']) $this->output(-1, '开始时间不能大于或等于结束时间.');
		return $info;
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function deleteAction() {
		$id = $this->getInput('id');
		$info = Client_Service_Ad::getAd($id);
		if ($info && $info['id'] == 0) $this->output(-1, '无法删除');
		Util_File::del(Common::getConfig('siteConfig', 'attachPath') . $info['img']);
		$result = Client_Service_Ad::deleteAd($id);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function uploadAction() {
		$imgId = $this->getInput('imgId');
		$this->assign('imgId', $imgId);
		$this->getView()->display('common/upload.phtml');
		exit;
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function upload_postAction() {
		$ret = Common::upload('img', 'ad');
		$imgId = $this->getPost('imgId');
		$this->assign('code' , $ret['data']);
		$this->assign('msg' , $ret['msg']);
		$this->assign('data', $ret['data']);
		$this->assign('imgId', $imgId);
		$this->getView()->display('common/upload.phtml');
		exit;
	}
}
	
