(function(){var c={expand_tips:'展开<i class="icon-arrow-down"></i>',show_tips:'收起<i class="icon-arrow-up"></i>',underPoints:"您的积分不足",underStock:"库存不足",phoneIllegal:"请输入合法的联系电话",numOverflow:"当前兑换数量不足",numError:"兑换数量必须为数值类型",exchangeSuccess:"兑换成功",otherError:"网络异常，请稍候重试",notLogin:"未登录，请先登录",nameError:"收货人不能为空",phoneError:"请输入合法的联系电话",addressError:"收货地址不能为空",exchangeFail:"兑换失败",unshelve:"商品已下架",postError:"请求异常，请刷新重试",timeOut:"请求超时"};
var a={network:"gamehall.hasnetwork",alert:"gamehall.alert",logout:"gamehall.clearlogin",finish:"gamehall.finish",islogin:"gamehall.islogin",getPoint:"gamehall.daily.task",login:"gamehall.account",statis:"gamehall.sendstatis",welfare:"gamehall.welfare",boradcast:"gamehall.money.change",getServerId:"gamehall.getserverid",};
var b={locked:false,getUrlParam:function(d){var e=new RegExp("(^|&)"+d+"=([^&]*)(&|$)");var f=window.location.search.substr(1).match(e);if(f!=null){return unescape(f[2]);
}return null;},lazyLoad:function(e,d){var f={};var h=$(e).find('img[src$="blank.gif"],img[src$="blankAvator.gif"]'),g=function(k){var j=k.getAttribute("data-src");
f.__IMAGE_CACHE=f.__IMAGE_CACHE||{};if(!j){return;}if(!f.__IMAGE_CACHE[j]){var i=new Image();i.src=j;i.onload=function(){k.src=j;f.__IMAGE_CACHE[j]=true;
$(k).attr("data-src",j);i=null;};}else{k.src=j;$(k).attr("data-src",j);}};h.each(function(k,j){d?setTimeout(function(){g(j);},k*d):g(j);});},openPageByViewType:function(){$("body").delegate("[data-infpage]","click",function(){var g=$(this).attr("data-infpage").split(",");
if(!(window.gamehall||navigator.gamehall)){location.href=g[1];return;}var k=$(this).attr("data-type"),h=$(this).attr("data-viewType"),d=$(this).attr("data-source")||"",e=g[1],l=g[0];
var j={};j.args={},j.type="list";j.args.newArgs={viewType:h,param:{url:e,title:l,source:d}};var f=errCal=function(){},i=window.gamehall?window.gamehall:navigator.gamehall;
i.startlistactivity(f,errCal,JSON.stringify(j.args));});},onEventAction:function(f,e){if(window.gamehall){var d=window.gamehall.onEvent(f,JSON.stringify(e));
}else{if(f="gamehall.alert"){}}},getValueAction:function(f,e){if(window.gamehall){var d=window.gamehall.getValue(f,JSON.stringify(e));d=JSON.parse(d);return d;
}},expand:function(){$(".J-expand").click(function(){var d=$(this).html()==c.expand_tips?true:false;if(d){$(this).html(c.show_tips);$(".J_content").addClass("h-auto");
}else{$(this).html(c.expand_tips);$(".J_content").removeClass("h-auto");}});},scroll:function(){if($(".J_ac_anno").length){$(document).on({scroll:function(){d();
}});function d(){var f=$(".J_ac_anno").height(),g=window.scrollY;if(g>f){$(".J_ac_anno").addClass("ac-fixed");}else{$(".J_ac_anno").removeClass("ac-fixed");
}}}if($(".J_loadMore").length){var e=$(window).height();$(document).on({scroll:function(){var h=$(".J_loadMore").attr("data-hasnext");if(h=="false"||h==0){return;
}if(b.locked){return;}var i=document.body.clientHeight,f=document.documentElement.clientHeight,j=document.body.scrollTop;if(Math.abs(i-f)<=j){var g=b.getValueAction(a.network,{});
if(g==false){b.onEventAction(a.alert,{alertStr:c.otherError});return;}b.getAjaxData();}}});}},moreWelfare:function(){$("#more").click(function(){b.onEventAction(a.welfare,{});
});},getAjaxData:function(){var k=$(".J_loadMore");k.removeClass("invisible");b.locked=true;var h=k.attr("data-ajaxurl"),g=parseInt(k.attr("data-curpage"),10);
var j=$("#coinList").length?true:false,i,e;var d=$("#playerList").length?true:false;var f=$("#goodsList").length?true:false;i={page:g+1,token:token,};if(j){i={page:g+1,token:token,uuid:uuid};
}if(f){i={page:g+1,token:token,puuid:b.getUrlParam("puuid"),sp:b.getUrlParam("sp"),uname:b.getUrlParam("uname")};}$.ajax({type:"POST",url:h,dataType:"json",data:i,success:function(o){b.locked=false;
var t;if(!o||(typeof o=="string")||!o.success){k.addClass("invisible");var l=c.postError;if(o&&o.msg){l=o.msg;}b.onEventAction(a.alert,{alertStr:l});return;
}var r=o.data;hasNext=r.hasNext,g=r.curPage,html="";if(j){e=$("#coinList");for(var p=0,q=r.list.length;p<q;p++){var m=r.list[p],s="",n="";if(m.status=="no"){n="即将生效";
}if(m.status=="available"){if(m.leftMount==0){n="已使用";s="disable";}else{n="可用"+m.leftMount;}}if(m.status=="outdate"){n="已过期";s="disable";}html+='<li class="'+s+'">									<div class="toLeft">										<span class="mount">'+m.chargeMount+'</span>										<span class="status">'+n+'</span>									</div>									<div class="toRright">										<span class="origion">'+m.origin+'</span>										<span class="riqi">'+m.startDate+"至"+m.endDate+"</span>									</div>								</li>";
}}if(d){e=$("#playerList");for(var p=0,q=r.list.length;p<q;p++){html+="<li class='tline'>"+r.list[p]["no"]+"."+r.list[p]["nickname"]+"</li>";}}if(f){e=$("#goodsList ul");
for(var p=0,q=r.list.length;p<q;p++){var m=r.list[p];if(m.discount){html+='<li>									<a data-type="0" data-viewtype="WebView" data-source="prizeexchange" data-infpage="'+m.title+","+m.redirectLink+'">										<div class="goods-list window-discount-title rem-border-1px">										<i class="bg-discount-title"><div>限时<span>'+m.discount+'</span>折</div></i>										<img src="http://assets.gionee.com/apps/game/apkv1/pic/blank.gif" alt="" data-src="'+m.img+'">											<span class="consume-points">消耗积分：<em><strike>'+m.consumePoints+'</strike></em></span>											<span class="enough-points">只需积分：<em>'+m.enoughPoints+'</em></span>											<span class="exchange-num">剩余数量：<em>'+m.remaindNum+"</em></span>										</div>									</a>									</li>";
}else{html+='<li>										<a data-type="0" data-viewtype="WebView" data-source="prizeexchange" data-infpage="'+m.title+","+m.redirectLink+'">											<div class="goods-list rem-border-1px">												<img src="http://assets.gionee.com/apps/game/apkv1/pic/blank.gif" alt="" data-src="'+m.img+'">												<span class="consume-points">消耗积分：<em>'+m.consumePoints+'</em></span>												<span class="exchange-num">剩余数量：<em>'+m.remaindNum+"</em></span>											</div>										</a>								</li>";
}}}setTimeout(function(){if(hasNext==0||hasNext=="false"){k.html('<span class="bottom">到底了，去其它页面看看吧</span>');if(f){var u=b.getUrlParam("sp").split("_")[1];
if(b.isVersionBiggerorEqual(u,"1.5.9")){$(".J_loadMore .bottom").attr("style","background-image:url()");}}}else{k.addClass("invisible");}e.append(html);
k.attr("data-hasnext",hasNext);k.attr("data-curpage",g);b.lazyLoad(e[0],100);},100);},error:function(){b.locked=false;k.addClass("invisible");}});},checkLoginStatus:function(){if(typeof isLogin=="undefined"){return;
}if(isLogin!=undefined&&isLogin=="false"){b.onEventAction(a.finish,{});b.onEventAction(a.logout,{});b.onEventAction(a.login,{});}},dialogShow:function(d){$("body").bind("touchmove",function(f){f.preventDefault();
},false);var e=(3-document.body.scrollTop)+"px";d.show().animate({display:"block",bottom:e,},300,function(){$(".J_dialog").removeClass("invisible");});
},dialogHide:function(e){$("body").unbind("touchmove");var d=e.height();e.animate({display:"block",bottom:-d,},300,function(){$(this).hide();$(".J_dialog").addClass("invisible");
});},getServerId:function(){var d=b.getUrlParam("sp").split("_")[1];var e="";if(b.isVersionBiggerorEqual(d,"1.5.7")){e=b.getValueAction(a.getServerId,{url:location.href}).serverId;
}return e;},isVersionBiggerorEqual:function(f,e){if(f.indexOf(e)>-1){return true;}var h=f.split("."),j=e.split(".");var d=Math.max(h.length,j.length);for(var g=0;
g<d;g++){if(h[g]===undefined){return false;}if(j[g]===undefined){return true;}if(h[g]>j[g]){return true;}else{if(h[g]<j[g]){return false;}}}return false;
},pointExAcoin:function(){var e=$("#exAcoinBtn");if(!e[0]){return;}var j=b.getUrlParam("goodId"),g=b.getUrlParam("puuid"),d=b.getUrlParam("uname"),h=b.getUrlParam("sp"),i=h.split("_"),f=i[i.length-1];
ajaxUrl=e.attr("data-ajaxurl");e.click(function(){var k=$(".J_loading");b.dialogShow(k);b.onEventAction(a.boradcast,{});var l={action:"exchange",object:"goods"+j};
b.onEventAction(a.statis,l);$.ajax({type:"POST",url:ajaxUrl,dataType:"json",timeout:5000,data:{goodId:j,puuid:g,uname:d,token:token,serverId:b.getServerId(),imei:f,sp:h},success:function(o){var n;
if(!o||(typeof o=="string")||!o.success){b.dialogHide(k);var q=c.postError;if(o&&o.msg){q=o.msg;}b.onEventAction(a.alert,{alertStr:q});return;}var p=o.data;
if(p.isLogin!=undefined&&p.isLogin==false){b.onEventAction(a.finish,{});b.onEventAction(a.logout,{});b.onEventAction(a.login,{});return;}if(p.exchangeStatus){var m=p.exchangeStatus;
switch(parseInt(m,10)){case 1:n=c.exchangeSuccess;break;case 2:n=c.underPoints;break;case 3:n=c.exchangeFail;break;case 4:n=c.underStock;break;case 5:n=c.underStock;
break;case 6:n=c.unshelve;break;}}setTimeout(function(){b.onEventAction(a.alert,{alertStr:n});b.dialogHide(k);setTimeout(function(){if(parseInt(m,10)==6){location.href=p.url;
}else{location.reload();}},1500);},300);},error:function(n,o){b.dialogHide(k);var m=c.otherError;if(o=="timeout"){m=c.timeOut;}b.onEventAction(a.alert,{alertStr:m});
return;}});});},pointExEntity:function(){var e=$("#exEntityBtn");if(!e[0]){return;}var l=b.getUrlParam("goodId"),g=b.getUrlParam("puuid"),k=b.getUrlParam("uname"),h=e.attr("data-ajaxurl"),j=$(this).attr("data-ajaxUrl"),d=b.getUrlParam("sp"),f=d.split("_"),i=f[f.length-1];
dialog=$(".J_loading");e.click(function(){var o=$("#name").val().trim(),n=$("#phone").val().trim(),m=$("#address").val().trim();if($.isEmptyObject(o)){b.onEventAction("gamehall.alert",{alertStr:c.nameError});
return;}var p=/^1[34578]{1}[0-9]{9}$/;if(!p.test(n)){b.onEventAction("gamehall.alert",{alertStr:c.phoneError});return;}if($.isEmptyObject(m)){b.onEventAction("gamehall.alert",{alertStr:c.addressError});
return;}b.dialogShow(dialog);b.onEventAction(a.boradcast,{});var q={action:"exchange",object:"goods"+l};b.onEventAction(a.statis,q);$.ajax({type:"POST",url:h,dataType:"json",timeout:5000,data:{goodId:l,puuid:g,uname:k,token:token,exchangeNums:1,receiverphone:n,receiver:o,address:m,serverId:b.getServerId(),imei:i,sp:d},success:function(t){var s;
if(!t||(typeof t=="string")||!t.success){b.dialogHide(dialog);var v=c.postError;if(t&&t.msg){v=t.msg;}b.onEventAction(a.alert,{alertStr:v});return;}var u=t.data;
if(u.isLogin!=undefined&&u.isLogin==false){b.onEventAction(a.finish,{});b.onEventAction(a.logout,{});b.onEventAction(a.login,{});return;}if(u.exchangeStatus){var r=u.exchangeStatus;
switch(parseInt(r,10)){case 1:s=c.exchangeSuccess;break;case 2:s=c.underPoints;break;case 3:s=c.exchangeFail;break;case 4:s=c.underStock;break;case 5:s=c.underStock;
break;case 6:s=c.unshelve;break;}}setTimeout(function(){b.onEventAction(a.alert,{alertStr:s});b.dialogHide(dialog);setTimeout(function(){if(parseInt(r,10)==6){location.href=u.url;
}else{location.reload();}},1500);},300);},error:function(s,t){b.dialogHide(dialog);var r=c.otherError;if(t=="timeout"){r=c.timeOut;}b.onEventAction(a.alert,{alertStr:r});
return;}});});},setDialogHeight:function(){if($("#myAcoin").length>0){return;}$(".J_dialog").height($(document).height());var f=[".J_loading"];for(var e=0,d=f.length;
e<d;e++){var g=$(f[e]);g.css("bottom",-g.height());g.removeClass("invisible").hide();}},init:function(){this.checkLoginStatus();this.pointExAcoin();this.pointExEntity();
this.setDialogHeight();this.scroll();this.openPageByViewType();this.expand();this.moreWelfare();this.lazyLoad(document.body,100);}};$(function(){b.init();
if(isVersion("1.6.0")){$(".J_loadMore").addClass("loading1-6-0");}FastClick.attach(document.body);});})();var urlParam=function(a){var b=new RegExp("(^|&)"+a+"=([^&]*)(&|$)");
var c=window.location.search.substr(1).match(b);if(c!=null){return unescape(c[2]);}return null;};function isVersion(d){var c="1.0.0";var b=urlParam("sp");
if(b){c=b.split("_")[1];}if(c.indexOf(d)>-1){return true;}var f=c.split("."),g=d.split("."),a=Math.max(f.length,g.length);for(var e=0;e<a;e++){if(f[e]===undefined){return false;
}if(g[e]===undefined){return true;}if(f[e]>g[e]){return true;}else{if(f[e]<g[e]){return false;}}}return false;}