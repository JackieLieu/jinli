(function(a,b){a.app("Gapk",function(){a.PathConfig();function c(h,g){if(!h||h.indexOf(",")==-1){return;}var f=h.split(","),e=f.length;if(navigator.gamehall){e==2?navigator.gamehall.startlistactivity(function(){},function(){},{title:f[0],url:f[1]}):navigator.gamehall.startdetailsactivity(function(){},function(){},{title:f[0],url:f[1],gameid:f[2],downurl:f[3],packagename:f[4],filesize:f[5],sdkinfo:f[6],resolution:f[7]});}else{location.href=f[1];}}var d={openMore:function(){b(".wrap").delegate(".J_openBtnWrap","click",function(){b(this).toggleClass("open").siblings("p").toggleClass("hidden");});return this;},slidePic:function(){var f=b("#J_screenshot");var e=b(".pic-wrap");if(!f[0]){return this;}a.include(["touchSwipe","./plugin/slidePic.js"],function(){f.slidePic(f.children().children().hasClass("J_arrow")?{slidePanel:".pic-wrap",slideItem:".pic-wrap span",specialWidth:true,isTouch:false,}:{slidePanel:".pic-wrap",slideItem:".pic-wrap span",specialWidth:true,});d.displayImg();},true);return this;},loadMore:function(){if(!b(".J_loadMore")[0]){return;}var f,e=false;b(window).scroll(function(i){var m=document.body.clientHeight,h=document.documentElement.clientHeight,n=document.body.scrollTop;var g=b(".J_loadMore"),l=g.attr("data-hasnext");var k=parseInt(g.attr("data-curpage"));var j=false;if(l==0||l=="false"){b(window).unbind("scroll");}else{if(e==true){return;}if(Math.abs(m-h)<=n+50){g.show();e=true;b.ajax({type:"POST",url:g.attr("data-ajaxUrl"),data:{page:k+1,token:token},dataType:"json",success:function(q){e=false;isNext=q.data.hasnext;g.attr("data-hasnext",q.data.hasnext).attr("data-curpage",q.data.curpage);var o=b(".J_gameItem ul"),p="",r="";if(GameApk.ApiVersion!="v1"){r='data-type="{data-type}"';}strTemp="<li>												<a "+r+' data-infpage="{profile}">													<span class="icon"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>													<span class="desc">														<em>{name}</em>														<p><em>大小：{size}</em><em class="tips1"></em></p>														<b>{resume}</b>													</span>												</a>											</li>',tdMerge=function(v,z,w){if(w){if(z.img&&z.img!=""){v=v.replace('<span class="pic"><img data-replace></span>','<span class="pic"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>');}else{v=v.replace('<span class="pic"><img data-replace></span>',"");}}if(!a.isString(v)||!/\{|\}/g.test(v)){return false;}var x=v.match(/(\{[a-zA-Z]+-[a-zA-Z]+\})|(\{[a-zA-Z]+[a-zA-Z]+\})|(\{[a-zA-Z]+_[a-zA-Z]+\})/g);if(!x.length){return false;}a.foreach(x,function(){var t=this.replace(/\{|\}/g,""),B=new RegExp("{"+t+"}"),A=z[t];v=v.replace(B,A!==undefined?A:(w?"{"+t+"}":""));});if(GameApk.ApiVersion!="v1"&&((z.attach&&z.attach!="")||(z.device&&z.device!=0))){var s='<em class="tips1">';if(z.attach){var y=z.attach.split(",");for(var u=0;u<y.length;u++){if(y[u]=="礼"){s+='<span class="gift"></span>';}if(y[u]=="评"){s+='<span class="comment"></span>';}}if(z.device==1){s+='<span class="grip"></span>';}}else{if(z.device==1){s+='<span class="grip"></span>';}}s+="</em>";v=v.replace('<em class="tips1"></em>',s);}return v;};if(b(".J_giftItem")[0]){o=b(".J_giftItem ul");strTemp="<li>													<a "+r+' data-infpage="{data-infpage}">														<span class="icon"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>														<span class="desc">															<em>{title}</em>															<p><em >{giftNum}</em></p>														</span>													</a>												</li>';}else{if(b(".J_stratgyItem")[0]){j=true;o=b(".J_stratgyItem ul");strTemp='<li>													<a data-type="1" data-infpage="{data-infpage}">														<div class="intro">															<span class="pic"><img data-replace></span><span class="title">																<b>{title}</b>																<em>{create_time}</em>															</span>														</div>														<div class="content">															<span class="desc">{resume}</span>														</div>													</a>												</li>';}}a.foreach(q.data.list,function(s,t){p+=tdMerge(strTemp,s,j);});o.append(p);d.lazyLoad(document.body,100);if(q.data.hasnext==0||q.data.hasnext=="false"){g.html('<span class="bottom">到底了，去其它页面看看吧</span>').show();b(window).unbind("scroll");}else{g.hide();}},error:function(){e=false;}});}}});return this;},openPage:function(e){b("body").delegate("a[data-infpage]","click",function(){var f=b(this).attr("data-infpage")||"";c(f);});},displayImg:function(){var f=b("#J_screenshot"),g=f.find("img");if(!f[0]){return this;}var e=[];g.each(function(h){e[h]=b(this).attr("data-bigpic");b(this).attr("data-index",h);});g.swipe({click:function(){var h=b(this).attr("data-index");var i=[];i=i.concat(h,e);if(navigator.gamehall){navigator.gamehall.startimagescanactivity(function(){a.log("正在为您跳转页面");},function(){},{url:i.join("|")});}}});},lazyLoad:function(f,e){var h=b(f).find('img[src$="blank.gif"],img[src$="blank1.gif"]'),g=function(k){var j=k.getAttribute("data-src");a.__IMAGE_CACHE=a.__IMAGE_CACHE||{};if(!j){return;}if(!a.__IMAGE_CACHE[j]){var i=new Image();i.src=j;i.onload=function(){k.src=j;a.__IMAGE_CACHE[j]=true;b(k).attr("data-src",j);i=null;};}else{k.src=j;b(k).attr("data-src",j);}};a.foreach(h,function(k,j){e?setTimeout(function(){g(k);},j*e):g(j);});}};return{init:function(){d.slidePic();d.openMore().loadMore();d.openPage();d.lazyLoad(document.body,100);}};});})(ICAT,jQuery);