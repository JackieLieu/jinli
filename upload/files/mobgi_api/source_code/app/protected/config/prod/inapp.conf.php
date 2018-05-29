<?php

/**
 * @Encoding      :   UTF-8
 * @Author       :   hunter.fang
 * @Email         :   782802112@qq.com
 * @Time          :   2014-12-3 14:54:38
 * $Id: rtb.conf.php 62100 2014-12-3 14:54:38Z hunter.fang $
 */
//手机品牌映射表
$inappconfig['INAPP_MOBILE_MAKE']= array(
    'samsung'=>'三星',	
    'xiaomi'=>'小米',	
    'huawei'=>'华为',	
    'lenovo'=>'联想',	
    'bbk,vivo'=>'步步高',	 
    'coolpad,yulong'=>'酷派',	
    'oppo'=>'OPPO',	
    'htc'=>'HTC',	
    'zte'=>'中兴',	
    'gionee'=>'金立',	
    'sony'=>'索尼',	
    'meizu'=>'魅族',	
    'hisense'=>'海信',	
    'spread,sprd'=>'展讯',	
    'k-touch'=>'天语',	
    'motorola'=>'摩托罗拉',	
    'doov'=>'朵唯',	
    'lg'=>'LG',	
    'nubia'=>'NUBIA',	
    'apple'=>"苹果",
    '其它'=>'其它'	
    );

