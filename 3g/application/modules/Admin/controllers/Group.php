<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 用户组管理
 * @author rainkid
 */
class GroupController extends Admin_BaseController {

	public $actions = array(
		'listUrl'     => '/Admin/Group/index',
		'addUrl'      => '/Admin/Group/add',
		'addPostUrl'  => '/Admin/Group/add_post',
		'editUrl'     => '/Admin/Group/edit',
		'editPostUrl' => '/Admin/Group/edit_post',
		'delUrl'      => '/Admin/Group/delete'
	);

	public $perpage = 20;

	public function indexAction() {
		$page = intval($this->getInput('page'));

		list($total, $groups) = Admin_Service_Group::getList($page, $this->perpage);
		$this->assign('groups', $groups);
		$this->assign('pager', Common::getPages($total, $page, $this->perpage, $this->actions['listUrl'] . '/?'));
	}

	public function editAction() {
		$groupid     = $this->getInput('groupid');
		$groupInfo   = Admin_Service_Group::getGroup($groupid);
		$menuService = new Common_Service_Menu(Common::getConfig("siteConfig", "mainMenu"), 0);
		$level       = $menuService->getMainLevels();
		$this->assign('level', $level);
		$this->assign('groupInfo', $groupInfo);
	}

	public function edit_postAction() {
		$info   = $this->getPost(array('name', 'descrip', 'rvalue', 'groupid', 'access_val'));
		$result = Admin_Service_Group::updateGroup($info, $info['groupid']);
		if (!$result) $this->output(-1, '修改失败.');
		$this->output(0, '修改成功.');
	}

	public function addAction() {
		$menuService = new Common_Service_Menu(Common::getConfig("siteConfig", "mainMenu"), 0);
		$level       = $menuService->getMainLevels();
		$this->assign('level', $level);
	}

	public function add_postAction() {
		$info = $this->getPost(array('name', 'descrip', 'rvalue'));
		if ($info['name'] == '') $this->output(-1, '用户名不得为空.');
		$result = Admin_Service_Group::addGroup($info);
		if (!$result) $this->output(-1, '操作失败.');
		$this->output(0, '操作成功.');
	}

	public function deleteAction() {
		$groupid = $this->getInput('groupid');
		$ret     = Admin_Service_Group::deleteGroup(intval($groupid));
		if (!$ret) $this->output(-1, '操作失败.');
		$this->output(0, '操作成功.');
	}
}
