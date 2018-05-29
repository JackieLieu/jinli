<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 
 * Enter description here ...
 * @author rainkid
 *
 */
class Client_BaseController extends Common_BaseController {
	public $actions = array(); 
	/**
	 * 
	 * Enter description here ...
	 */
	public function init() {
		parent::init();
		
		$this->checkToken();
		
		$webroot = Common::getWebRoot();
		$staticroot = Yaf_Application::app()->getConfig()->staticroot;
		
		$module = $this->getRequest()->getModuleName();
		$controller = $this->getRequest()->getControllerName();
		$action = $this->getRequest()->getActionName();
		
		if(stristr($webroot,'channel')) {
			$assts = 'channel';
		} else {
			$assts = 'apk';
		}
		
		
		$this->assign("webroot", $webroot);
		$this->assign("staticSysPath", $staticroot . '/sys');
		$this->assign("staticResPath", $staticroot . '/apps/game/'.$assts);
		$this->assign("staticroot", $staticroot);
		$this->assign("crUrl", $webroot."/Cr/index");
		
		//PV统计
		Common::getCache()->increment('game_pv');
		
		if ($this->getToday()) {
			Common::getCache()->increment('game_uv');
		}
		$refer = Util_Http::getServer('HTTP_REFERER');
		$this->assign("refer", $refer);
		
		$this->assign('cn', sprintf('%s_%s_%s', $module, $controller, $action));
	}
	
	public function cache($data, $name) {
		if (!$this->cacheKey) return false;
		if (!count($data) || !$name) return false;
		$file = sprintf("%scache/APPC_%s.php", Common::getConfig('siteConfig', 'dataPath'), $this->cacheKey);
	
		$new_version = crc32(json_encode($data));
	
		//如果文件存在，且版本号没有变化，则不更新
		if (!file_exists($file)) $this->saveCacheFile($file, $name, $data);
	
		$files = include $file;
		if (crc32(json_encode($files[$name])) !== $new_version) $this->saveCacheFile($file, $name, $data);
		return false;
	}
	
	private function saveCacheFile($file, $cacheName, $cacheData) {
		if(file_exists($file)) {
			$files = include $file;
			if (!is_array($files)) $files = array();
			$files[$cacheName] = array_unique($cacheData);
		}
	
		return Util_File::savePhpData($file, $files);
	}
	
	public function setSource() {
		$source = $this->getInput('source');
		$from = $this->getInput('from');
		
		if($from) $source = $from;
		
		$sid = $this->getSource();
		
		if ($sid) {
			$sid_arr = explode('_', $sid);
			$sid_arr[0] = $source;
			$string = implode('_', $sid_arr);
		} else {
			$uid = crc32(uniqid());
			$string = sprintf("%s_%s", $source, $uid);
		}
		return Util_Cookie::set('GAME-SOURCE', $string, false, strtotime("+30 day"), '/', $this->getDomain());
	}
	
	public function getSource() {
		return Util_Cookie::get('GAME-SOURCE', false);
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getDomain() {
		$domain = str_replace('http://','',Common::getWebRoot());
		if($number = strrpos($domain,':'))  $domain = Util_String::substr($domain, 0, $number);
		return $domain;
	}
	
	
	/**
	 *
	 * @return boolean
	 */
	public function getToday() {
		$is_game_user = Util_Cookie::get('ISGAMEUSER', true);
		if ($is_game_user) return false;
		return Util_Cookie::set('ISGAMEUSER', 1, true, strtotime(date('Y-m-d 23:59:59')), '/', $this->getDomain());
	}
	
	/**
	 * 检查token
	 */
	protected function checkToken() {
		if (!$this->getRequest()->isPost()) return true;
		$post = $this->getRequest()->getPost();
		$result = Common::checkToken($post['token']);
		if (Common::isError($result)) $this->output(-1, $result['msg']);
		return true;
	}
	
	/**
	 * 根据游戏大厅不同版本号返回不同值用于区别输出
	 * return int
	 * 返回值：
	 *        0  sp参数不存在
	 * 		  1  游戏大厅版本不存在或低于 1.4.8 
	 *        2  游戏大厅版本大于等于1.4.8
	 *        3  游戏大厅版本大于等于1.5.1
	 *        4  游戏大厅版本大于等于1.5.2
	 */
	protected function checkAppVersion(){
		$flag = 0;
		$request = $this->getInput('sp');
		if($request){
			$sp = explode('_', $request);		
			$version = $sp[1];
			if(empty($version)||(strnatcmp($version, '1.4.8') < 0))	{
				$flag = 1;
			} 
			if (strnatcmp($version, '1.4.8') >= 0){
				$flag = 2;
			}
			if (strnatcmp($version, '1.5.1') >= 0){
				$flag = 3;
			}
			if (strnatcmp($version, '1.5.2') >= 0){
				$flag = 4;
			}
		}
		return $flag;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $code
	 * @param unknown_type $msg
	 * @param unknown_type $data
	 */
	public function output($code, $msg = '', $data = array()) {
		$callback = $this->getInput('callback');
		$out = array(
				'success' => $code == 0 ? true : false ,
				 'msg' => $msg,
				'data' => $data
			);
		if ($callback) {
				header("Content-type:text/javascript");
				exit($callback . '(' . json_encode($out) . ')');
			} else {
				header("Content-type:text/json");
				exit(json_encode($out));
		}
	}
}
