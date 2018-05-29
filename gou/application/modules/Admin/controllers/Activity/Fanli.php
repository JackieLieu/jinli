<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class Activity_FanliController extends Admin_BaseController {
	
	public $actions = array(
		'listUrl' => '/Admin/Activity_Fanli/index',
		'addUrl' => '/Admin/Activity_Fanli/add',
		'addPostUrl' => '/Admin/Activity_Fanli/add_post',
		'editUrl' => '/Admin/Activity_Fanli/edit',
		'editPostUrl' => '/Admin/Activity_Fanli/edit_post',
		'deleteUrl' => '/Admin/Activity_Fanli/delete',
	);
	
	public $perpage = 20;
	/**
	 * 
	 * Enter description here ...
	 */
	public function indexAction() {
		$page = intval($this->getInput('page'));
		$perpage = $this->perpage;
		
		list($total, $result) = Activity_Service_Fanli::getList($page, $perpage);
		
		$this->assign('result', $result);
		$this->cookieParams();
		$this->assign('pager', Common::getPages($total, $page, $perpage, $this->actions['listUrl']));
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function editAction() {
		$id = $this->getInput('id');
		$info = Activity_Service_Fanli::get(intval($id));
		
		$this->assign('info', $info);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function addAction() {
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function add_postAction() {
		$info = $this->getPost(array('name', 'rate', 'start_time', 'end_time', 'status'));
		$info = $this->_cookData($info);
		$result = Activity_Service_Fanli::add($info);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function edit_postAction() {
		$info = $this->getPost(array('name', 'rate', 'start_time', 'end_time', 'status', 'id'));
		$info = $this->_cookData($info);
		$ret = Activity_Service_Fanli::update($info, intval($info['id']));
		if (!$ret) $this->output(-1, '操作失败');
		$this->output(0, '操作成功.'); 		
	}

	/**
	 * 
	 * Enter description here ...
	 */
	private function _cookData($info) {
		if(!$info['name']) $this->output(-1, '活动名称不能为空.'); 
		if(!$info['rate']) $this->output(-1, '返利比率不能为空.'); 
		if(!$info['start_time']) $this->output(-1, '开始时间不能为空.'); 
		if(!$info['end_time']) $this->output(-1, '结束时间不能为空.');
		$info['start_time'] = strtotime($info['start_time']);
		$info['end_time'] = strtotime($info['end_time']);
		if($info['end_time'] <= $info['start_time']) $this->output(-1, '开始时间不能晚于结束时间.');
		return $info;
	}
		
	/**
	 * 
	 * Enter description here ...
	 */
	public function deleteAction() {
		$id = $this->getInput('id');
		$info = Activity_Service_Fanli::get($id);
		if ($info && $info['id'] == 0) $this->output(-1, '无法删除');
		$result = Activity_Service_Fanli::deleteAd($id);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}
}
