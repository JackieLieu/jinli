<?php
// smarty
require 'plugin/smarty.php';

$setting = new setting();
$ipay_rates = $setting->get_ipay_rate();



if (isset($_POST['smt'])){
	$ipay_rates = array();
	$i=0;
	foreach($_POST['clientid'] as $k=>$v){
		$payway = trim($v);
		$name = trim($_POST['name'][$k]);
		
		$clientid = trim($v);
		$rate = trim($_POST['rate'][$k]);
		if($clientid===''||$clientid<0||(string)$rate==='')continue;
		$i++;
		$ipay_rates[$clientid] = $rate;
	}
	
	if(count($ipay_rates)<$i){
		alertMsg('输入渠道号有重复，请重新输入！');
	}
	
	$setting->set_ipay_rate($ipay_rates);
	alertMsg('修改成功！','index.php?ac='.$ac);
}

//用于新增支付方式
for($i=1;$i<=5;$i++){
	$ipay_rates[] = '';
}

$tpl_vars = array(
		'ac' => $ac,
		'actionText' => '爱贝渠道分成比率配置',
		'configs' => $ipay_rates,
		);

// 模板赋值
$smarty->assign($tpl_vars);

// 显示模板的内容
$smarty->display('index_'.$ac.'.html');