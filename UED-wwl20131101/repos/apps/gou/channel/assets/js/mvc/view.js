(function(a,b){var e={partslist:{curIndex:2,ajaxUrl:""}};Gou.template={aHeader:'<div class="logo-search">				<h1>购物大厅</h1>				<a class="search" href="<%=url%>">				<div>				<h2>请输入商品名</h2>				<button></button>				</div>				</a>			</div>			<nav>				<ul>					<%for(var i=0, len=nav.length; i<len; i++){%>						<%if(nav[i].selected){%>						<li class="selected"><span><%=nav[i].name%></span></li>						<%}else{%>						<li><a href="<%=nav[i].link%>"><%=nav[i].name%></a></li>						<%}%>					<%}%>				</ul>			</nav>',bHeader:'<div class="title-back">					<h1><%=subtitle%></h1>					<div class="back"><a href="<%=homeurl%>"></a></div>				</div>				<%if(_self.data){%>				<nav>					<ul>						<%for(var i=0, len=data.length; i<len; i++){%>							<li<%if(data[i].selected){%> class="selected"<%}%>>								<a data-ajaxurl="<%=data[i].ajax_url%>"><%=data[i].title%></a>							</li>						<%}%>					</ul>				</nav>				<%}%>',recommend:'<section class="tj-webapp">			<%if(data.length){%>				<ul>					<%for(var i=0, len=data.length; i<len; i++){%>					<li><a href="<%=data[i].link%>"><img src="<%=blankPic%>" data-src="<%=data[i].img%>"></a></li>					<%}%>				</ul>			<%}%>			</section>',banner:'<section class="scroll-banner">				<div class="pic">					<ul>						<%for(var i=0, len=data.length; i<len; i++){%>						<li><a href="<%=data[i].link%>"><img src="<%=data[i].img%>" alt="<%=data[i].title%>"><span><%=data[i].title%></span></a></li>						<%}%>					</ul>				</div>				<div class="handle">					<%for(var j=0, len=data.length; j<len; j++){%>					<span<%if(j==0){%> class="on"<%}%>></span>					<%}%>				</div>			</section>',points:'<section class="jf-links">				<ul>					<%for(var i=0, len=data.length; i<len; i++){%>					<li><a href="<%=data[i]%>"></a></li>					<%}%>				</ul>			</section>',notice:"",icoItem:'<section class="ico-show">				<h2><%=title%></h2>				<%if(data.length){%>				<ul>					<%for(var i=0, len=data.length; i<len; i++){%>					<li>						<a href="<%=data[i].link%>">							<span><img src="<%=blankPic%>" data-src="<%=data[i].img%>"></span>							<em><%=data[i].name%></em>						</a>					</li>					<%}%>				</ul>				<%}%>			</section>',footer:/\=xiaolajiao/i.test(location.href)?'<footer class="ft">					<div class="help">						<span>官方社区：bbs.xiaolajiao.com</span>						<span>微信公共账号：yusunshouji</span>					</div>					<div class="copyright">						<p>copyright &copy; 2012 粤ICP备05087105号</p>					</div>				</footer>':'<footer class="ft">					<div class="help">						<span>官网QQ群：237057997</span>						<a href="tel:075582583525">联系客服</a>						<span>微信公共账号：金小号</span>					</div>					<div class="copyright">						<p>copyright &copy; 2012 粤ICP备05087105号</p>					</div>			</footer>',gotop:'<div class="gotop"><a href="javascript:window.scrollTo(0,1);">返回顶部</a></div>',codSearch:'<div class="cod-search">				<form action="<%=data.action%>"><input type="text" name="<%=data.name%>" placeholder="<%=data.keyword%>" /><button></button></form>			</div>',codList:'<%for(var i=0, ilen=data.list.length; i<ilen; i++){%>			<section class="item<%=(data.list[i].type_dir===1? " pic-right":" pic-left")%>">				<figure class="clearfix">					<div class="c-main">						<div class="pic">							<a href="<%=data.list[i].img_data.link%>"><img src="<%=blankPic%>" data-src="<%=data.list[i].img_data.img%>" /></a>							<div class="mask"><%=data.list[i].img_data.title%></div>						</div>					</div>					<div class="desc c-sub" style="background-color:<%=(data.list[i].color? data.list[i].color:"#f57766")%>"><a href="<%=data.list[i].link%>"><h3><%=data.list[i].type_name%></h3></a></div>				</figure>				<div class="detail">					<ul><%var codlinks=data.list[i].text_data; while(codlinks.length){%>						<li><a href="<%=codlinks[0].link%>" style="color:<%=codlinks[0].color%>"><%=codlinks[0].title%></a></li>					<%codlinks.shift();}%></ul>				</div>			</section>			<%}%>',picList:'<div class="list_item">				<ul>					<%for(var i=0, len=data.list.length; i<len; i++){%>					<li>						<a href="<%=data.list[i].link%>">							<div class="pic">								<img src="<%=blankPic%>" data-src="<%=data.list[i].img%>" alt="">							</div>							<div class="desc1"><%=data.list[i].descrip%></div>						</a>					</li>					<%}%>				</ul>			</div>',shopList:'<div class="list">				<ul>					<%for(var i=0, len=data.list.length; i<len; i++){%>					<li>						<a href="<%=data.list[i].shop_url%>">						<dl>							<dt><%=data.list[i].num%></dt>							<dd>								<p><%=data.list[i].title%></p>								<p class="level">									<img src="<%=data.list[i].credit_img%>" alt="<%=data.list[i].title%>" />								</p>							</dd>						</dl>							<div class="pic">								<%if(data.list[i].goods!==null){%>								<%for(var j=0, l=data.list[i].goods.length; j<l; j++){%><span><img src="<%=data.list[i].goods[j]%>" /></span><%}%>								<%}%>							</div>						</a>					</li>					<%}%>				</ul>			</div>'};var c=typeof t_bi!="undefined"?("t_bi="+t_bi):"",d=0;Gou.view=a.View.extend({config:{multiChild:true,dataSave:true},blankPic:a.PathConfig.appRef.replace(/assets\/js\//g,"pic/blank.gif")});Gou.view.setting={header:{config:{tempId:"aHeader",wrap:"#iHeader",multiChild:false,dataSave:false},nav:[{name:"推 荐",link:a.util.fullUrl("",c)},{name:"货到付款",link:a.util.fullUrl("/cod",c)},{name:"淘宝好店",link:a.util.fullUrl("/shop",c)}],url:"/search?refer="+escape(document.location.href)},subheader:{config:{tempId:"bHeader",wrap:"#iHeader",multiChild:false,dataSave:false},homeurl:a.util.fullUrl("",c)},banner:{config:{tempId:"banner",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/ad?"+c,delay:3000,hooks:{"&>0":[".J_scrollBanner","curIndex~0"],},callback:function(f,i){var g=$(".J_scrollBanner"),l=g.find(".pic ul"),n=l.find("li"),j=n.length,m=g.find(".handle span"),k=function(o){if(o==j){o=0;l.animate({left:0},0);}else{l.animate({left:(-n.width()*o)+"px"},500);}m.removeClass("on").eq(o).addClass("on");g.attr("curIndex",o);},h=function(o){var p=setTimeout(function(){if(o){l.width(n.width()*j);}var q=parseInt(g.attr("curIndex"))+1;k(q);h();},i.delay);};h(true);}}},points:{config:{tempId:"points",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/link",getBIData:function(g){var f=[];g.data.forEach(function(h){f.push(a.util.fullUrl(h,c));});g.data=f;return g;}}},notice:{config:{tempId:"notice",wrap:"#iScroll .panel"}},mall:{config:{tempId:"icoItem",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/mall"},title:"综合购物商城"},theme:{config:{tempId:"icoItem",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/theme"},title:"主题店"},tuan:{config:{tempId:"icoItem",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/tuan"},title:"团购&折扣"},helper:{config:{tempId:"icoItem",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"gou/helper"},title:"生活便民助手"},footer:{config:{tempId:"footer",wrap:"#iScroll .panel"}},gotop:{config:{tempId:"gotop",wrap:"#iScroll"}},codSearch:{config:{tempId:"codSearch",wrap:"#iScroll",multiChild:true,hooks:{"&":".cod-list"},ajaxUrl:"/api/"+Gou.platform.prefix+"cod/search",callback:function(){$("#iScroll").css("min-height",$(window).height()+"px");}}},list:{config:{tempId:"codList",wrap:"#iScroll",multiChild:true,ajaxUrl:"/api/"+Gou.platform.prefix+"cod/index",callback:function(g,f,h){Gou.view.setting.loadingData(g,f,h,1);}}},newlist:{config:{tempId:"picList",wrap:"#iScroll .panel",hooks:{"&<0":".new-wrap"},ajaxUrl:"/api/new/index",multiChild:true,callback:function(g,f,h){Gou.view.setting.loadingData(g,f,h,1);}}},partslist:{config:{tempId:"picList",wrap:"#iScroll .panel",hooks:{"&<0":".mall-wrap"},ajaxUrl:a.util.storage("typeIndex")||"/api/fitting/goods",events:[{selector:".J_partsNav li a",type:"singleTap",callback:"typeShow"},],multiChild:true,callback:function(g,f,h){Gou.view.setting.loadingData(g,f,h,2);}},typeShow:function(h,g){var j=$(this),f=$(this.parentNode),i=j.attr("data-ajaxurl");f.siblings().removeClass("selected");f.addClass("selected");a.util.storage("typeIndex",i);$("#iScroll").scrollTop(0).attr("data-ajaxurl",i);h.setAjaxUrl(i,false,true);}},loadingData:function(h,g,j,f){var i;switch(f){case 1:i=g.ajaxUrl;break;case 2:i=a.util.storage("typeIndex")||"/api/fitting/goods";break;case 3:i=g.ajaxUrl;break;}if(e.partslist.ajaxUrl!=i){e.partslist={curIndex:2,ajaxUrl:i,hasNext:undefined};}else{e.partslist.ajaxUrl=i;}e.partslist.splite=e.partslist.ajaxUrl.indexOf("?")>-1?"&page=":"?page=";if(e.partslist.hasNext===undefined){e.partslist.hasNext=j.data.hasnext;a.util.scroll(document,function(l,k,m){if(k+l>=m){if(e.partslist.hasNext===true){e.partslist.hasNext=false;if(!e.partslist.loading){e.partslist.loading=true;$(h).append('<div class="loading"><i class="icon-img"></i><span class="icon-label">正在加载中...</span></div>');}else{$(h).find(".loading").show();}a.util.ajax({url:e.partslist.ajaxUrl+e.partslist.splite+e.partslist.curIndex,success:function(n){if(n.success){n.blankPic=a.PathConfig.appRef.replace(/assets\/js\//g,"pic/blank.gif");e.partslist.curIndex=n.data.curpage+1;e.partslist.hasNext=n.data.hasnext;setTimeout(function(){a.View[g.viewId].setData(n,false,false);$(h).find(".loading").hide();},1000);}}});}}});}},shopList:{config:{tempId:"shopList",wrap:"#iScroll .panel",ajaxUrl:"/api/"+Gou.platform.prefix+"shop/index",hooks:{"&<0":["data-hasnext~true","data-curpage~1","data-ajaxurl~/api/"+Gou.platform.prefix+"shop/index"]},callback:function(g,f,h){Gou.view.setting.loadingData(g,f,h,1);}}}};})(ICAT,window);