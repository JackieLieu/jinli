<?php
if (!defined('BASE_PATH')) exit('Access Denied!');

/**
 * 任务系统-任务子类[返利活动]：登录游戏
 * @author fanch
 *
 */
class Task_Tasks_LoginGame extends Task_Tasks_Base{

    /**
     * 消息模板
     */
    protected function onCreateMessage($good, $data){
        $time = Common::getTime();
        $msgTemplate = array(
                'Acoupon'=>array(
                        'type' =>  103,
				        'top_type' =>  100,
				        'totype' =>  1,
				        'status' =>  0,
				        'start_time' =>  $time,
				        'end_time' =>  strtotime('2050-01-01 23:59:59'),
                        'title' => '您获得'. $data['denomination'] . 'A券',
                        'msg' => '恭喜，您已完成' . $data['task_name'] . '活动，获得' . $data['denomination'] . 'A券奖励！请在有效期内使用！',
                        'sendInput' => $data['uuid']
                ),
        );
        $this->saveNewTicket($data['denomination'], $data['uuid']);
        return $msgTemplate[$good];
    }
    
    private function saveNewTicket($ticket, $uuid) {
    	$ticketValues = array();
    	$ticketValues[] = $ticket;
    	$cache = Cache_Factory::getCache();
    	if ($cache->get(Util_CacheKey::SDK_TICKET_LOGIN.$uuid)) {
    		$oldTicket = json_decode($cache->hGet(Util_CacheKey::SDK_TICKET_LOGIN, $uuid), true);
    		$ticketValues = array_merge($oldTicket, $ticketValues);
    	}
    	$cache->set(Util_CacheKey::SDK_TICKET_LOGIN.$uuid, true, 2);
    	$cache->hSet(Util_CacheKey::SDK_TICKET_LOGIN, $uuid, json_encode($ticketValues), 300);
    }
}