/*
 * \u8868\u5355\u76f8\u5173\u7684\u5404\u79cd\u65b9\u6cd5
 */
(function(a,b,c){a.mix(b,{pwdStrength:function(){var g=function(h){if(h>=48&&h<=57){return 1}if(h>=65&&h<=90){return 2}if(h>=97&&h<=122){return 4}else{return 8}},e=function(h){modes=0;for(i=0;i<4;i++){if(h&1){modes++}h>>>=1}return modes},d=function(h){if(h.length<=4||/^\d*$/g.test(h)){return 0}Modes=0;for(i=0;i<h.length;i++){Modes|=g(h.charCodeAt(i))}return e(Modes)},f=function(m,l){var j=["pwd0","pwd1","pwd2"],n=m.siblings(".J_pwdSafe");if(l==null||l==""){var h=n.attr("data-class");n.removeClass(h);n.siblings("span").show()}else{var k=d(l);n.siblings("span").hide();switch(k){case 0:var h=n.attr("data-class");n.removeClass(h).addClass(j[0]);n.attr("data-class",j[0]);break;case 1:var h=n.attr("data-class");n.removeClass(h).addClass(j[1]);n.attr("data-class",j[1]);break;case 2:var h=n.attr("data-class");n.removeClass(h).addClass(j[2]);n.attr("data-class",j[2]);break}}return};c(".J_pwdStrength").bind("blur keyup",function(){f(c(this),this.value)})},form2str:function(j){if(!j){return}var d="";for(var h=0,g=j.elements,f=g.length;h<f;h++){var e=c(g[h]);if(e.attr("name")){d+="&"+e.attr("name")+"="+g[h].value}}d=d.replace("&","?");return d},form2json:function(j){if(!j){return}var d={};for(var h=0,g=j.elements,f=g.length;h<f;h++){var e=c(g[h]);if(e.attr("name")){d[e.attr("name")]=g[h].value}}return d}})})(ICAT,ICAT.util,jQuery);