<?php $run_time=microtime(true);$run_memory=memory_get_usage(false);DEBUG?error_reporting(7):error_reporting(0);session_start();$domain=$_SERVER['HTTP_HOST'];if(ip2long($domain)!==false)ini_set('session.cookie_domain','');else ini_set('session.cookie_domain',$domain);require 'api/file.php';require 'lib/utile.php';require 'lib/aklib.php';set_error_handler('akerr');require 'lib/input.php';if($_POST||$_GET){$magic_quote=get_magic_quotes_gpc();if($magic_quote){$_GET=slashes_strip($_GET);$_POST=slashes_strip($_POST);}}?>