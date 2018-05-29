<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
/**
 * 通用上传类
 * @package
 */
class Util_Upload{
	
	const UPLOAD_ERR_INI_SIZE   = 1;
	const INPUT_MAX_FILE_SIZE   = 2;
	const UPLOAD_HALF           = 3;
	const UPLOAD_ERR_NO_TMP_DIR = 4;
	
	private $params; //参数
	private $defaultMaxSize = 2048; //上传文件默认最大值
	private $defaultAllowFileType = array('gif','jpeg','jpg','png','bmp','swf', 'txt');
	public $errorCodeArr = array(
							'upload_error'         =>array( -1, "上传失败"),
							'not_upload_files'     => array(-2, "不是通过HTTP POST方法上传"),
							'not_an_allowed_type'  => array(-3, "不允许的上传类型"),
							'file_size_is_large'   => array(-4, "文件太大"),
							'upload_err_ini_size'  => array(-5, "上传文件超过服务器上传限制"),
							'input_max_file_size'  =>array( -6, "上传文件超过表达最大上传限制"),
							'upload_half'          =>array( -7, "只上传了一半文件"),
							'upload_err_no_tmp_dir' => array(-8, "上传的临时目录出错"),
							'illegal_file_type'    =>array( -9, "新的文件名，命名不合法"),
							'upload_content_error' => array(-10, "上传的内容不合法"),
							'img_width_height' => array(-11, "图片尺寸超出限定范围"),
	                          
	                          
		); //错误码
	
	/**
	 * 初始化
	 * 
	 * @param array $params array('maxSize' => 文件上传最大,'allowFileType' => 允许上传的文件类型)
	 * @return
	 */
	public function __construct(array $params) {
		$this->params = $this->parseParams($params);
	}
	
	/**
	 * 参数设置
	 * 
	 * @param array $params array('maxSize' => 文件上传最大,'allowFileType' => 允许上传的文件类型)
	 * @return 
	 */
	public function setParams($params) {
		$this->params = $this->parseParams($params);
	}
	
	/**
	 * 上传文件 主函数
	 * 
	 * @param $name    上传文件名
	 * @param $newName 新的文件名  不需要类型
	 * @param $path    目录
	 * @return array
	 */
	public function upload($name, $newName, $path) {
		$uploadInfo = $this->init($name, $newName, $path);
		if (!$uploadInfo) return $this->error('upload_error'); //是否正常上传
		$errorVal = $this->checkUpload($uploadInfo['error']);
		if ($errorVal !== true) return $this->error($errorVal); //检测上传错误码
		if (!$this->checkIsUploadFile($uploadInfo['tmp_name'])) return $this->error('not_upload_files'); //是否通过HTTP POST上传
		if (!$this->checkType($uploadInfo['ext'])) return $this->error('not_an_allowed_type'); //是否允许上传的类型
		if (!$this->checkSize($uploadInfo['size'])) return $this->error('file_size_is_large'); //文件大小	
		if (!$this->checkImgWidthHeight($uploadInfo)) return $this->error('img_width_height'); //图片尺寸
		if (!$this->checkNewName($newName)) return $this->error('illegal_file_type'); //新文件名是否合法
		$result = $this->save($uploadInfo['tmp_name'], $uploadInfo['source'], $uploadInfo['path']);
		if ($result == false) {
			return $this->error('upload_error');
		} else {
			$checkContentResult = $this->checkContent($uploadInfo);
			if ($checkContentResult !== true) return $this->error($checkContentResult); //检测上传错误码
			return $uploadInfo;
		}
	}
	
	/**
	 * 装载上传文件的信息
	 * 
	 * @param  string $name 上传文件名
	 * @return array
	 */
	private function init($name, $newName, $path) {
		$newName = $this->escapeStr($newName);
		$path = $this->escapeDir($path);
		$file = $_FILES[$name];
		if (!$file['tmp_name'] || $file['tmp_name'] == '') return false;
		$file['name'] = $this->escapeStr($file['name']);
		$file['ext'] = strtolower(substr(strrchr($file['name'], '.'), 1));
		$file['size'] = intval($file['size']);
		$file['type'] = $file['type'];
		$file['tmp_name'] = $file['tmp_name'];
		$file['source'] = $path . '/' . $newName . '.' . $file['ext']; //路劲
		$file['path'] = $path; //目录
		$file['newName'] = $newName . '.' . $file['ext']; //文件名
		return $file;
	}
	