//导量配置设置　应用类型
$INAPP_CAT_APP = array(
    0=>array(
        60001=>'图书与工具书',
        60002=>'商务',
        60003=>'动漫',
        60004=>'通讯',
        60005=>'教育',
        60006=>'娱乐',
        60007=>'财务',
        60008=>'游戏',
        60009=>'健康与健身',
        60010=>'软件与演示',
        60011=>'生活时尚',
        60012=>'动态壁纸',
        60013=>'媒体与视频',
        60014=>'医药',
        60015=>'音乐与音频',
        60016=>'新闻杂志',
        60017=>'个性化',
        60018=>'摄影',
        60019=>'效率',
        60020=>'购物',
        60021=>'社交',
        60022=>'体育',
        60023=>'工具',
        60024=>'交通运输',
        60025=>'旅游与本地出行',
        60026=>'天气',
        60027=>'小部件',
        60028=>'游戏/街机与动作',
        60029=>'游戏/解谜问答',
        60030=>'游戏/纸牌与赌场',
        60031=>'游戏分类—休闲游戏',
        60032=>'游戏/动态壁纸',
        60033=>'游戏/竞速',
        60034=>'游戏/体育',
        60035=>'游戏/小部件'
    ),
    1=>array(
        '应用'=>array(
            '影音图像'=>array(
                10101=>'音乐播放',
                10102=>'拍照视频',
                10103=>'美化',
                10104=>'影音编辑',
                10105=>'图像编辑',
                10106=>'视频播放器',
                10107=>'在线视频',
                10108=>'k歌',
                10109=>'录音',
                10110=>'电台',
                10111=>'电视',
                10100=>'其它',
            ),
            '办公理财'=>array(
                10201=>'办公',
                10202=>'商务',
                10203=>'名片',
                10204=>'理财',
                10205=>'证券',
                10206=>'银行',
                10207=>'彩票',
                10208=>'记账',
                10200=>'其它',
            ),
            '系统软件'=>array(
                10301=>'性能检测',
                10302=>'系统',
                10303=>'插件',
                10304=>'输入法',
                10300=>'其它',

            ),
            '实用软件'=>array(
                10401=>'工具',
                10402=>'无线流量',
                10403=>'穿戴设备',
                10404=>'手电筒',
                10405=>'备份',
                10400=>'其它',
            ),
            '通讯辅助'=>array(
                10501=>'通讯',
                10502=>'聊天',
                10503=>'婚恋',
                10504=>'交友',
                10505=>'社交',
                10506=>'短信',
                10507=>'拨号',
                10508=>'邮箱',
                10500=>'其它',
            ),
            '网络应用'=>array(
                10601=>'浏览器',
                10602=>'WEB工具',
                10603=>'网盘',
                10604=>'微博工具',
                10605=>'论坛访问',
                10600=>'其它',
            ),
            '安全软件'=>array(
                10701=>'安全防护',
                10702=>'监控',
                10700=>'其它',
            ),
            '阅读图书'=>array(
                10801=>'阅读器',
                10802=>'有声读物',
                10803=>'图册',
                10804=>'漫画',
                10805=>'杂志',
                10806=>'新闻',
                10807=>'资讯',
                10808=>'报纸',
                10800=>'其它',
            ),
            '生活购物'=>array(
                10901=>'天气',
                10902=>'幽默',
                10903=>'优惠',
                10904=>'美食',
                10905=>'二维码',
                10906=>'日历',
                10907=>'购物',
                10908=>'支付比价',
                10909=>'时钟',
                10910=>'便民',
                10911=>'时尚',
                10912=>'电影票',
                10913=>'演唱会',
                10914=>'美容',
                10900=>'其它',
            ),
            '旅行地图'=>array(
                11001=>'地图',
                11002=>'导航',
                11003=>'出行',
                11004=>'旅游',
                11005=>'酒店',
                11006=>'票务',
                11007=>'火车票',
                11008=>'地铁',
                11009=>'公交',
                11010=>'机票',
                11000=>'其它',
            ),
            '学习教育'=>array(
                11101=>'翻译',
                11102=>'字典',
                11103=>'教育',
                11104=>'参考',
                11105=>'学习',
                11106=>'英文',
                11107=>'外语',
                11108=>'课程记事',
                11109=>'日程',
                11100=>'其它',
            ),
            '主题个性'=>array(
                11201=>'铃声',
                11202=>'来电',
                11203=>'锁屏',
                11204=>'主题',
                11205=>'壁纸',
                11206=>'桌面',
                11207=>'字体',
                11208=>'表情',
                11200=>'其它',
            ),
            '医疗健康'=>array(
                11301=>'健康',
                11302=>'医疗',
                11303=>'母婴',
                11304=>'养生',
                11305=>'运动',
                11300=>'其它',

            ),
            '其它'=>array(
                10000=>'其它',
            ),
        ),
        '游戏'=>array(
            '角色扮演'=>array(
                20101=>'RPG',
                20102=>'角色扮演',
                20103=>'RPG网游',
                20104=>'中国风',
                20105=>'日韩系',
                20106=>'冒险',
                20107=>'欧美系',
                20108=>'精美3D',
                20109=>'仙侠',
                20100=>'其它',
            ),
            '动作格斗'=>array(
                20201=>'格斗',
                20202=>'横版',
                20203=>'街机',
                20204=>'躲避',
                20200=>'其它',
            ),
            '体育竞技'=>array(
                20301=>'运动',
                20302=>'跑酷',
                20303=>'跳跃',
                20304=>'赛车',
                20305=>'摩托',
                20306=>'足球',
                20307=>'篮球',
                20308=>'台球',
                20309=>'极限',
                20300=>'其它',
            ),
            '射击飞行'=>array(
                20401=>'射击',
                20402=>'狙击',
                20403=>'坦克',
                20404=>'战争',
                20405=>'太空',
                20406=>'打飞机',
                20407=>'直升机',
                20400=>'其它',
            ),
            '策略回合'=>array(
                20501=>'策略',
                20502=>'经营',
                20503=>'养成',
                20504=>'三国',
                20505=>'防守',
                20506=>'塔防',
                20507=>'开罗',
                20500=>'其它',
            ),
            '冒险模拟'=>array(
                20601=>'冒险',
                20602=>'逃脱',
                20603=>'飞行模拟',
                20604=>'模拟经营',
                20600=>'其它',
            ),
            '休闲趣味'=>array(
                20701=>'休闲',
                20702=>'消除',
                20703=>'解密',
                20704=>'音乐',
                20705=>'物理',
                20706=>'捕鱼',
                20707=>'会说话',
                20700=>'其它',
            ),
            '棋牌益智'=>array(
                20801=>'麻将',
                20802=>'桌游',
                20803=>'棋类',
                20804=>'益智',
                20805=>'斗地主',
                20806=>'纸牌',
                20807=>'德州扑克',
                20809=>'网络卡牌',
                20800=>'其它',
            ),
            '其它'=>array(
                20000=>'其它',
            ),
        ),
        
    ),
    2=>array(
        1=>'美女两性',
        2=>'影音媒体',
        3=>'桌面美化',
        4=>'新闻阅读',
        5=>'图片漫画',
        6=>'生活服务',
        7=>'系统工具',
        8=>'虚拟定位',
        9=>'办公学习',
        10=>'积分兑换',
        11=>'角色扮演',
        12=>'动作街机',
        13=>'飞行射击',
        14=>'体育竞技',
        15=>'棋牌竞猜',
        16=>'经营策略',
        17=>'休闲益智',
        18=>'网络游戏'
    ),
);

//运营商
$inappconfig["INAPP_CARRIEROPERATOR"] = array(
        '1'=>'移动',
        '2'=>'联通',
        '3'=>'电信',
        '4'=>'其它',
);

//网络环境
$inappconfig["INAPP_NETWORK_ENVIRONMENT"] = array(
        "wifi" =>'WIFI网络',
        "2g" =>'2G',
        "3g" =>'3G',
        "4g" =>'4G',
        "unknow" =>'未知',
    //    2 =>'蜂窝数据网络 - 未知', //汇总到未知
);

//地理位置 
$inappconfig['INAPP_GEOGRAPHICAL_POSITION']= array(
        '北京市','天津市','重庆市','上海市','河北省','山西省','辽宁省','吉林省','黑龙江省','江苏省',
        '浙江省','安徽省','福建省','江西省','山东省','河南省','湖北省','湖南省','广东省','海南省',
        '四川省','贵州省','云南省','陕西省','甘肃省','青海省','台湾省',
        '内蒙古','广西','宁夏','新疆','西藏',
        '香港特别行政区','澳门特别行政区',
        '其它'
    );


