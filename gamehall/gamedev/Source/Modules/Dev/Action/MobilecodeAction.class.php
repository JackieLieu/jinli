<?php
/**
 * 发送手机验证码（目前只限登录用户）
 * @author jiazhu
 *
 */
class MobilecodeAction extends CommonAction
{
	/**
	 * 发送手机验证码
	 */
	public function index()
	{
		$mobile = $this->_post('mobile');
		$module = $this->_post('module');
		$authId = cookie ( 'authId' );
		
		if ( empty($mobile))
		{
			$this->error ( "非法参数！" );
		}
		$config = array('item_id',$authId);
		$send_res = SmslogModel::createSend($mobile,$module,$config);
		if ($send_res > 0)
		{
			$this->success('获取验证码成功');
		}else 
		{
			$this->error('获取验证码失败，请重试');
		}
	}
}