(function(a){a.fn.extend({SlideImg:function(r){var p={scroll:".ui-slide-scroll",scrollItem:".ui-slide-item",tabs:".ui-slide-tabs>.ui-slide-tab",loop:false,auto:false,circleTime:500,css3:false,prev:".ui-slide-prev",next:".ui-slide-next",curTabClass:"ui-slide-tabcur",beforeSlide:function(){},afterSlide:function(){}},d=a.extend(p,r);var k=a(this),m=k.find(d.scroll),q=k.find(d.scrollItem),l=k.find(d.tabs),f=k.find(d.prev),g=k.find(d.next);if(!k[0]){return}var c=null,n=q.width(),b=0,j=q.length,e={autoplay:function(){c=setTimeout(function(){b=b>=j?0:b+1;e.slide(b);e.autoplay()},5000)},slide:function(o){d.beforeSlide();if(d.css3){m.css("-webkit-transition","all "+d.circleTime/1000+"s").get(0).style.webkitTransform="translateX("+(-o*q.width())+"px)";if(o>=j){b=0;m.get(0).style.webkitTransform="translateX(0px)"}else{b=o}e.reset(b);d.afterSlide()}else{m.animate({left:-o*q.width()+"px"},d.circleTime,function(){if(o>=j){b=0;m.css({left:"0px"})}else{b=o}e.reset(b);d.afterSlide()})}if(o<=0){f.addClass("ui-slide-prevdis")}else{if(0<o<j){f.removeClass("ui-slide-prevdis")}}if(o==j-1){g.addClass("ui-slide-nextdis")}else{if(o<j-1){g.removeClass("ui-slide-nextdis")}}if(d.auto&&c==null){e.autoplay()}},reset:function(o){l.removeClass(d.curTabClass).eq(o).addClass(d.curTabClass)}};m.width(n*j*(d.css3?1:2));if(d.loop&&!d.css3){m.append(m.html())}if(d.auto){e.autoplay()}if(f[0]){f.bind("click",function(){if(b>0){e.slide(b-1)}})}if(g[0]){g.bind("click",function(){if(b<(j-1)){e.slide(b+1)}})}if(l[0]){l.bind("click",function(){var o=this,s=l.index(o);if(c){clearTimeout(c);c=null}e.slide(s)})}m.swipe({swipeLeft:i,swipeRight:h});function i(o,u,v,t,s){if(c){clearTimeout(c);c=null}if(v>10){if(b==j-1){d.loop?e.slide(b+1):e.slide(b)}else{e.slide(b+1)}}}function h(o,u,v,t,s){if(c){clearTimeout(c);c=null}if(v>10){b<=0?e.slide(b):e.slide(b-1)}}if(d.css3){setInterval(function(){var o=q.width();m.width(o*j*(d.css3?1:2));m.get(0).style.webkitTransform="translateX("+(-b*o)+"px)"},100)}else{a(window).bind("resize",function(){var o=q.width();m.width(o*j*(d.css3?1:2));m.css({left:-b*o+"px"})})}}})})(jQuery);
