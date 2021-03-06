<?php
include 'common.php';
/**
 * 从BI拉取 -- 月榜
 */

//get bi rank
$bi_monthrank = Common::getDao("Client_Dao_BIMonthRank");
$result = $bi_monthrank->getsBy(array('day_id'=>date('Ymd', strtotime("-2 day"))));
//$result = $bi_monthrank->getAll();

//insert local rank
$lc_monthrank = Common::getDao("Client_Dao_MonthRank");
if($result){
	//把今天的数据全部加入表
	foreach($result as $key=>$value){
		$tmp['day_id'] = $value['day_id'];
		$tmp['game_id'] = $value['game_id'];
		$tmp['rank_rate'] = $value['rank_rate'];
		$ret = $lc_monthrank->insert($tmp);
	}
	//插入成功
	if($ret){
		//清除4天前的数据
		$clear = $lc_monthrank->delByBeforDay(date('Ymd', strtotime("-4 day")));
	}
} else {
	$to =array(
			'jiangjc@gionee.com',
			'yaozhuo@gionee.com',
			'wujiahan@gionee.com',
			'xiehx@gionee.com',
			'liuyp@gionee.com',
			'fanch@gionee.com',
			'lichanghua@gionee.com',
			'luojiapeng@gionee.com',
			'xiangyuan@gionee.com',
			'songcc@gionee.com',
			'zhongqh@gionee.com',
			'panfei@gionee.com',
			'liuliangjun@gionee.com',
			'huangab@gionee.com',
			'chenzhan@gionee.com',
			'guohb@gionee.com',
			);
	$title = "【月榜无数据】";
	$body = date("Y-m-d H:i:s",Common::getTime())."当前从BI获取数据为空，请BI确认！"."<br><br><br>";
	$body.="李畅华"."<br>";
	$body.="深圳市金立通信设备有限公司"."<br>"; 
	$body.="移动互联网运营部"."<br>";
	$body.="地址:深圳市福田区深南大道7008号阳光高尔夫大厦15楼 "."<br>";
	$body.="MP：13691655852"."<br>";
	foreach($to as $key=>$value){
		Common::sendEmail($title, $body , $value, $author, $type = 'HTML');
	}
	
}


//mark sync time





echo CRON_SUCCESS;