<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="format-detection" content="email=no" />

<?php if($isLocal){?>
<link rel="stylesheet" href="<?php echo "$webroot/$sysRef/reset/phonecore$source.css$timestamp";?>" />
<link rel="stylesheet" href="<?php echo "$webroot/$appRef/assets/css/game$source.css$timestamp";?>" />
<script type="text/javascript" refSlipt="gameChannel" src="<?php echo "$webroot/$sysRef/icat/1.1.5/icat$source.js$timestamp";?>"></script>
<script type="text/javascript" refSlipt="gameChannel" src="<?php echo "$webroot/$appRef/assets/js/plugin/cordova-2.0.0$source.js";?>"></script>
<script type="text/javascript" refSlipt="gameChannel" src="<?php echo "$webroot/$sysRef/lib/jquery/jquery$source.js";?>"></script>
<script type="text/javascript" src="<?php echo "$webroot/$appRef/assets/js/main$source.js$timestamp";?>"></script>
<?php if($isOld){?>
<link rel="stylesheet" href="<?php echo "$webroot/$appRef/assets/css/gameOld$source.css$timestamp";?>" />
<?php } } else {?>
<link rel="stylesheet" href="<?php echo "$webroot/??/$sysRef/reset/phonecore$source.css,/$appRef/assets/css/game$source.css$timestamp";?>" />
<script type="text/javascript" refSlipt="gameChannel" src="<?php echo "$webroot/??/$sysRef/icat/1.1.5/icat$source.js,/$appRef/assets/js/main$source.js$timestamp";?>"></script>
<?php } ?>

<script>var t_bi='abcdef123456';</script>
