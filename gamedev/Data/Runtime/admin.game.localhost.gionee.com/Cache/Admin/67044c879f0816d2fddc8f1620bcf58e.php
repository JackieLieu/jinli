<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (C("sitename")); ?></title>
<link href="<?php echo cdn('PUBLIC');?>/app/dwz/themes/css/login.css?20110824" rel="stylesheet" type="text/css" />
<script src="<?php echo cdn('PUBLIC');?>/app/dwz/js/jquery-1.4.4.min.js?20110425" type="text/javascript"></script>
<script language="JavaScript">
<!--
function fleshVerify(type){ 
	//重载验证码
	var timenow = new Date().getTime();
	if (type){
		$('#verifyImg').attr("src", '__URL__/verify/adv/1/'+timenow);
	}else{
		$('#verifyImg').attr("src", '__URL__/verify/'+timenow);
	}
}
$(function(){
	fleshVerify();
});
//-->
</script>
</head>
<body>
<div id="login">
	<div id="login_header">
		<h1 class="login_logo">
			<a href="#"><img src="<?php echo cdn('PUBLIC');?>/app/dwz/themes/default/images/login_logo.gif" /></a>
		</h1>
		<div class="login_headerContent">
			<div class="navList">
				<ul>
				</ul>
			</div>
			<h2 class="login_title"><img src="<?php echo cdn('PUBLIC');?>/app/dwz/themes/default/images/login_title.png" /></h2>
		</div>
	</div>
	<div id="login_content">
		<div class="loginForm">
			<form method="post" action="__URL__/checkLogin/">
				<p>
					<label>帐号：</label>
					<input type="text" name="account" size="15" class="login_input" />
				</p>
				<p>
					<label>密码：</label>
					<input type="password" name="password" size="15" class="login_input" />
				</p>
				<p>
					<label>验证码：</label>
					<input class="code" name="verify" type="text" size="5" />
					<span><img id="verifyImg" src="__URL__/verify" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle"></span>
				</p>
				<div class="login_bar">
					<input class="sub" type="submit" value=" " />
				</div>
			</form>
		</div>
		<div class="login_banner"><img src="<?php echo cdn('PUBLIC');?>/app/dwz/themes/default/images/login_banner.jpg" /></div>
		<div class="login_main">
			<ul class="helpList">
			</ul>
			<div class="login_inner">
			</div>
		</div>
	</div>
	<div id="login_footer">
		Copyright &copy; 2009 - 2011 <?php echo (C("COMPANY")); ?> Inc. All Rights Reserved. 
		Powered by <a href="http://www.4wei.cn" target="_blank">4wei Manage<em> <?php echo (C("sysversion")); ?></em>.</a>, <a href="http://www.thinkphp.cn/" target="_blank">ThinkPHP.</a>, <a href="http://www.j-ui.com/" target="_blank">JUI.</a></a>
	</div>
</div>

</body>
</html>