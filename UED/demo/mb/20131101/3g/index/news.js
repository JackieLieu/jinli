(function(a){a.PathConfig();a.PathConfig._isConcat=false;a.app("GNnews",function(){return{version:"1.1"};});a.mix(GNnews,{init:function(){setTimeout(function(){GNnews.fetchData();GNnews.slider();},1500);$(".ui-search form").on("submit",function(f){f.preventDefault();var c=$(this).attr("action"),b=$(".inp-search").attr("name"),d=$(".inp-search").val(),g=c+"&"+b+"="+encodeURIComponent(d);window.location.href=g;});GNnews.gotop();GNnews.showCateMore();GNnews.baiduHots();},baiduHots:function(){var b=$("#api-baidu-hots").val();$.getJSON(b,function(c){$(".inp-search").val(c.data.top1);$(".in-hot-world").html(c.data.keywords);});},fetchData:function(){if(!$(".news-tab-item")){return;}var f=$(".news-hot-title").attr("remote"),e=$(".news-tab-item"),c,d,b=$(".news-tab");$.getJSON(f,function(g){var h='<a href="'+g.data.list.data[0].url+'">'+g.data.list.data[0].title+"</a>";$(".news-hot-title").html(h);});if(navigator.onLine){$.each(b,function(g,h){d=$(h).children().first().data("part");c=$("#J_news_tab"+d+" li").first().data("remote");GNnews.getNews(c,$("#J_news_tab_item"+g+" ul").first());});}$(".news-tab").delegate("li","click",function(g){var l=$(this),k,m,j=l.data("part"),i=l.data("num"),h=l.data("remote");k=$("#J_news_tab_item"+j+" ul");m=k.eq(i);l.parent().children().removeClass("sel");l.addClass("sel");k.addClass("ishide");m.removeClass("ishide");if(m.hasClass("done")){return;}if(navigator.onLine){GNnews.getNews(h,m);}});},getNews:function(c,d,b){$.ajax({type:"GET",url:c,dataType:"json",success:function(g){d.addClass("done").html("");var h=g.data.list.data,e=[],f;$.each(h,function(i,j){if(j.color==""){f='<li class="item"><a href="'+j.url+'">'+j.title+"</a></li>";}else{f='<li class="item"><a href="'+j.url+'" style="color:'+j.color+'">'+j.title+"</a></li>";}e.push(f);});if(g.data.moreUrl&&b==undefined){e.push('<li class="item list-more"><a href="'+g.data.moreUrl+'">更多资讯</a></li>');}d.append(e.join(""));}});},slider:function(){if(!$("#J_full_slider")){return;}var h=$("#J_full_slider");var e=h.find(".ui-slider-content"),f=e.find("li"),c=f.length,d=h.find(".ui-slider-handle span"),b=function(i){if(i==c){i=0;e.animate({left:0},0);}else{e.animate({left:(-f.width()*i)+"px"},500);}d.removeClass("on").eq(i).addClass("on");h.attr("curIndex",i);},g=function(i){var j=setTimeout(function(){if(i){e.width(f.width()*c);}var k=(parseInt(h.attr("curIndex"))||0)+1;b(k);g();},8000);};g(true);},gotop:function(){if(!$(".ui-goptop")){return;}window.onscroll=function(){if(document.body.scrollTop>50){$(".ui-gotop").show();}else{$(".ui-gotop").hide();}};$(".ui-gotop").on("click",function(){setTimeout(function(){window.scrollTo(0,1);},100);});},showCateMore:function(){if(!$(".J_news_more")){return;}$(".J_news_more").on("click",function(){$(".ui-toolbar-right .icon-arrow").toggleClass("icon-arrow-state");$(".news-menu-wrap").toggleClass("ishide");});}});GNnews.init();})(ICAT);