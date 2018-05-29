<?php
if (! defined ( 'BASE_PATH' ))
	exit ( 'Access Denied!' );
/**
 *
 * 游戏大厅 1.5.1 之后使用
 * Enter description here ...
 * 
 * @author lichanghua
 *        
 */
class Local_Service_IndexAd {

	/**
	 * 客户端本地化客户端广告接口
	 * @param unknown_type $ad
	 * @param unknown_type $adtype
	 * @param unknown_type $pos
	 * @return multitype:number string multitype:string
	 */
	public function cookClientAd($ad, $adtype, $pos, $withCache = true ) {
		// 1.内容，2.分类，3.专题，4.外链, 5.活动
		switch ($ad ['ad_ptype']) {
			case 1 :
				//附加属性处理,1:礼包
				if (Client_Service_IndexAdI::haveGiftByGame($ad['link'])) $attach = 1;
		
				$content = self::_cookClientAdContent ( $ad, $adtype, $withCache );
				$content['attach'] = intval($attach);
				break;
			case 2 :
				$content = self::_cookClientCategory ( $ad );
				break;
			case 3 :
				$content = self::_cookClientSubject ( $ad );
				break;
			case 4 :
				$content = self::_cookClientLink ( $ad, $adtype, $pos );
				break;
			case 5 :
				$content = self::_cookClientActivity( $ad, $adtype, $pos );
				break;
		}
		return $content;
	}
	
	/**
	 * 本地化首页游戏列表
	 * @param unknown_type $ad
	 * @return array
	 */
	private static function _cookClientAdContent($ad,$adtype,$withCache = true) {
		$gameid = $ad ['link'];
		$game = Resource_Service_Games::getGameAllInfo(array("id"=>$gameid));
		$tmp = array ();
		$tmp ['viewType'] = 'GameDetailView';
		$tmp ['title'] = html_entity_decode($ad ['title']);
		$tmp ['head'] = $ad ['head'];
		$tmp ['img'] = Common::getAttachPath() . $ad ['img'];
		$tmp ['ad_id'] = $ad ['id'];
		$tmp ['param'] = array(
				'url'=>'',
				'contentId'=>$gameid,
				'gameId'=>$gameid,
		);
		
		if($adtype == 'ad4') {
			$tmp ['gameid'] = $gameid;
			$tmp ['resume'] = html_entity_decode($game ['resume']);
			$tmp ['name'] = html_entity_decode($game ['name']);
			$tmp ['img'] = $game ['img'];
			$tmp ['size'] = $game ['size']."M";
			$tmp ['package'] = $game ['package'];
			$tmp ['link'] = $game ['link'];
			$tmp ['category'] = $game ['category_title'];
			$tmp ['hot'] = Resource_Service_Games::getSubscript($game ['hot_id']);
			$tmp ['score'] = $game ['client_star'];
			unset($tmp ['param']);
			unset($tmp ['title']);
		}
		
		
		
		return $tmp;
	}
	
	private static function _cookClientCategory($ad) {
		// 分类
		$category = Resource_Service_Attribute::getResourceAttribute ( $ad ['link'] );
		$tmp = array ();
		if ($category ['status']) {
			$tmp ['head'] = $ad ['head'];
			$tmp ['title'] = html_entity_decode($ad ['title']);
			$tmp ['ad_id'] = $ad ['id'];
			$tmp ['img'] = Common::getAttachPath() . $ad['img'];
			$tmp ['viewType'] = 'CategoryDetailView';
			$tmp ['param'] = array(
					'url'=>'',
					'contentId'=>$ad ['link'],
					'gameId'=>'',
			);
		}
		return $tmp;
	}
	
	private static function _cookClientSubject($ad) {
		// 专题
		$subject = Client_Service_Subject::getSubject ( $ad ['link'] );
		$tmp = array ();
		if ($subject ['status']) {
			$tmp ['head'] = $ad ['head'];
			$tmp ['title'] = html_entity_decode($ad ['title']);
			$tmp ['ad_id'] = $ad ['id'];
			$tmp ['img'] = Common::getAttachPath() . $ad['img'];
			$tmp ['viewType'] = 'TopicDetailView';
			$tmp ['param'] = array(
					'url'=>'',
					'contentId'=>$ad ['link'],
					'gameId'=>'',
			);
		}
		return $tmp;
	}
	
	private static function _cookClientLink($ad, $adtype, $pos) {
		// 外链
		$tmp = array();
		$tj ='';
		if (stristr ( $ad ['link'], 'installe' )) {
			$tj = '_ness';
			$tmp ['data-type'] = 0;
		} else if (stristr ( $ad ['link'], 'rank' )) {
			$tj = '_newon';
			$tmp ['data-type'] = 0;
		} else if (stristr ( $ad ['link'], 'single' )) {
			$tj = '_pcg';
			$tmp ['data-type'] = 0;
		} else if (stristr ( $ad ['link'], 'web' )) {
			$tj = '_olg';
		}
	
		if (strpos(html_entity_decode($ad ['link']), "?" )) {
			$anchor =  $ad['link'] . '&intersrc=' . $adtype . $pos . $tj;
		} else {
			$anchor =  $ad['link'] . '?intersrc=' . $adtype . $pos . $tj;
		}
		$tmp ['viewType'] = 'Link';
		$tmp ['head'] = $ad ['head'];
		$tmp ['title'] = html_entity_decode($ad ['title']);
		$tmp ['img'] = Common::getAttachPath() . $ad['img'];
		$tmp ['link'] = sprintf ( "%s", $anchor );
		$tmp ['ad_id'] = $ad ['id'];
		$tmp ['param'] = array(
				'url' => html_entity_decode($anchor),
				'contentId' => '',
				'gameId' => '',
		);
		return $tmp;
	}
	
	private static function _cookClientActivity($ad) {
		// 活动
		$params['start_time'] = array('<=',Common::getTime());
		$params['status'] = 1;
		$params['id'] = $ad['link'];
		$orderBy = array('sort'=>'DESC','start_time'=>'DESC','id'=>'DESC');
		list(, $hd) = Client_Service_Hd::getList(1, 1, $params, $orderBy);
		$hd = $hd[0];
		
		$tmp = array ();
		if ($hd ['status'] && $hd) {
			$tmp ['ad_id'] = $ad ['id'];
			$tmp ['head'] = $ad ['head'];
			$tmp ['title'] = html_entity_decode($ad ['title']);
			$tmp ['img'] = Common::getAttachPath() . $ad['img'];
			$tmp ['viewType'] = 'ActivityDetailView';
			$tmp ['param'] = array(
					'url'=>'',
					'contentId'=>$ad ['link'],
					'gameId'=>$hd['game_id'],
			);
		}
		return $tmp;
	}
}
