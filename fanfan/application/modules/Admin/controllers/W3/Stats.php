<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

class W3_StatsController extends Admin_BaseController {

	public $actions = array(
		'listUrl' => '/Admin/W3_Stats/index',

	);

	public function indexAction() {

		$type = $this->getInput('type');
		if (empty($type)) {
			$type = 'num';
		}
		$this->_logData($type);
	}

	private function _logData($type) {
		$sDate = $this->getInput('sdate');
		$eDate = $this->getInput('edate');
		!$sDate && $sDate = date('Y-m-d', strtotime("-8 day"));
		!$eDate && $eDate = date('Y-m-d', strtotime("today"));

		//pv
		$lineData = W3_Service_StatsData::getData($type, date('Ymd', strtotime($sDate)), date('Ymd', strtotime($eDate)));
		$date     = array();
		for ($i = strtotime($sDate); $i <= strtotime($eDate); $i += 86400) {
			$date[] = date('Y-m-d', $i);
		}

		$list = array();
		foreach ($lineData as $k => $v) {
			foreach ($date as $kdv) {
				$list[$k][$kdv] = isset($v[$kdv]) ? intval($v[$kdv]) : 0;
			}
		}

		$types = array(
			'num'       => '数量',
			'net'       => '网络',
			'app_ver'   => '版本',
			'model'     => '机型'
		);

		$this->assign('lineData', $list);
		$this->assign('type', $type);
		$this->assign('types', $types);
		$this->assign('sdate', $sDate);
		$this->assign('edate', $eDate);
		$this->assign('date', $date);
	}

	

}