	/**
	 * 参数处理
	 * 
	 * @param array $params 文件上传配置参数
	 * @return 
	 */
	private function parseParams(array $params) {
		$temp = array();
		$temp['maxSize'] = (isset($params['maxSize'])) ? (int) $params['maxSize'] : $this->defaultMaxSize;
		$temp['allowFileType'] = (is_array($params['allowFileType'])) ? $params['allowFileType'] : $this->defaultAllowFileType;
		$temp['imgWH'] = $params['imgWH'];
		return $temp;
	}
	
	/**
	 * 保存文件
	 * 
	 * @param $name    上传文件名
	 * @param $newName 新的文件名  1
	 * @param $path    目录
	 * @return bool
	 */
	private function save($tmpName, $filename, $path) {
		self::mkRecur($path); //创建目录
		if (function_exists("move_uploaded_file") && @move_uploaded_file($tmpName, '/' . $filename)) {
			@chmod($filename, 0777);
			return true;
		} elseif (@copy($tmpName, $filename)) {
			@chmod($filename, 0777);
			return true;
		}
		return false;
	}

	/**
	 * 创建目录
	 *
	 * @param string $path 目录路径
	 * @param int $permissions 权限
	 * @return boolean
	 */
	public static function mk($path, $permissions = 0777) {
		return @mkdir($path, $permissions, true);
	}

	/**
	 * 递归的创建目录
	 *
	 * @param string $path 目录路径
	 * @param int $permissions 权限
	 * @return boolean
	 */
	public static function mkRecur($path, $permissions = 0777) {
		if (is_dir($path)) return true;
		$_path = dirname($path);
		if ($_path !== $path) self::mk($_path, $permissions);
		return self::mk($path, $permissions);
	}
	
	/**
	 * 错误码检测
	 * 
	 * @param int $error 错误状态
	 * @return string
	 */
	private function checkUpload($error) {
		if ($error == self::UPLOAD_ERR_INI_SIZE) { //上传是否超过ini设置
			return 'upload_err_ini_size';
		} elseif ($error == self::INPUT_MAX_FILE_SIZE) { //上传是否超过表单设置
			return 'input_max_file_size';
		} elseif ($error == self::UPLOAD_HALF) { //上传一半
			return 'upload_half';
		} elseif ($error == self::UPLOAD_ERR_NO_TMP_DIR) { //上传临时目录创建错误
			return 'upload_err_no_tmp_dir';
		} else {
			return true;
		}
	}
	
	/**
	 * 
	 * 图片大小检测
	 * */
	private function checkImgWidthHeight($uploadInfo) {
	    $wh=$this->params['imgWH'];
	    if(!$wh || count($wh)!=2) {
	        return true;
	    }
	    list($width, $height) = $wh;
	    $sizeInfo = $this->getImgSize($uploadInfo['tmp_name'], $uploadInfo['ext']);
		if(!$sizeInfo) {
		    return true;
		}
		return $sizeInfo['width']==$width && $sizeInfo['height']==$height;
	}
	
	
	/**
	 * 文件类型检测
	 * 
	 * @param string $uploadType 类型
	 * @return bool
	 */
	private function checkType($uploadType) {
		return (empty($uploadType) || !in_array($uploadType, $this->params['allowFileType'])) ? false : true;
	}
	
	/**
	 * 文件大小检测
	 * 
	 * @param int $uploadSize 大小
	 * @return bool
	 */
	private function checkSize($uploadSize) {
		return ($uploadSize < 1 || $uploadSize > ($this->params['maxSize'] * 1024)) ? false : true;
	}
	
	/**
	 * 检测新的文件名
	 * 
	 * @param string $newName 新文件名
	 * @return bool
	 */
	private function checkNewName($newName) {
		$newName = strtolower($newName);
		return (strpos($newName, '..') !== false || strpos($newName, '.php.') !== false || preg_match("/^\.php$/i", $newName)) ? false : true;
	}
	
