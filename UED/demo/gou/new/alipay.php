<!DOCTYPE HTML>
<?php include '_cfg.php';?>
<html<?php echo ($ucClass? 'class="'.$ucClass.'"' : '');?>>
<head>
	<meta charset="UTF-8">
	<title>alipay介绍页</title>
	<?php include '_incfile.php';?>
</head>

<body id="alipay" data-pagerole="body">
	<div class="module">
		<header>
			<div class="top-banner"><img src="<?php echo "$webroot/$appPic/alipay_topbanner.jpg";?>" alt=""></div>
		</header>
		<section class="steps">
			<div class="item full-pic">
				<h3><i>1</i>打开支付宝钱包，点击【注册】，填写未注册过支付宝账户的手机号码，点击【下一步】</h3>
				<div class=""><img src="<?php echo "$webroot/$appPic/alipay_step1.jpg";?>" alt=""></div>
			</div>
			<div class="item pic">
				<h3><i>2</i>点击【通过短信验证身份】，若未收到短信，60秒后可以点击【重新获取校验码】</h3>
				<div><img src="<?php echo "$webroot/$appPic/alipay_step2.jpg";?>" alt=""></div>
			</div>
			<div class="item full-pic">
				<h3><i>3</i>填写好校检码，点击【下一步】，勾选同意《支付宝用户注册协议》，点击【确定】</h3>
				<div><img src="<?php echo "$webroot/$appPic/alipay_step3.jpg";?>" alt=""></div>
			</div>
			<div class="item full-pic">
				<h3><i>4</i>设置好手机支付密码和手势密码，完成注册</h3>
				<div><img src="<?php echo "$webroot/$appPic/alipay_step4.jpg";?>" alt=""></div>
			</div>
		</section>
		<footer>
			<div class="btn">
				<a href="">对我有用</a>
				<span class="J_gotop">再看一次</span>
			</div>
		</footer>
	</div>
</body>
</html>