$inappconfig['INAPP_GEOGRAPHICAL_POSITIONS'] = array(
    "安徽"=>array("合肥","芜湖","蚌埠","马鞍山","淮北","铜陵","安庆","黄山","滁州","宿州","池州","淮南","巢湖","阜阳","六安","宣城","亳州"),
    "北京"=>array("北京"),
    "重庆"=>array("重庆"),
    "福建"=>array("福州","厦门","莆田","三明","泉州","漳州","南平","龙岩","宁德"),
    "甘肃"=>array("兰州","嘉峪关","金昌","白银","天水","酒泉","张掖","武威","定西","陇南","平凉","庆阳","临夏","甘南"),
    "广东"=>array("广州","深圳","珠海","汕头","东莞","中山","佛山","韶关","江门","湛江","茂名","肇庆","惠州","梅州","汕尾","河源","阳江","清远","潮州","揭阳","云浮"),
    "广西自治区"=>array("南宁","柳州","桂林","梧州","北海","防城港","钦州","贵港","玉林","南宁地区","柳州地区","贺州","百色","河池"),
    "贵州"=>array("贵阳","六盘水","遵义","安顺","铜仁","黔西南","毕节","黔东南","黔南"),
    "海南"=>array("海南"),
    "河北"=>array("石家庄","邯郸","邢台","保定","张家口","承德","廊坊","唐山","秦皇岛","沧州","衡水"),
    "黑龙江"=>array("哈尔滨","齐齐哈尔","牡丹江","佳木斯","大庆","绥化","鹤岗","鸡西","黑河","双鸭山","伊春","七台河","大兴安岭"),
    "河南"=>array("郑州","开封","洛阳","平顶山","安阳","鹤壁","新乡","焦作","濮阳","许昌","漯河","三门峡","南阳","商丘","信阳","周口","驻马店","济源"),
    "湖北"=>array("武汉","宜昌","荆州","襄樊","黄石","荆门","黄冈","十堰","恩施","潜江","天门","仙桃","随州","咸宁","孝感","鄂州"),
    "湖南"=>array("长沙","常德","株洲","湘潭","衡阳","岳阳","邵阳","益阳","娄底","怀化","郴州","永州","湘西","张家界"),
    "江西"=>array("南昌","景德镇","九江","鹰潭","萍乡","新馀","赣州","吉安","宜春","抚州","上饶"),
    "江苏"=>array("南京","镇江","苏州","南通","扬州","盐城","徐州","连云港","常州","无锡","宿迁","泰州","淮安"),
    "吉林"=>array("长春","吉林","四平","辽源","通化","白山","松原","白城","延边"),
    "辽宁"=>array("沈阳","大连","鞍山","抚顺","本溪","丹东","锦州","营口","阜新","辽阳","盘锦","铁岭","朝阳","葫芦岛"),
    "内蒙古自治区"=>array("呼和浩特","包头","乌海","赤峰","呼伦贝尔盟","阿拉善盟","哲里木盟","兴安盟","乌兰察布盟","锡林郭勒盟","巴彦淖尔盟","伊克昭盟"),
    "宁夏自治区"=>array("银川","石嘴山","吴忠","固原"),
    "青海"=>array("西宁","海东","海南","海北","黄南","玉树","果洛","海西"),
    "山东"=>array("济南","青岛","淄博","枣庄","东营","烟台","潍坊","济宁","泰安","威海","日照","莱芜","临沂","德州","聊城","滨州","菏泽"),
    "上海"=>array("上海"),
    "山西"=>array("太原","大同","阳泉","长治","晋城","朔州","吕梁","忻州","晋中","临汾","运城"),
    "陕西"=>array("西安","宝鸡","咸阳","铜川","渭南","延安","榆林","汉中","安康","商洛"),
    "四川"=>array("成都","绵阳","德阳","自贡","攀枝花","广元","内江","乐山","南充","宜宾","广安","达川","雅安","眉山","甘孜","凉山","泸州"),
    "天津"=>array("天津"),
    "新疆自治区"=>array("乌鲁木齐","石河子","克拉玛依","伊犁","巴音郭勒","昌吉","克孜勒苏柯尔","克孜","博尔塔拉","吐鲁番","哈密","喀什","和田","阿克苏"),
    "西藏自治区"=>array("拉萨","日喀则","山南","林芝","昌都","阿里","那曲"),
    "云南"=>array("昆明","大理","曲靖","玉溪","昭通","楚雄","红河","文山","思茅","西双版纳","保山","德宏","丽江","怒江","迪庆","临沧"),
    "浙江"=>array("杭州","宁波","温州","嘉兴","湖州","绍兴","金华","衢州","舟山","台州","丽水"),
    "澳门特别行政区"=>array("澳门"),
    "香港特别行政区"=>array("香港"),
    "台湾"=>array("台湾"),
    "其它"=>array("其它"),
);

