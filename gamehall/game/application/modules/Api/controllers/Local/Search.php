<?php

if (!defined('BASE_PATH'))
    exit('Access Denied!');

class Local_SearchController extends Api_BaseController {

    public $perpage = 10;
    public $actions = array(
        'detailtUrl' => '/index/detail',
        'searchUrl' => '/Search/list',
    );
    private $mOptionSearch = array();

    public function searchListAction() {
        $page = intval($this->getInput('page'));
        $limit = intval($this->getInput('limit'));
        $keyword = html_entity_decode($this->getInput('keyword'));
        $intersrc = $this->getInput('intersrc');
        $type = $this->getInput('type');
        $data = $this->_searchList($keyword, $page, $intersrc, $limit, $type);
        $this->localOutput('', '', $data);
    }

    public function searchMoreAction() {
        $get = $this->getInput(array('keyword', 'limit', 'type'));
        $action = true;
        $filter_key = Game_Service_Config::getValue('game_search_filter');
        $filter_key = explode('|', $filter_key);
        if (in_array($get['keyword'], $filter_key))
            $action = false;
        $searchOption = array(
            'searchFrom' => $get['type'] == 'h5' ? Api_Search_Query::Search_From_Default : Api_Search_Query::Search_From_Android,
            'searchAction' => intval($this->getInput('action')),
            'ua' => $this->getInput('sp'),
            'uuid' => $this->getInput('puuid')
        );
        $localGames = Api_Search_Query::getHotSearchConnectList($get['keyword'], 6, $searchOption);
        $searchList = $this->handleConnectWordApiData($localGames['data']['list'], $get['keyword'], $get['type']);
        $this->localOutput('', '', $searchList);
    }

    /**
     * 
     */
    private function _searchList($keyword, $page, $intersrc, $limit, $type = '') {
        if (!$intersrc)
            $intersrc = 'SEARCH';
        if ($page < 1)
            $page = 1;

        //关键字过滤
        $action = true;
        $filter_key = Game_Service_Config::getValue('game_search_filter');
        $filter_key = explode('|', $filter_key);
        if (in_array($keyword, $filter_key))
            $action = false;

        $searchOption = array(
            'searchFrom' => $type == 'h5' ? Api_Search_Query::Search_From_Default : Api_Search_Query::Search_From_Android,
            'searchAction' => intval($this->getInput('action')),
            'ua' => $this->getInput('sp'),
            'uuid' => $this->getInput('puuid')
        );
        $localGames = Api_Search_Query::getSearchList($page, $limit ? $limit : $this->perpage, $keyword, $searchOption);

        $this->mOptionSearch = array(
            'localGameList' => $localGames['data'],
            'action' => $action,
            'keyword' => $keyword,
            'page' => $page,
            'perpage' => $this->perpage,
            'intersrc' => $intersrc
        );

        $searchList = Api_Search_Query::handleSearchList($this->mOptionSearch, $type);
        if ($type == 'h5') {
            $gameList = $this->handleMobileApih5Data($searchList);
        } else {
            $gameList = $this->handleMobileApiData($searchList);
        }
        $hasnext = (ceil((int) $searchList['total'] / $this->perpage) - ($page)) > 0 ? true : false;
        if ($type == 'h5') {
            $data = array('list' => $gameList, 'hasNext' => $hasnext, 'curPage' => $page, 'resum' => $searchList['resum'], 'totalCount' => $searchList['total']);
        } else {
            $data = array('list' => $gameList, 'hasnext' => $hasnext, 'curpage' => $page, 'resum' => $searchList['resum'], 'totalCount' => $searchList['total']);
        }
        return $data;
    }

    private function handleConnectWordApiData($searchList, $keyword, $type = '') {
        $gameList = array('gameItems' => array(), 'list' => array());
        foreach ($searchList as $key => $value) {
            if (count($gameList['gameItems']) > 0) {
                $gameInfo = Resource_Service_GameData::getGameAllInfo($value['id']);
                $gameList['list'][] = array('name' => $value['name'], 'href' => Common::getWebRoot() . $this->actions['searchUrl'] . '?keyword=' . html_entity_decode($value['name']) . '&f=' . $keyword . '&intersrc=hsearch&t_bi=' . $this->getSource());
            } else {
                $attach = array();
                if (Client_Service_IndexAdI::haveGiftByGame($value['id'])) {
                    array_push($attach, '1');
                }
                $gameInfo = Resource_Service_GameData::getGameAllInfo($value['id']);
                $data = array(
                    'img' => urldecode($gameInfo['img']),
                    'name' => html_entity_decode($value['name']),
                    'resume' => html_entity_decode($gameInfo['resume']),
                    'package' => $gameInfo['package'],
                    'link' => $type == 'h5' ? Util_Statist::getDownloadTSearchUrl($value['id'], $gameInfo['link']) : $gameInfo['link'],
                    'gameId' => $value['id'],
                    'size' => $gameInfo['size'] . 'M',
                    'category' => $gameInfo['category_title'],
                    'hot' => Resource_Service_Games::getSubscript($gameInfo['hot']),
                    'score' => $gameInfo['client_star'],
                    'freedl' => $gameInfo['freedl'],
                    'viewType' => 'GameDetailView',
                    'reward' => $gameInfo['reward'],
                    'attach' => ($attach) ? implode(',', $attach) : '',
                    'href' => Common::getWebRoot() . $this->actions['detailtUrl'] . '?from=gn&id=' . $gameInfo['id'] .
                    '&gname=' . $gameInfo['name'] . '&keyword=' .
                    '&intersrc=tsearch_gamedetail' . $gameInfo['id'] . '&t_bi=' . $this->getSource()
                );
                $gameList['gameItems'][] = $data;
            }
        }
        return $gameList;
    }