	/**
	 * 检测是否是上传的文件
	 * 
	 * @param $tmpName 临时文件名
	 * @return bool
	 */
	private function checkIsUploadFile($tmpName) {
		if (!$tmpName || $tmpName == 'none') {
			return false;
		} elseif (function_exists('is_uploaded_file') && !is_uploaded_file($tmpName) && !is_uploaded_file(str_replace('\\\\', '\\', $tmpName))) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 文件上传后检测文件内容是否合法
	 * 
	 * @param string $uploadInfo 文件信息
	 * @param string $source 文件源目录
	 * @return bool
	 */
	private function checkContent($uploadInfo) {
		if ($uploadInfo['ext'] == 'txt') {
			if (preg_match('/(onload|submit|post|form)/i', $this->readover($uploadInfo['source']))) {
				@unlink($uploadInfo['source']);
				return 'upload_content_error';
			}
		} elseif (in_array($uploadInfo['ext'], array('gif','jpeg','jpg','png','bmp'))) {
			if (!$img_size = $this->getImgSize($uploadInfo['source'], $uploadInfo['ext'])) {
				@unlink($uploadInfo['source']);
				return 'upload_content_error';
			}
		}
		return true;
	}
	
	/**
	 * 读取文件
	 *
	 * @param string $fileName 文件绝对路径
	 * @param string $method 读取模式
	 */
	private function readover($fileName, $method = 'rb') {
		$fileName = $this->escapePath($fileName);
		$data = '';
		if ($handle = @fopen($fileName, $method)) {
			flock($handle, LOCK_SH);
			$data = @fread($handle, filesize($fileName));
			fclose($handle);
		}
		return $data;
	}
	
	/**
	 * 获取图片的大小
	 * 
	 * @param string $srcFile 图片地址
	 * @param string $srcExt  图片类型
	 * @return 
	 */
	private function getImgSize($srcFile, $srcExt = null) {
		empty($srcExt) && $srcExt = strtolower(substr(strrchr($srcFile, '.'), 1));
		$srcdata = array();
		if (function_exists('read_exif_data') && in_array($srcExt, array(
			'jpg',
			'jpeg',
			'jpe',
			'jfif'
		))) {
			$datatemp = @read_exif_data($srcFile);
			$srcdata['width'] = $datatemp['COMPUTED']['Width'];
			$srcdata['height'] = $datatemp['COMPUTED']['Height'];
			$srcdata['type'] = 2;
			unset($datatemp);
		}
		!$srcdata['width'] && list($srcdata['width'], $srcdata['height'], $srcdata['type']) = @getimagesize($srcFile);
		if (!$srcdata['type'] || ($srcdata['type'] == 1 && in_array($srcExt, array(
			'jpg',
			'jpeg',
			'jpe',
			'jfif'
		)))) { //noizy fix
			return false;
		}
		return $srcdata;
	}
	
	/**
	 * 字符转换
	 * 
	 * @param  string  $string  转换的字符串
	 * @return string  返回转换后的字符串
	 */
	private function escapeStr($string) {
		$string = str_replace(array("\0","%00","\r"), '', $string); 
		$string = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $string);
		$string = str_replace(array("%3C",'<'), '&lt;', $string);
		$string = str_replace(array("%3E",'>'), '&gt;', $string);
		$string = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $string);
		return $string;
	}
	
	/**
	 * 目录转换
	 * @param string $dir
	 * @return string
	 */
	private function escapeDir($dir) {
		$dir = str_replace(array("'",'#','=','`','$','%','&',';'), '', $dir);
		return preg_replace('/(\/){2,}|(\\\){1,}/', '/', $dir);
	}
	
	/**
	 * 私用路径转换
	 * @param $fileName
	 * @param $ifCheck
	 * @return boolean
	 */
	private function escapePath($fileName, $ifCheck = true) {
		$tmpname = strtolower($fileName);
		$tmparray = array('://',"\0");
		$ifCheck && $tmparray[] = '..';
		if (str_replace($tmparray, '', $tmpname) != $tmpname) {
			return false;
		}
		return true;
	}
	
	/**
	 * 上传错误提示
	 * 
	 * @param unknown_type $msgType
	 */
	private function error($errorCode) {
		return $this->errorCodeArr[$errorCode][0];
	}
	
	
	public function getErrorMsg($intCode) {
	    foreach ($this->errorCodeArr as $codeItem) {
	        if($codeItem[0] == $intCode) {
	               return $codeItem[1];
	            break;
	        }
	    }
	    return "";
	}
	
	
}
?>