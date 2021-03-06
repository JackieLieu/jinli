<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class GuidetypeController extends Admin_BaseController {
	
	public $actions = array(
		'listUrl' => '/Admin/GuideType/index',
		'addUrl' => '/Admin/GuideType/add',
		'addPostUrl' => '/Admin/GuideType/add_post',
		'editUrl' => '/Admin/GuideType/edit',
		'editPostUrl' => '/Admin/GuideType/edit_post',
		'deleteUrl' => '/Admin/GuideType/delete',
		'uploadUrl' => '/Admin/Ad/upload',
		'uploadPostUrl' => '/Admin/Ad/upload_post',
		'uploadImgUrl' => '/Admin/Ad/uploadImg'
	);
	
	public $perpage = 20;
	public $appCacheName = array('APPC_Front_Index_index', 'APPC_Channel_Index_index');
	/**
	 * 
	 * Enter description here ...
	 */
	public function indexAction() {
		$page = intval($this->getInput('page'));
		
		$perpage = $this->perpage;
		
		list($total, $result) = Gou_Service_GuideType::getList($page, $perpage);
		
		$this->assign('result', $result);
		$this->assign('pager', Common::getPages($total, $page, $perpage, $this->actions['listUrl'].'?'));
	}
	
	/**
	 * 
	 * edit an subject
	 */
	public function editAction() {
		$id = $this->getInput('id');
		$info = Gou_Service_GuideType::getGuidetype(intval($id));
		$this->assign('info', $info);
	}
	
	/**
	 * get an subjct by subject_id
	 */
	public function getAction() {
		$id = $this->getInput('id');
		$info = Gou_Service_GuideType::getGuidetype(intval($id));
		if(!$info) $this->output(-1, '操作失败.');
		$this->output(0, '', $info);
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
		$info = $this->getPost(array('sort', 'title', 'img', 'status'));
		$info = $this->_cookData($info);
		list($total, $all) = Gou_Service_GuideType::getAllGuideType();
		$tmp = array();
		foreach($all as $key=>$value){
			$tmp[] = $value['title'];
		}
		if(in_array($info['title'],$tmp)) $this->output(-1, '不能重复添加导购分类');
		$result = Gou_Service_GuideType::addGuidetype($info);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function edit_postAction() {
		$info = $this->getPost(array('id', 'sort', 'title', 'img', 'status'));
		$info = $this->_cookData($info);
		$ret = Gou_Service_GuideType::updateGuidetype($info, intval($info['id']));
		if (!$ret) $this->output(-1, '操作失败');
		$this->output(0, '操作成功.'); 		
	}

	/**
	 * 
	 * Enter description here ...
	 */
	private function _cookData($info) {
		if(!$info['title']) $this->output(-1, '名称不能为空.'); 
		if(!$info['img']) $this->output(-1, '图片不能为空.'); 
		return $info;
	}
		
	/**
	 * 
	 * Enter description here ...
	 */
	public function deleteAction() {
		$id = $this->getInput('id');
		$info = Gou_Service_GuideType::getGuidetype($id);
		if ($info && $info['id'] == 0) $this->output(-1, '无法删除');
		$result = Gou_Service_GuideType::deleteGuidetype($id);
		if (!$result) $this->output(-1, '操作失败');
		$this->output(0, '操作成功');
	}
	
	/**
	 *
	 */
	public function toolAction() {
		$cache_file = Common::getConfig('siteConfig', 'dataPath') . $this->gou_index_tool_file;
		if (is_file($cache_file)) {
			$info = include $cache_file;
		}
		$this->assign('tool', $info);
	}
	
	/**
	 * 
	 */
	public function edit_toolAction() {
		$gou_index_tools = $this->getPost('tool');
		$cache_file = Common::getConfig('siteConfig', 'dataPath') . $this->gou_index_tool_file;
		$ret = Util_File::write($cache_file, "<?php\n return ".str_replace("\'",'', var_export($gou_index_tools, TRUE))."\n?>");
		if (!$ret) $this->output(-1, '操作失败.');
		$this->output(0, '操作成功.');
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
	 */
	public function uploadImgAction() {
		$ret = Common::upload('imgFile', 'guidetype');
		if ($ret['code'] != 0) die(json_encode(array('error' => 1, 'message' => '上传失败！')));
		exit(json_encode(array('error' => 0, 'url' => '/attachs/' .$ret['data'])));
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function upload_postAction() {
		$ret = Common::upload('img', 'guidetype');
		$imgId = $this->getPost('imgId');
		$this->assign('code' , $ret['data']);
		$this->assign('msg' , $ret['msg']);
		$this->assign('data', $ret['data']);
		$this->assign('imgId', $imgId);
		$this->getView()->display('common/upload.phtml');
		exit;
	}
}
