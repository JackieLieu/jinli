(function(a,b){a.app("Gapk",function(){a.PathConfig();function f(l,p,m,j,q,i,o){if(!l){return;}var k=l.split(","),n=GameApk.ApiVersion;if(!(window.gamehall||navigator.gamehall)){location.href=k[k.length-1];
return;}switch(n){case"v1":e(k);break;case"v2":c(k,p);break;case"v3":g(p,m,j,q,k,i,o);break;}}function e(k){var j={},i=k.length;if(i==2){j={type:"list",args:{title:k[0],url:k[1]}};
}else{j={type:"detail",args:{title:k[0],url:k[1],gameid:k[2],downurl:k[3],packagename:k[4],filesize:k[5],sdkinfo:k[6],resolution:k[7]}};}d(j);}function c(o,p){var l={type:"",args:{}},j=o.length;
if(p==0){var k={title:o[0],url:o[1]};l.args=window.gamehall?{}:k;l.type="list";if(j==2){l.args.newArgs={title:o[0],url:o[1]};}else{var m=[];for(var n=0;
n<j;n+=2){var q={};if(n==0){q.title="热门";}else{q.title=o[n];}q.viewType="Webview";q.url=o[n+1];m.push(q);}l.args.newArgs={title:o[0],items:m};}}else{if(p==1){var k={title:"",url:"",gameid:"",downurl:"",packagename:"",filesize:"",sdkinfo:"",resolution:""};
l.args=window.gamehall?{}:k;l.type="detail";if(j==2){l.args.newArgs={title:o[0],url:o[1],};}else{l.args.newArgs={title:o[0],url:o[1],gameId:o[2]};}}else{if(p==2){var k={title:"",url:"",gameid:"",downurl:"",packagename:"",filesize:"",sdkinfo:"",resolution:""};
l.args=window.gamehall?{}:k;l.type="detail";l.args.newArgs={title:"礼包详情",url:o[1],viewType:"GiftDetailView",gameId:o[2],giftId:o[3]};}else{if(p==-1||p==3){l.type="browser";
l.args={url:p==-1?o[0]:o[1]};}}}}d(l);}function g(p,m,k,r,l,i,o){var n={};n.args={};var j="",q="";if(m=="GameDetailView"){if(i==""){i="gltj"+k;}}if(m.toLowerCase()=="webview"||o.toLowerCase()=="webview"){j=l[l.length-1];
q=l[0];}n.args.newArgs={viewType:m,param:{contentId:k,gameId:r,url:j,title:q,source:i,}};if(o!=""){n.args.newArgs.param.subViewType=o;}if(p==0){n.type="list";
}else{if(p==1||p==2){n.type="detail";}else{if(p==3){n={};n.type="browser";n.args={url:p==-1?l[0]:l[1]};}}}d(n);}function d(i){var k=errCal=function(){},j=window.gamehall?window.gamehall:navigator.gamehall;
switch(i.type){case"list":j.startlistactivity(k,errCal,JSON.stringify(i.args));break;case"detail":j.startdetailsactivity(k,errCal,JSON.stringify(i.args));
break;case"browser":j.startlocalbrowser(k,errCal,JSON.stringify(i.args));break;}}var h={openMore:function(){b(".wrap").delegate(".J_openBtnWrap","click",function(){b(this).toggleClass("open").siblings("p").toggleClass("hidden");
});return this;},slidePic:function(){var j=b("#J_screenshot");var i=b(".pic-wrap");if(!j[0]){return this;}a.include(["/sys/lib/jquery/touchSwipe.js","./plugin/slidePic.js"],function(){j.slidePic(j.children().children().hasClass("J_arrow")?{slidePanel:".pic-wrap",slideItem:".pic-wrap span",specialWidth:true,isTouch:false,}:{slidePanel:".pic-wrap",slideItem:".pic-wrap span",specialWidth:true,});
h.displayImg();},true);return this;},loadMore:function(){if(!b(".J_loadMore")[0]){return;}var j,i=false;b(window).scroll(function(m){var q=document.body.clientHeight,l=document.documentElement.clientHeight,r=document.body.scrollTop;
var k=b(".J_loadMore"),p=k.attr("data-hasnext");var o=parseInt(k.attr("data-curpage"));var n=false;if(p==0||p=="false"){b(window).unbind("scroll");}else{if(i==true){return;
}if(Math.abs(q-l)<=r){k.show();i=true;b.ajax({type:"POST",url:k.attr("data-ajaxUrl"),data:{page:o+1,token:token},dataType:"json",success:function(v){i=false;
isNext=v.data.hasnext;k.attr("data-hasnext",v.data.hasnext).attr("data-curpage",v.data.curpage);var t=b(".J_gameItem ul"),u="",w="";if(GameApk.ApiVersion!="v1"){w='data-type="{data-type}" data-gameId="{game_id}" data-id="{id}" data-viewType="{viewType}"';
}strTemp="<li>												<a "+w+' data-infpage="{profile}">													<span class="icon"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>													<span class="desc">														<em>{name}</em>														<p><em>大小：{size}</em><em class="tips"></em></p>														<b>{resume}</b>													</span>												</a>											</li>',tdMerge=function(y,C,z){if(z){if(C.img&&C.img!=""){y=y.replace('<span class="pic"><img data-replace></span>','<span class="pic"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>');
}else{y=y.replace('<span class="pic"><img data-replace></span>',"");}}if(!a.isString(y)||!/\{|\}/g.test(y)){return false;}var A=y.match(/(\{[a-zA-Z]+-[a-zA-Z]+\})|(\{[a-zA-Z]+[a-zA-Z]+\})|(\{[a-zA-Z]+_[a-zA-Z]+\})/g);
if(!A.length){return false;}a.foreach(A,function(){var D=this.replace(/\{|\}/g,""),F=new RegExp("{"+D+"}"),E=C[D];y=y.replace(F,E!==undefined?E:(z?"{"+D+"}":""));
});if(GameApk.ApiVersion!="v1"&&((C.attach&&C.attach!="")||(C.device&&C.device!=0))){var s='<em class="tips">';if(C.attach){var B=C.attach.split(",");for(var x=0;
x<B.length;x++){if(B[x]=="礼"){s+='<span class="gift"></span>';}if(B[x]=="评"){s+='<span class="comment"></span>';}}if(C.device==1){s+='<span class="grip"></span>';
}}else{if(C.device==1){s+='<span class="grip"></span>';}}s+="</em>";y=y.replace('<em class="tips"></em>',s);}return y;};if(b(".J_stratgyItem")[0]){n=true;
t=b(".J_stratgyItem ul");strTemp="<li>													<a "+w+' data-infpage="{data-infpage}">														<div class="intro">															<span class="pic"><img data-replace></span><span class="title">																<b>{title}</b>																<em>{create_time}</em>															</span>														</div>														<div class="content">															<span class="desc">{resume}</span>														</div>													</a>												</li>';
}a.foreach(v.data.list,function(s,x){u+=tdMerge(strTemp,s,n);});t.append(u);h.lazyLoad(document.body,100);if(v.data.hasnext==0||v.data.hasnext=="false"){k.html('<span class="bottom">到底了，去其它页面看看吧</span>').show();
b(window).unbind("scroll");}else{k.hide();}},error:function(){i=false;}});}}});return this;},openPage:function(i){b("body").delegate("a[data-infpage]","click",function(){var o=b(this).attr("data-infpage")||"",k=parseInt(b(this).attr("data-type"),10),j=b(this).attr("data-viewType")||"",n=b(this).attr("data-source")||"",m=b(this).attr("data-subViewType")||"",p=b(this).attr("data-id"),l=b(this).attr("data-gameId");
if(GameApk.ApiVersion=="v1"){f(o);}else{f(o,k,j,p,l,n,m);}});b("body").delegate("a[data-action]","click",function(){if(!window.gamehall){return;}var k=b(this).attr("data-action"),j={};
if(k=="gamehall.forum"){j.url=b(this).attr("data-href");j.needLogin=true;}window.gamehall.onEvent(k,JSON.stringify(j));});},displayImg:function(){var i=b("#J_screenshot");
if(!i[0]){return this;}h.startimgActivity(i,true);},bigImg:function(){var i=b("#J_screenshot_off");if(!i[0]){return this;}h.startimgActivity(i,false);},startimgActivity:function(j,i){var m=j.find("img"),k=[];
m.each(function(n){k[n]=b(this).attr("data-bigpic");b(this).attr("data-index",n);});var l=function(){var n=b(this).attr("data-index"),o=[];o=o.concat(n,k);
var p=(window.gamehall)?window.gamehall:navigator.gamehall;if(p){p.startimagescanactivity(function(){a.log("正在为您跳转页面");},function(){},JSON.stringify({url:o.join("|")}));
}};if(i){m.swipe({click:l});}else{m.click(l);}},lazyLoad:function(j,i){var l=b(j).find('img[src$="blank.gif"],img[src$="blank1.gif"],img[src$="blankAvator.gif"]'),k=function(p){var n=p.getAttribute("data-src");
a.__IMAGE_CACHE=a.__IMAGE_CACHE||{};if(!n){return;}if(!a.__IMAGE_CACHE[n]){var m=new Image();m.src=n;m.onload=function(){p.src=n;a.__IMAGE_CACHE[n]=true;
b(p).attr("data-src",n);m=null;};}else{p.src=n;b(p).attr("data-src",n);}};a.foreach(l,function(n,m){i?setTimeout(function(){k(n);},m*i):k(m);});},openclose:function(){var j=b(".J_expand"),i=b(j.children("p")[0]),k=b(j.children("p")[1]),l=b(".J_content");
j.bind("click",function(){if(i.hasClass("hidden")){i.removeClass("hidden");k.addClass("hidden");l.removeClass("h-auto");}else{k.removeClass("hidden");i.addClass("hidden");
l.addClass("h-auto");}});}};return{init:function(){h.slidePic();h.openMore().loadMore();h.openPage();h.bigImg();h.lazyLoad(document.body,100);h.openclose();
}};});})(ICAT,jQuery);