    private function handleMobileApiData($searchList) {
        foreach ($searchList['gamelist'] as $key => $value) {
            if ($searchList['from'] == 'gn') {
                $data = $this->getGameFormatData($value['id']);
                $data['from'] = $searchList['from'];
            } else {
                $data = array(
                    'from' => $searchList['from'],
                    'img' => urldecode($value['img']),
                    'name' => html_entity_decode($value['name']),
                    'resume' => html_entity_decode($value['resume']),
                    'package' => $value['package'],
                    'link' => $value['link'],
                    'gameid' => $value['id'],
                    'size' => $value['size'] . 'M',
                    'attach' => 0,
                    'category' => $value['category'],
                    'hot' => Resource_Service_Games::getSubscript($value['hot']),
                    'score' => 0,
                    'freedl' => $value['freedl'],
                    'viewType' => 'GameDetailView',
                    'reward' => $value['reward'],
                );
            }
            $gameList[] = $data;
        }
        return $gameList;
    }

    private function handleMobileApiH5Data($searchList) {
        $webroot = Common::getWebRoot();
        $i=($this->mOptionSearch['page']-1) * $this->perpage + ($this->mOptionSearch['page'] > 1 ? 1 : 0);
        foreach ($searchList['gamelist'] as $key => $value) {
            if ($searchList['from'] == 'gn') {
                $value = Resource_Service_GameData::getGameAllInfo($value['id']);
            }
            $href = urldecode($webroot . $this->actions['detailtUrl'] . '?stype=' . $this->getInput('stype') . '&from=' . $searchList['from'] . '&id=' . $value['id'] . '&gname=' . $value['name'] . '&keyword=' . $this->mOptionSearch['keyword'] . '&intersrc=' . $this->mOptionSearch['intersrc'] . '&t_bi=' . $this->getSource());
            $data = array(
                'id' => $value['id'],
                'from' => $searchList['from'],
                'name' => $value['name'],
                'category' => $value['category_title'],
                'resume' => html_entity_decode($value['resume'], ENT_QUOTES),
                'size' => $value['size'] . 'M',
                'link' => Util_Statist::getDownloadUrl($value['id'], $value['link'], '', 'isearchI'.$i),
                'alink' => urldecode($webroot . $this->actions['detailtUrl'] . '?stype=' . $this->getInput('stype') . '&from=' . $searchList['from'] . '&id=' . $value['id'] . '&gname=' . $value['name'] . '&keyword=' . $this->mOptionSearch['keyword'] . '&intersrc=isearchI'.$i.'_think_gamedetail' . $value['id'] . '&t_bi=' . $this->getSource()),
                'img' => ($value['from'] ? $value['img'] : urldecode($value['img'])),
                'profile' => $value['name'] . ',' . $href . ',' . $value['id'] . ',' . $value['link'] . ',' . $value['package'] . ',' . $value['size'] . ',' . 'Android' . $value['version'] . ',' . $value['min_resolution'] . '-' . $value['max_resolution'],
                'href' => Common::getWebRoot() . $this->actions['detailtUrl'] . '?from=' . $searchList['from'] . '&id=' . $value['id'] .
                '&gname=' . $value['name'] . '&keyword=' . $this->mOptionSearch['keyword'] .
                '&intersrc=isearchI'.$i.'_gamedetail'.$value['id'].'&t_bi=' . $this->getSource()
            );
            $i++;
            $gameList[] = $data;
        }
        return $gameList;
    }
    
    private function getGameFormatData($gameId) {
        if (! $gameId) {
            return false;
        }
        $gameList = Resource_Service_GameListData::getList(array($gameId));
        list($gameInfo) = Resource_Service_GameListFormat::output($gameList);
        return $gameInfo;
    }

}
