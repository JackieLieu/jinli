<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<?php include "_config.php";?>
<?php if($isLocal):?>
<link rel="stylesheet" href="<?php echo "$webroot/$sysRef/reset/mpcore$source.css";?>" />
<link rel="stylesheet" href="<?php echo "$webroot/$appRef/assets/css/$mainCss$source.css";?>" />
<!--<script src="<?php echo "$webroot/$sysRef/icat/1.1.4/icat$source.js";?>"></script>-->
<!--<script src="<?php echo "$webroot/$appRef/assets/js/$mainJs$source.js";?>"></script>-->
<?php else :?>
<link rel="stylesheet" href="<?php echo "$webroot/$sysRef/reset/mpcore$source.css";?>" />
<link rel="stylesheet" href="<?php echo "$webroot/$appRef/assets/css/$mainCss$source.css";?>" />
<!--<script src="<?php echo "$webroot/??/$sysRef/icat/1.1.4/icat$source.js,/$appRef/assets/js/$mainJs$source.js";?>"></script>-->
<?php endif;?>
<script>var token = '132465564654';</script>
<script type="text/javascript">
		window.onload = function(){
			var autoSkip = document.querySelector('#J_autoSkip');
			if(!autoSkip) return;

			var href = autoSkip.getAttribute('data-href'),
				sec = autoSkip.querySelector('em'), i = 0,
				num = parseInt(sec.innerHTML),
				timer;

			timer = setInterval(function(){
				i++;
				if(i!=num) sec.innerHTML = num-i;

				if(i == num){
					clearInterval(timer);
					//location.href = href;
				}
			}, 1000);
		};
</script>
<!-- 多屏幕模拟测试专用 START -->
<!-- 使用方法：http://yourdomain/#?protoFluid=ready-->
<!--<script type="text/javascript" src="<?php echo $webroot;?>/sys/jquery.min.js" async="true"></script>-->
<!--<script type="text/javascript" src="<?php echo $webroot;?>/sys/protoFluid3.02.js" async="true"></script>-->
<!-- <script src="http://18.8.2.55:8080/target/target-script-min.js#anonymous"></script> -->
<!-- 多屏幕模拟测试专用 END -->

