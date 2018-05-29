<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

class ActivityController extends Game_BaseController {
	
	public $actions =array(
	                'listUrl' => '/activity/index',
	                'listAjax' => '/activity/listAjax',
	                'detailUrl' => '/activity/detail',
	);
	
	public $perpage = 10;
	
	public function indexAction() {
	    $data = $this->getActivityList();
	    $this->assign('data', json_encode($data));
	}
	
	public function listAjaxAction() {
	    $data = $this->getActivityList();
	    $this->ajaxOutput($data);
	}
	
	private function getActivityList() {
	    $page = $this->getPageInput();
	    $params = array(
	                    'status' => 1,
	                    'start_time' => array('<', time())
	    );
	    $orderBy = array('sort'=>'DESC','start_time'=>'DESC','id'=>'DESC');
	    list($total, $activityies) = Client_Service_Hd::getList($page, $this->perpage,$params, $orderBy);
	    $list = array();
	    $format = 'Y-m-d';
	    $attachPath = Common::getAttachPath();
	    foreach ($activityies as $activity) {
	        $list[] = array(
	                        'name' => html_entity_decode($activity['title'], ENT_QUOTES),
	                        'date' => date($format, $activity['start_time']).'至'.date($format, $activity['end_time']),
	                        'type' => $activity['end_time'] > time() ? 'in' : 'end',
	                        'href' => Util_Statist::getActivityDetailUrl($activity['id']),
	                        'imgUrl' => $attachPath.$activity['img'],
	                        'info' => strip_tags(html_entity_decode($activity['content']))
	        );
	    }
        return array(
                        'list' => $list,
                        'hasNext' => $page*$this->perpage < $total,
                        'curPage' => $page,
                        'ajaxUrl' => $this->actions['listAjax'].'?'.Util_Statist::getCurStatistStr()
        );    
	}
	
	public function detailAction() {
	    $id = $this->getInput('id');
	    $activity = Client_Service_Hd::getHd($id);
	    $attachPath = Common::getAttachPath();
	    $game = Resource_Service_GameData::getGameAllInfo($activity['game_id']);
	    $format = 'Y-m-d';
	    $data = array(
	                    'name' => html_entity_decode($activity['title'], ENT_QUOTES),
	                    'download' => ($game && $game['status']) ? Util_Statist::getDownloadUrl($game['id'], $game['link']) : '',
	                    'contents' => html_entity_decode($activity['content']),
	                    'date' => date($format, $activity['start_time']).'至'.date($format, $activity['end_time']),
	    );
	    $this->assign('data', json_encode($data));
	}
	
}
