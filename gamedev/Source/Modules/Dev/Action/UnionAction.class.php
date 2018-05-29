<?php
/**
 * 用户联运模块
 * @author shuhai
 *
 */
class UnionAction extends BaseAction
{
	protected $model = "Union";
	protected $pageSize = 15;
	
	//展示已经通过审核的联运应用
	function index()
	{
		import ( "Extend.General.Page", LIB_PATH ); //导入分页类

		$packages = D($this->model)->where(array("author_id"=>$this->uid))->getField("package", true);
		$map = array("author_id"=>$this->uid);
		
		$union_apps = count($packages);
		if($union_apps > 0)
		{
			$map["package"] = array("in", $packages);
		}else{
			//没有联运应用
			$count = D("Dev://Apps")->where ( $map )->count();
			$this->assign("count", $count);
			$this->display();
			exit;
		}

		$count = D("Dev://Apps")->where ( $map )->count();
		$Page = new Page ( $count, $this->pageSize );
		$Page->setConfig ( "first", "首页" );
		$Page->setConfig ( "prev", "« 上一页" );
		$Page->setConfig ( "next", "下一页 »" );
		$Page->setConfig ( "last", "尾页" );
		$Page->setConfig ( "theme", "<ul class=\"pagination viciao\">%first% %upPage% %linkPage% %downPage% %end%</ul>" );
		$show = $Page->show ();
		
		$apps = D("Dev://Apps")->where ( $map )->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$apps = array_map(array($this, "getApk"), $apps);
		
		$this->assign("apps", $apps);
		$this->assign("count", $count);
		$this->assign("page", $show);
		$this->assign("union_apps", $union_apps);
		$this->display();
	}
	
	function add()
	{
		$status = D("AccountTax")->status_to_int($this->uid);
		if($status <= -2)
			$this->display("User:taxfix");
		else
			$this->display("add");
	}
	
	function key()
	{
		$model = D($this->model);
		$map = array("author_id" => $this->uid);
		$total_count = $model->where($map)->count();
		
		//获取审核状态
		if(isset($_GET["status"]) && in_array($_GET["status"], array_keys($model->_status)))
			$map['status'] = $_GET["status"];
		else
			$map['status'] = 1;
		import('ORG.Util.PageNew');
		$count = $model->where($map)->count();
		
		$Page  = new Page($count, 10);
		$Page->setConfig ( "first", "首页" );
		$Page->setConfig ( "prev", "« 上一页" );
		$Page->setConfig ( "next", "下一页 »" );
		$Page->setConfig ( "last", "尾页" );
		$Page->setConfig ( "theme", "<ul class=\"pagination viciao\">%first% %upPage% %linkPage% %downPage% %end%</ul>" );
		$show = $Page->show();

		$list = $model->where($map)->order(array("id"=>"desc"))->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$this->assign("_status", $model->_status);
		$this->assign("_all_status", $model->get_all_status($this->uid));
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('total_count',$total_count);
		$this->assign('pagenav',$show);
		$this->assign('status',$map['status']);
		$this->display("key");
	}
	
	//查看Key的状态
	function key_detail()
	{
		$id = $this->_get("id", "intval", 0);
		$map = array("author_id"=>$this->uid, "id"=>$id);
		$key = D($this->model)->where($map)->find();
		if(empty($key))
			$this->error("Key不存在");

		$this->assign("key", $key);
		
		$tpl = $key["status"] == 1 ? 'key_status_1' : 'key_status';
		$this->display($tpl);
	}
	
	//发送密钥信息
	function sendSecretKey()
	{
		$id = $this->_post("id", "intval", 0);
		$email = $this->_post("email", "trim", 0);
		$map = array("author_id"=>$this->uid, "id"=>$id);
		$key = D($this->model)->where($map)->find();
		if(empty($key))
			$this->error("Key不存在");
		
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
			$this->error("接收密钥的邮箱不正确");
		
		$subject = sprintf("%s【%s】密钥信息", C("SMTP.SMTP_NAME"), $key["name"]);
		$body = sprintf("   <p>游戏名称   ：%s</p>
							<p>游戏包名   ：%s</p>
							<p>APIKey    ：%s</p>
							<p>SecretKey ：%s</p>
							<p>PublicKey ：%s</p>
							<p>PrivateKey：%s</p>
							<p>NotifyURL ：%s<p>
							<p>SDK下载地址：http://dev.game.gionee.com/help/sdk.html</p>
							<p>如有疑问，请联系：dev.game@gionee.com</p>",
				$key['name'], $key['package'], $key['api_key'], $key['secret_key'], $key['notify_key'], $key['pay_key'], $key['notity_url']);

		smtp_mail($email, $subject, $body);

		$this->success('密钥信息发送成功，请注意查收');
	}

