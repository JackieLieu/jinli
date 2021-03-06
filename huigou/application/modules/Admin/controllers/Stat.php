<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class StatController extends Admin_BaseController {
	
	public $actions = array(
		'listUrl' => '/Admin/Stat/pv',
	);
	
	public $perpage = 20;
	/**
	 * 
	 * Enter description here ...
	 */
	public function pvAction() {
		$sDate = $this->getInput('sdate');
		$eDate = $this->getInput('edate');
		$quick = $this->getInput('quick');
		!$sDate && $sDate = date('Y-m-d', strtotime("-8 day"));
		!$eDate && $eDate = date('Y-m-d', strtotime("today"));
		
		//pv
		list($list, $lineData) = Gc_Service_Stat::getPvLineData($sDate, $eDate);
		
		$this->assign('list', $list);
		$this->assign('lineData', json_encode($lineData));
		$this->assign('sdate', $sDate);
		$this->assign('edate', $eDate);
		$this->assign('yesterday', date('Y-m-d', strtotime("-1 day")));
		$this->assign('weekday', date('Y-m-d', strtotime("-8 day")));
		$this->assign('monthday', date('Y-m-d', strtotime("-1 month")));
		$this->assign('threemonth', date('Y-m-d', strtotime('-3 month')));
		$this->assign('quick', $quick);
	}
	
	/**
	 *
	 * Enter description here ...
	 */
	public function ipAction() {
		$sDate = $this->getInput('sdate');
		$eDate = $this->getInput('edate');
		$quick = $this->getInput('quick');
		!$sDate && $sDate = date('Y-m-d', strtotime("-8 day"));
		!$eDate && $eDate = date('Y-m-d', strtotime("today"));
	
		//ip
		list($list, $lineData) = Gc_Service_Stat::getIpLineData($sDate, $eDate);
	
		$this->assign('list', $list);
		$this->assign('lineData', json_encode($lineData));
		$this->assign('sdate', $sDate);
		$this->assign('edate', $eDate);
		$this->assign('yesterday', date('Y-m-d', strtotime("-1 day")));
		$this->assign('weekday', date('Y-m-d', strtotime("-8 day")));
		$this->assign('monthday', date('Y-m-d', strtotime("-2 month")));
		$this->assign('threemonth', date('Y-m-d', strtotime('-3 month')));
		$this->assign('quick', $quick);
	}
}
