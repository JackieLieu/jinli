(function(a){a.fn.scrollLoading=function(b){var c={attr:"data-src",container:a(window),callback:a.noop};var d=a.extend({},c,b||{});d.cache=[];a(this).each(function(){var h=this.nodeName.toLowerCase(),g=a(this).attr(d.attr);
var i={obj:a(this),tag:h,url:g};d.cache.push(i);});var f=function(g){if(a.isFunction(d.callback)){d.callback.call(g.get(0));}};var e=function(){var g=d.container.height();
if(d.container.get(0)===window){contop=a(window)[0].scrollY;}else{contop=d.container.offset().top;}a.each(d.cache,function(m,n){var p=n.obj,j=n.tag,k=n.url,l,h;
if(p){l=p.offset().top-contop,h=l+p.height();if((l>=0&&l<g)||(h>0&&h<=g)){if(k){if(j==="img"){f(p.attr("src",k));}else{p.load(k,{},function(){f(p);});}}else{f(p);
}n.obj=null;}}});};e();d.container.bind("scroll",e);};a.init=function(){(function(){var i,h,g,f=Com.getClientVersion();if(!a(".game-list .J_openDetailView").length){return;
}Com.onEvent("gamestatus.listener");if(!Com.isVersionBiggerorEqual(f,"1.5.8")){return a(".J_openDetailView").find(".dow").hide();}a(".game-list .J_openDetailView").each(function(){if(!a(this).attr("data-packagename")){return;
}i=getDowData(a(this));h=Com.dowloadStatus({list:[i]});if(!h){return;}h=h.list[0];g=h.status;a(this).find("input").val(gameStatus[g][0]);a(this).attr("data-status",g);
});}());var b=document.getElementById("roll"),c=document.getElementById("copyRoll"),d=document.getElementById("rolls"),e;if(a("#roll li").length>5){c.innerHTML=b.innerHTML;
if(a("#roll li").length%2){a("#roll").append("<li>"+a("#roll li").eq(0).html()+"</li>");a("#copyRoll li").eq(0).remove();}setInterval(function(){if(c.offsetTop-d.scrollTop<=0||e==d.scrollTop){d.scrollTop-=b.offsetHeight+56;
}else{e=d.scrollTop;d.scrollTop++;}},80);}};})(Zepto);var action={network:"gamehall.hasnetwork",alert:"gamehall.alert",logout:"gamehall.clearlogin",finish:"gamehall.finish",islogin:"gamehall.islogin",login:"gamehall.account",account:"gamehall.account.info",};
var Const={ADDRESS_NULL:"请填写收货地址",CONSIGNEE_NULL:"请填写收货人姓名",PHONE_NULL:"手机号码不能为空",PHONE_ERROR:"手机号码格式错误",LOGOUT:"未登录",CHANGE_TEXT:"兑换",CHANGED_TEXT:"已兑换",CHANGE_ING_TEXT:"兑换中...",CHANGE_TEXT_NOW:"立即兑换",PHONE_REG:/^1[34578]{1}[0-9]{9}$/,MILLSECONDS:3000,};
var changeInfo={unchangeAble:1,changeAble:2,changed:3};var prizeType={entity:1,acoin:2,point:3};function getDowData(a){var b={};b.source=a.attr("data-source");
b.iconUrl=a.attr("data-iconUrl");b.gameId=a.attr("data-gameId");b.gameSize=a.attr("data-gameSize");b.gameName=a.attr("data-gameName");b.downUrl=a.attr("data-downUrl");
b.packageName=a.attr("data-packageName");return b;}function openPage(a){if(!a){return;}if(!(window.gamehall)){location.href=a.url;return;}switch(apiVersion){case"v1":openV1Page(a);
break;case"v2":openV2Page(a);break;case"v3":openV3Page(a);break;}}function openV1Page(b){var a={};if(b.type=="list"){a={type:"list",args:{title:b.title,url:b.url}};
}else{a={type:"detail",args:{title:b.title,url:b.url,gameid:b.gameId,downurl:b.downurl,packagename:b.packagename,filesize:b.filesize,sdkinfo:b.sdkinfo,resolution:b.resolution}};
}startActivity(a);}function openV2Page(c){var b={type:"",args:{}};if(c.type=="list"){var a={title:c.title,url:c.url};b.args=window.gamehall?{}:a;b.type="list";
b.args.newArgs={title:c.title,url:c.url};}else{if(c.type=="detail"){var a={title:"",url:"",gameid:"",downurl:"",packagename:"",filesize:"",sdkinfo:"",resolution:""};
b.args=window.gamehall?{}:a;b.type="detail";b.args.newArgs={title:c.title,url:c.url,gameId:c.gameId};}else{if(c.type=="giftDetail"){var a={title:"",url:"",gameid:"",downurl:"",packagename:"",filesize:"",sdkinfo:"",resolution:""};
b.args=window.gamehall?{}:a;b.type="detail";b.args.newArgs={title:"礼包详情",url:c.url,viewType:"GiftDetailView",gameId:c.gameId,giftId:c.giftId};}else{if(c.type=="browser"){b.type="browser";
b.args={url:c.url};}}}}startActivity(b);}function openV3Page(c){var b={};b.args={};var a=c.viewType;b.args.newArgs={viewType:a,param:{contentId:c.id,gameId:c.gameId,url:a=="WebView"?c.url:"",title:a=="WebView"?c.title:"",source:c.source,"package":c["package"]}};
b.type=c.type;console.log(JSON.stringify(b.args.newArgs));startActivity(b);}function startActivity(a){if(!window.gamehall){return;}var c=errCal=function(){},b=window.gamehall;
switch(a.type){case"list":b.startlistactivity(c,errCal,JSON.stringify(a.args));break;case"detail":b.startdetailsactivity(c,errCal,JSON.stringify(a.args));
break;case"browser":b.startlocalbrowser(c,errCal,JSON.stringify(a.args));break;}}function getUrlParam(a){var b=new RegExp("(^|&)"+a+"=([^&]*)(&|$)");var c=window.location.search.substr(1).match(b);
if(c!=null){return unescape(c[2]);}return null;}function getClientVersion(){var b="1.0.0";var a=getUrlParam("sp");if(a){b=a.split("_")[1];}return b;}function getApiVersionAndBranch(b){var d="v1",c="v1";
var a=b;if(compareVersion(a,"1.5.1")){d="v3";c="v3";if(compareVersion(a,"1.5.2")){c="v3_1";}if(compareVersion(a,"1.5.5")){c="v3_2";}}else{if(compareVersion(a,"1.4.8")){d="v2";
c="v2";}else{d="v1";c="v1";}}return{version:d,branch:c};}function compareVersion(c,b){if(c.indexOf(b)>-1){return true;}var e=c.split("."),f=b.split(".");
var a=Math.max(e.length,f.length);for(var d=0;d<a;d++){if(e[d]===undefined){return false;}if(f[d]===undefined){return true;}if(e[d]>f[d]){return true;}else{if(e[d]<f[d]){return false;
}}}return false;}function onEventAction(c,b){if(window.gamehall){var a=window.gamehall.onEvent(c,JSON.stringify(b));}else{if(c=c.alert){}}}function getValueAction(c,b){if(window.gamehall){var a=window.gamehall.getValue(c,JSON.stringify(b));
a=JSON.parse(a);return a;}}function getLoginInfo(){var b;var a=getValueAction(action.islogin,{});if(a){b={};b=getValueAction(action.account,{});}else{b=null;
}return b;}function openClientPage(e,i){var d=i.attr("data-id")||"",h=i.attr("data-gameId")||"",g=i.attr("data-title")||"",c=i.attr("data-url")||"",a=i.attr("data-source")||"",b=i.attr("data-packagename")||"";
var f={viewType:e,type:"list",id:d,gameId:h,title:g,url:c,source:a,"package":b};f.downurl=i.attr("data-downurl")||"";if(e=="GameDetailView"&&apiVersion=="v1"){f.type="detail";
f.packagename=i.attr("data-packagename")||"";f.filesize=i.attr("data-filesize")||"";f.sdkinfo=i.attr("data-sdkinfo")||"";f.resolution=i.attr("data-resolution")||"";
}console.log(f);openPage(f);}function showDialog(a){var b=a.parent(".J_dialog");a.siblings(".dialog-container").addClass("hidden");a.removeClass("hidden");
b.removeClass("hidden");$("body").bind("touchmove",function(c){c.preventDefault();c.stopPropagation();},false);}function clearLogin(){onEventAction(action.finish,{});
onEventAction(action.logout,{});onEventAction(action.login,{});}function postData(c,d,b,a){$.ajax({url:c,dataType:"json",type:"POST",data:d,timeout:10000,success:function(e){b(e);
setTimeout(function(){postLock=false;},Const.MILLSECONDS);},error:function(){postLock=false;}});}var Memory={list:[]};var currentPrizeEle=null;var puuid=getUrlParam("puuid");
var uname=getUrlParam("uname");var sp=getUrlParam("sp");var apkVersion=getClientVersion();var apiVersion=getApiVersionAndBranch(apkVersion).version;var haveChance=compareVersion(apkVersion,clientVersion);
var postLock=false;var Com=Common;var gameStatus={100:["下载","startDownload"],101:["下载中",""],102:["继续","resumeDownload"],103:["安装中",""],104:["安装","installGame"],105:["启动","openGame"],106:["升级","startDownload"],107:["重试","resumeDownload"],109:["下载","startDownload"],108:["下载",""],110:["升级","startDownload"]};
$(function(){$.init();$("img[data-src]").scrollLoading({callback:function(){$(this).removeAttr("data-src");}});function a(e,d){var b=Com.getClientVersion();
if(!Com.isVersionBiggerorEqual(b,"1.5.5")){return;}if(!haveChance){if(sp!=null&&window.gamehall){showDialog($(".J_update"));}else{return;}}else{var c=getLoginInfo();
if(c==null){clearLogin();}else{e(d);}}}$("body").on("click",".J_openWebview",function(c){if($(this).hasClass("J_checkStatus")){var b=$(this),d=function(){openClientPage("WebView",b);
};a(d);}else{openClientPage("WebView",$(this));}});$("body").on("click",".J_openDetailView img",function(c){var b=$(this).parents(".J_openDetailView"),d=getUrlParam("sp");
openClientPage("GameDetailView",b);});$(".J_update ").on("click",".J_openDetailView",function(b){openClientPage("GameDetailView",$(this));});$("body").on("click",".J_stapHide",function(b){$(".J_dialog").addClass("hidden");
$("body").unbind("touchmove");});$("body").on("click",".J_exchange",function(){var d=$(this),b=d.attr("data-value"),c=d.attr("data-bigImgSrc");currentPrizeEle=d;
var e=function(j){var f=j.attr("data-exchangeStatus");var i=j.attr("data-prizenum");var h=j.attr("data-prizeType"),k;if(h==prizeType.entity){k={type:"list",viewType:"WebView",id:"",gameId:"",title:j.attr("data-title"),url:j.attr("data-url"),source:j.attr("data-source")};
}if(h!=1&&i==0){return;}if(i==0&&f!=3){return;}if(f==changeInfo.unchangeAble){$.get("client/festival_activity/click",{puuid:puuid,uname:uname,sp:sp,exchange:0});
if(c){$(".J_stap").find(".J_poster").attr("src",c);}$(".J_stap").find(".J_msg").html(b);return showDialog($(".J_stap"));}else{if(f==changeInfo.changeAble){$.get("client/festival_activity/click",{puuid:puuid,uname:uname,sp:sp,exchange:1});
if(h==prizeType.entity){onEventAction(action.finish,{});openPage(k);}else{var g=j.attr("data-propNum");$(".J_num").html(g);$(".J_award").children("img").attr("src",j.attr("data-bigImgSrc"));
showBtnContainer();showDialog($(".J_award"));}}else{if(f==changeInfo.changed){if(h==prizeType.entity){openPage(k);}else{return;}}}}};a(e,d);});$("body").on("click",".J_virtual_exchange,.J_vritual_retry",function(b){if(postLock){return;
}if(!currentPrizeEle.length){return;}var c=getVirtualData();currentPrizeEle.children(".btn").html(Const.CHANGE_ING_TEXT);postLock=true;postData($(this).attr("data-ajaxUrl"),c,dlcCallback);
});$("body").on("click",".J_cancel_virtual_retry",function(b){if(!currentPrizeEle.length){return;}currentPrizeEle.children(".btn").html(Const.CHANGE_TEXT);
$(".J_dialog").addClass("hidden");$("body").unbind("touchmove");});$("body").on("click",".j_dow",function(f){var d=$(this).parents(".J_openDetailView"),g=getDowData(d),b=d.attr("data-status"),c={list:[g]};
Com.removeLetter(c.list,"gameSize");Memory.list.push(g);f.stopPropagation();if(gameStatus[b][1]){Com.onEvent(gameStatus[b][1],c);}});$("body").on("click",".J_entity_exchange,.J_entity_retry",function(c){if(postLock){return;
}var d=getEntityData();if(checkEntityDataValidate(d)){var b=$(".J_entity_exchange");b.html(Const.CHANGE_ING_TEXT);$(".J_error_tips").addClass("invisible");
showInutError();postLock=true;postData(b.attr("data-ajaxUrl"),d,entityCallback);}});$("body").on("click",".J_cancel_entity_retry",function(b){$(".J_entity_exchange").html(Const.CHANGE_TEXT_NOW);
$(".J_dialog").addClass("hidden");$("body").unbind("touchmove");});});function dlcCallback(b){if(b.success=="false"||!b.success){showFail();return;}b=b.data;
if(b.isLogin=="false"){clearlogin();}else{var a=parseInt(b.status,10);if(a){showSuccess();setTimeout(function(){window.location.reload();},Const.MILLSECONDS);
}else{showFail();}}}function setButStatus(m,d){var k=Com.dowloadStatus({list:Memory.list}),g,l,b,e=[],a,j,f,h=false;g=k.list;f=g.length;for(var c=0;c<f;
c++){b=g[c].status;a=g[c].packageName;l=$('[data-packagename="'+a+'"]');l.attr("data-status",b);l.find("input").val(gameStatus[b][0]);}}function entityCallback(b){showDialog($(".J_award"));
if(b.success=="false"||!b.success){showFail();return;}b=b.data;if(b.isLogin=="false"){clearlogin();}else{var a=parseInt(b.status,10);if(a){showSuccess();
setTimeout(function(){var d=location.search;var c=$(".J_entity_exchange").attr("data-redirecturl");location.href=c+d;},Const.MILLSECONDS);}else{showFail();
}}}function getVirtualData(){var a={token:token,festivalId:festivalId,prizeType:currentPrizeEle.attr("data-prizeType"),prizeId:currentPrizeEle.attr("data-prizeId"),puuid:puuid,uname:uname,sp:sp,contact:"",phone:"",address:""};
return a;}function getEntityData(){var b=$(".J_entity_exchange");var a={token:token,festivalId:festivalId,prizeType:b.attr("data-prizeType"),prizeId:b.attr("data-prizeId"),puuid:puuid,uname:uname,sp:sp,contact:$("#contact").val().trim(),phone:$("#phone").val().trim(),address:$("#address").val().trim()};
return a;}function checkEntityDataValidate(a){if(a.contact==""){showErrorTips(Const.CONSIGNEE_NULL);showInutError($("#contact"));return false;}if(a.phone==""){showErrorTips(Const.PHONE_NULL);
showInutError($("#phone"));return false;}else{if(!Const.PHONE_REG.test(a.phone)){showErrorTips(Const.PHONE_ERROR);showInutError($("#phone"));return false;
}}if(a.adress==""){showErrorTips(Const.ADDRESS_NULL);showInutError($("#address"));return false;}return true;}function showErrorTips(b){var a=$(".J_error_tips");
a.html(b);a.removeClass("invisible");}function showInutError(a){$("#contact").removeClass("error");$("#phone").removeClass("error");$("#address").removeClass("error");
if(a!=undefined&&a.length){a.addClass("error");}}function showBtnContainer(){$(".J_ex_fail").addClass("hidden");$(".J_ex_success").addClass("hidden");$(".J_num_tips").removeClass("hidden");
$(".J_btn_container").removeClass("hidden");}function showSuccess(){$(".J_ex_fail").addClass("hidden");$(".J_ex_success").removeClass("hidden");$(".J_num_tips").addClass("hidden");
$(".J_btn_container").addClass("hidden");}function showFail(){$(".J_ex_fail").removeClass("hidden");$(".J_ex_success").addClass("hidden");$(".J_num_tips").addClass("hidden");
$(".J_btn_container").addClass("hidden");}function setGameStatus(a,b){if(a=="gamehall.gameStatusChange"){setButStatus(a,b);}}