	//申请appkey
	function save_appkey()
	{
		$post = $this->_post();
		$post = array_map('trim', $post);
		
		if($post["channel"] == 1)
			$post["channel"] = "游戏大厅";
		else
			$post["channel"] = $post["userchannel"];
		
		if(!preg_match("@^[a-zA-Z0-9\-\.]+\.am$@is", $post["package"]))
			$this->error("应用包名不正确，包名必须以 .am 结尾");
		if(strlen($post["package"]) < 4 || strlen($post["package"]) > 200)
			$this->error("应用包名长度不正确， 只支持3-200字符");
		
		if($post["public_time_type"] == 1)
			$post["public_time"] = $post["public_time1"];
		else
			$post["public_time"] = $post["public_time2"];

		$post["author_id"] = $this->uid;
		$post["author"] = $this->user['email'];
		$post["company_name"] = D("Accountinfo")->where(array("account_id"=>$this->uid))->getField("company");

		$Union = D($this->model);

		//如果不是重新提交应用，则要进行重复检查
		if($this->_post("act") == 'update')
		{
			$id = $this->_post("id", "intval", 0);
			$map = array("author_id" => $this->uid);
			$key = D($this->model)->where($map)->find($id);
			
			if(empty($key))
				$this->error("申请记录不存在，或者已经被删除");
			if($key['status'] > 0)
				$this->error("申请记录已经通过，不能修改");

			$act = 2;

		}else{
			if($Union->where(array("package"=>$post["package"], "author_id"=>$this->uid, "channel"=>$post["channel"]))->count())
				$this->error("您已经申请过这个包名了");

			$act = 1;
		}
		
		//新申请与修改，都还原成未审核
		$post['status'] = 0;
		if (!$Union->validate($Union->_validate_appkey)->create($post, $act))
			$this->error($Union->getError());

		if($act == 2)
			$res = $Union->save();
		else
			$res = $Union->add();
		
		//关联已经发布的应用
		D("Union")->snyc_key_appid($post["package"], $this->uid);
		
		if(!$res)
		{
			Log::write("SQL新增记录出错：" . $Union->getDbError(), Log::EMERG); 
			$this->error("系统出错了，请联系我们解决");
		}else{
			session("notice_message", $this->fetch("add_key_success"));
			
			$this->display('success');
		}
	}
	
	function notityurl()
	{
		$Union = D($this->model);
		$id = $this->_post("id", "intval", 0);
		$map = array("author_id" => $this->uid, "id"=>$id);
		$key = $Union->where($map)->find();
			
		if(empty($key))
			$this->error("申请记录不存在，或者已经被删除");
		if($key['status'] <= 0)
			$this->error("申请记录未通过审核，不能修改");
		
		$url = $this->_post("notity_url", "trim", "");
		$url = str_replace('&amp;', '&', $url);
		$post = array("id"=>$id,  "notity_url"=>$url);

		if (!$Union->validate($Union->_validate_notityurl)->create($post, Model::MODEL_UPDATE))
			$this->error($Union->getError());
		
		$data = Helper("ApiKey")->notify_update(array("api_key"=>$key["api_key"], "notify_url"=>$url));

		if(empty($data))
			$this->error("请求ApiKey服务接口时出错，或网络超时");

		if(20000000 != $data["status"])
			$this->error(sprintf("申请ApiKey时出错，%d:%s", $data["status"], $data["description"]));
		
		if($Union->save())
			$this->success("notityURL更新成功");
		else
			$this->error("更新失败，服务器数据库出了问题");
	}
	
	function reset_paykey()
	{
		$Union = D($this->model);
		$id = $this->_post("id", "intval", 0);
		$map = array("author_id" => $this->uid, "id"=>$id);
		$key = $Union->where($map)->find();
			
		if(empty($key))
			$this->error("申请记录不存在，或者已经被删除");
		if($key['status'] <= 0)
			$this->error("申请记录未通过审核，不能修改");
		
		$data = Helper("ApiKey")->paykey_update(array("api_key"=>$key["api_key"]));
		
		if(empty($data))
			$this->error("请求ApiKey服务接口时出错，或网络超时");
		
		if(20000000 != $data["status"])
			$this->error(sprintf("申请ApiKey时出错，%d:%s", $data["status"], $data["description"]));
				
		$data = array("id" => $id, "pay_key" => $data["pay_key"]);
		if($Union->data($data)->save())
			$this->success("支付私钥更新成功");
		else
			$this->error("更新失败，服务器数据库出了问题");
	}
	
	//检查包名是否通过联运审核
	function union_check_package()
	{
		$package = $this->_post("package", "trim", "");
		if(empty($package))
			$this->error("您的应用还没有申请联运Key");
		$status = D($this->model)->union_check_package($package, $this->uid);
		if($status <= 0)
			$this->error("您的应用还没有申请联运Key或者还没有通过审核，不能申请联运");
		if($status == 1)
			$this->success("该应用已通过联运审核，可以申请联运");
		$this->error("发生未知错误");
	}
	
	function taxfix()
	{
		R("Dev://User/taxfix");
	}
	
	protected function getApk($app)
	{
		$app['apk'] = D("Dev://Apks")->getApkByAppId($app['id']);
		$app['icon'] = D("Dev://Picture")->getApkIcon($app['id'], $app['apk']['id'], array('type'=>array('gt', 1)) );
		$app['status'] = D("Dev://Apks")->status_to_string($app['apk']['id']);
		return $app;
	}

}