<?php $page = isset($_POST["page"])? $_POST["page"]:0;?>
{"success":true,"msg":"手机号错误","data":{"list":[{"id":"<?php echo ($page-1)*4+1?>","href":"qadetail.php","text":"手机中的语音信箱如何使用？"},{"id":"<?php echo ($page-1)*4+2?>","href":"qadetail.php","text":"手机中的语音信箱如何使用？"},{"id":"<?php echo ($page-1)*4+3?>","href":"qadetail.php","text":"手机中的语音信箱如何使用？"},{"id":"<?php echo($page-1)*4+4?>","href":"qadetail.php","text":"手机中的语音信箱如何使用？"}], "total":4, "hasnext":<?php echo ($page==4? "false":"true");?>,"curpage":<?php echo $page;?>}}