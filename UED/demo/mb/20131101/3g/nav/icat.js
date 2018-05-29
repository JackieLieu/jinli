/*!
 * Copyright 2011~2013, ICAT JavaScript Library v1.1.4
 * https://github.com/valleykid/icat
 *
 * Copyright (c) 2013 valleykid
 * Licensed under the MIT license.
 *
 * @Author valleykiddy@gmail.com
 * @Time 2013-04-20 08:50:00
 */
(function(){var c=this,d=document,b={version:"1.1.4"};if(typeof exports!=="undefined"){if(typeof module!=="undefined"&&module.exports){exports=module.exports=b}exports.ICAT=b}else{c.ICAT=b}c.SHIM=c.SHIM||{};b.mix=SHIM.mix||function(j,h,l,g){if(!h||!j){return j}if(!g){g=true}var f,k,e;if(l&&(e=l.length)){for(f=0;f<e;f++){k=l[f];if(k in h){if(g||!(k in j)){j[k]=h[k]}}}}else{for(k in h){if(g||!(k in j)){j[k]=h[k]}}}return j};b.mix(Array.prototype,{contains:function(e){return this.indexOf(e)==-1?false:true},remove:function(f){var e=this;e.forEach(function(g,h){if(g===f){e.splice(h,1)}});return e},unique:function(){var e=this,g={},f=[];e.forEach(function(h){if(!g[h]){f.push(h);g[h]=true}});return f}});var a=location.href;["Function","String","Object","Array","Number","Boolean","Undefined","Null"].forEach(function(e){b["is"+e]=function(f){return Object.prototype.toString.call(f)==="[object "+e+"]"}});b.mix(b,{DebugMode:/debug/i.test(a),DemoMode:/localhost|demo\.|\/{2}\d+(\.\d+){3}|file:/i.test(a),TestMode:/3gtest\./i.test(a),IPMode:/\/{2}\d+(\.\d+){3}/.test(a),isEmptyObject:function(f){for(var e in f){return false}return true},isjQueryObject:function(e){return typeof $!=="undefined"&&e instanceof $},toArray:SHIM.toArray||function(e){return Array.prototype.slice.call(e)},foreach:function(l,e,h){var g,j=0,k=l.length,f=k===undefined||b.isString(l)||b.isFunction(l);if(h){if(f){for(g in l){if(e.apply(l[g],h)===false){break}}}else{for(;j<k;){if(e.apply(l[j++],h)===false){break}}}}else{if(f){for(g in l){if(e.call(l[g],g,l[g])===false){break}}}else{for(;j<k;){if(e.call(l[j],j,l[j++])===false){break}}}}},Class:function(){var g=arguments,e=g.length;if(!e){return}if(e===1){if(!b.isObject(g[0])){return}var f=g[0],i=function(){f.Create.apply(this,arguments)};b.foreach(f,function(m,l){if(m!="Create"){i.prototype[m]=l}});return i}else{if(!b.isString(g[0])&&!b.isObject(g[1])){return}var j=g[0],f=g[1],h=g[2]||c,i=function(){f.Create.apply(this,arguments)};b.foreach(f,function(m,l){if(m!="Create"){i.prototype[m]=l}});return h[j]=i}},widget:function(f,e){this.Class(f,e,b.widget)},util:function(e,f){if(b.isString(e)){b.util[e]=f}else{b.mix(b.util,e)}},namespace:function(){var f=arguments,e=f.length,m=null,h,g,k;for(h=0;h<e;++h){k=(""+f[h]).split(".");m=this;for(g=(c[k[0]]===m)?1:0;g<k.length;++g){m=m[k[g]]=m[k[g]]||{}}}return m},app:function(e,h){var f=b.isString(e),g=f?c[e]||{}:e;b.mix(g,b,["namespace"],true);b.mix(g,b.isFunction(h)?h():h);f&&(b.app[e]=c[e]=g);return g},log:function(h){if(d.all){c.console!==undefined&&console.log?console.log(h):alert(h)}else{try{__$abc_ICAT()}catch(g){var f=g.stack.split("\n")[2];f=f.replace(/.*[\(\s]|\).*/g,"").replace(/(.*):(\d+):\d+/g,"$1  line $2:\n");console.log(f,h)}}}})}).call(this);(function(a,b,c){a.util({matches:SHIM._matches||function(h,e){if(a.isjQueryObject(h)){return h.closest(e).length>0}else{if(!e||h==null||h==c.body.parentNode||h==c){return}var f=c.documentElement.webkitMatchesSelector;if(a.isString(e)){return f.call(h,e)}else{if(a.isArray(e)){for(var g=0,d=e.length;g<d;g++){if(f.call(h,e[g])){return g}}return false}}}},_sizzle:SHIM._sizzle||function(d,e){return e.querySelectorAll(d)},_getSelector:function(d){if(!d||!a.isString(d)){return d}if(/\:[\d\*]+/.test(d)){d=d.trim().replace(/\:([\d\*]+)\s*/g,"|$1|").replace(/\|$/g,"");return d.split("|")}else{return d}},_queryDom:function(g,j,f,h,e){var d,i=a.toArray(a.util._sizzle(g,e||c));if(!f){d=j=="*"?i:i[j]}else{if(j=="*"){d=[];i.forEach(function(k){h=="*"?d=d.concat(a.toArray(a.util._sizzle(f,k))):d.push(a.util._sizzle(f,k)[h||0])})}else{d=a.toArray(a.util._sizzle(f,i[j]));d=h=="*"?d:d[h||0]}}return d},queryOne:function(e,d){if(!e){return[c][0]}if(a.isString(e)){e=a.util._getSelector(e);if(a.isString(e)){return a.toArray(a.util._sizzle(e,d||c))[0]}else{if(e.length>4){return}return a.util._queryDom(e[0],e[1],e[2],e[3],d)}}else{return e}},queryAll:function(e,d){if(!e){return[c]}if(a.isString(e)){e=a.util._getSelector(e);if(a.isString(e)){return a.toArray(a.util._sizzle(e,d||c))}else{if(e.length>4){return}if(e[2]){e[3]=e[3]||"*"}return a.util._queryDom(e[0],e[1],e[2],e[3],d)}}else{return e}},waitObj:function(g,d){d=d||100;a.__cache_timers=a.__cache_timers||{};var f=0,e="icat_timer"+Math.floor(Math.random()*1000000+1);(function(){var h=arguments.callee;g(e,f);if(f<d&&!a.__cache_timers[e]){setTimeout(function(){f++;h()},1)}})()}})})(ICAT,this,document);(function(a,c,d){a.util({parentIfText:function(e){return"tagName" in e?e:e.parentNode},bubble:function(f,e){if(!f){return}while(f!==d.body){if(e&&a.isFunction(e)){if(e(f)==false){break}}f=f.parentNode}}});var b=a.namespace("Event");a.mix(b,{_bindEvent:function(g,f,e){g.events=g.events||{};g.types=g.types||[];g.events[f]=function(h){h=h||window.event;h.target=h.target||h.srcElement;h.preventDefault=h.preventDefault||function(){h.returnValue=false};h.stopPropagation=h.stopPropagation||function(){h.cancelBubble=true};e(h)};!g.types.contains(f)&&g.types.push(f);if(g.addEventListener){g.addEventListener(f.replace(/\..*/g,""),g.events[f],false)}},_unbindEvent:function(g,f){if(!g.events||!g.types.contains(f)){return}var e=g.events[f];f=f.replace(/\..*/g,"");if(g.removeEventListener){g.removeEventListener(f,e,false)}if(a.isEmptyObject(g.events)||!g.types.length){g.events=null;g.types=null}},_execute:function(f,g,e){a.util.bubble(g,function(j,h){h=a.util.matches(j,b.__event_selectors);var k=false;if(a.isNumber(h)){var i=b.items[b.__event_selectors[h]];f=f.replace(/click|tap/gi,"tap").replace(/moving|swiping/gi,"swiping");if(i.types.contains(f)){a.foreach(i.events,function(m,l){m=m.replace(/\.\w+/g,"").split("|");if(m[0]==f){if(f=="hover"){l[e].apply(j)}else{l.apply(j,e)}}if(m[1]==0){b.trigger(j,"click",false,true)}if(m[2]==1){k=true;return false}})}}if(k){return false}})},bind:function(g,f,e){if(!g){return}g=a.util.queryAll(g);if(a.isjQueryObject(g)){g.bind?g.bind(f,e):arguments.callee(a.util.queryAll(g.selector),f,e)}else{g.length===undefined?b._bindEvent(g,f,e):a.foreach(g,function(j,h){b._bindEvent(h,f,e)})}},unbind:function(f,e){if(!f){return}f=a.util.queryAll(f);if(a.isjQueryObject(f)){f.unbind?f.unbind(e):arguments.callee(a.util.queryAll(f.selector),e)}else{f.length===undefined?b._unbindEvent(f,e):a.foreach(f,function(h,g){b._unbindEvent(g,e)})}},trigger:function(h,g,f,e){if(a.isObject(h)&&!a.isjQueryObject(h)){h[g]&&h[g].apply(h,f);return}if(a.isjQueryObject(h)){if(h.trigger){h.trigger(g);return}else{h=h.get(0)}}if(/\:dg$/i.test(g)){g=g.replace(/\:dg$/i,"");h=a.util.queryOne(h);b._execute(g,h)}else{if(!d.createEvent){return}var i=d.createEvent("Event");i.initEvent(g,f,e);h.dispatchEvent(i)}},ready:function(){var f=[],e=function(){if(!arguments.callee.done){arguments.callee.done=true;for(var g=0;g<f.length;g++){f[g]()}}};if(d.readyState){(function(){if(d.readyState!=="loading"){e()}else{setTimeout(arguments.callee,10)}})()}return function(g){if(a.isFunction(g)){f[f.length]=g}return g}}(),__event_selectors:[],delegate:function(h,g){if(!h||a.isEmptyObject(h)||h==[]){return}var j=b.__event_selectors;var i=b.items=b.items||{};if(a.isObject(h)){if(!j.contains(h.selector)&&!g){j.push(h.selector)}h.type=h.type.replace(/click|tap/gi,"tap").replace(/moving|swiping/gi,"swiping");var f=i[h.selector]=i[h.selector]||{},e=h.type+"|"+(h.preventDefault?1:0)+"|"+(h.stopPropagation?1:0);f.events=f.events||{};f.types=f.types||[];f.events[e]=h.callback;h.type=h.type.replace(/\..*/g,"");!f.types.contains(h.type)&&f.types.push(h.type)}else{if(a.isArray(h)){while(h.length){arguments.callee(h[0]);h.shift()}}}},undelegate:function(f){if(!f||a.isEmptyObject(f)||f==[]){return}var h=b.__event_selectors;var g=b.items=b.items||{};if(a.isObject(f)){if(!h.contains(f.selector)||!g[f.selector]){return}h.remove(f.selector);var e=g[f.selector];f.type=f.type.replace(/click|tap/gi,"tap").replace(/moving|swiping/gi,"swiping");if(e.types.contains(f.type)){e.types.remove(f.type);a.foreach(e.events,function(j,i){if(j.indexOf(f.type)!=-1){delete e.events[j]}})}if(!e.types.length&&a.isEmptyObject(e.events)){delete g[f.selector]}}else{if(a.isArray(f)){while(f.length){arguments.callee(f[0]);f.shift()}}}},on:function(h,g,f,e,i){if(a.isString(h)&&/\:dg$/i.test(g)){g=g.replace(/\:dg$/i,"");b.delegate({selector:h,type:g,callback:f,preventDefault:e,stopPropagation:i})}else{b.bind(h,g,f)}},off:function(f,e){if(a.isString(f)&&/\:dg$/i.test(e)){e=e.replace(/\:dg$/i,"");b.undelegate({selector:f,type:e})}else{b.unbind(f,e)}}});b.ready(function(){var m={},h,n="ontouchstart" in c;var o=n?"touchstart":"mousedown",e=n?"touchmove":"mousemove",s=n?"touchend":"mouseup",i=n?"touchcancel":"mouseout";var k=a.pageBody=a.util.queryOne("*[data-pagerole=body]"),r=a.Event,g,q,l=750,f,j=function(){if(f){clearTimeout(f)}f=null},p=function(u,t,w,v){var y=Math.abs(u-t),x=Math.abs(w-v);return y>=x?(u-t>0?"Left":"Right"):(w-v>0?"Up":"Down")};if(!k){return}r.on(k,o,function(t){t.stopPropagation();if(t.button&&t.button===2){return}var u=n?t.touches[0]:t;g=Date.now();q=g-(m.last||g);m.el=a.util.parentIfText(t.target);h&&clearTimeout(h);m.x1=u.pageX;m.y1=u.pageY;m.isScrolling=undefined;if(q>0&&q<=250){m.isDoubleTap=true}m.last=g;r._execute("hover",m.el,0);f=setTimeout(function(){f=null;if(m.last){r._execute("longTap",m.el);m={}}},l)});r.on(k,e,function(t){t.stopPropagation();if(t.button&&t.button===2){return}j();var w=n?t.touches[0]:t;m.x2=w.pageX;m.y2=w.pageY;var v=m.x2-m.x1,u=m.y2-m.y1;if(typeof m.isScrolling=="undefined"){m.isScrolling=!!(m.isScrolling||Math.abs(v)<Math.abs(u))}if(!m.isScrolling){r._execute("swiping",m.el,[m.x1,m.x2,m.y1,m.y2])}});r.on(k,s,function(t){t.stopPropagation();if(t.button&&t.button===2){return}r._execute("hover",m.el,1);j();if(!m.isScrolling){if(m.isDoubleTap){r._execute("doubleTap",m.el);m={}}else{if("last" in m){if((m.x2&&Math.abs(m.x1-m.x2)<20)||(m.y2&&Math.abs(m.y1-m.y2)<20)){r._execute("tap",m.el)}h=setTimeout(function(){h=null;r._execute("singleTap",m.el);m={}},250)}else{if((m.x2&&Math.abs(m.x1-m.x2)>30)||(m.y2&&Math.abs(m.y1-m.y2)>30)){var u="swipe"+p(m.x1,m.x2,m.y1,m.y2);r._execute(u,m.el);m={}}}}}else{m={}}});r.on(k,i,function(t){t.stopPropagation();if(h){clearTimeout(h)}if(f){clearTimeout(f)}f=h=null;m={}});r.on(k,"click",function(t){var u=a.util.parentIfText(t.target);if(!u||u==d.body){return}a.util.bubble(u,function(w,v){v=a.util.matches(w,r.__event_selectors);if(a.isNumber(v)){t.preventDefault()}})})})})(ICAT,this,document);(function(a,c,e){a.util({lazyLoad:function(i,h){if(!i){return}i=a.util.queryOne(i);var j=a.toArray(a.util.queryAll('img[src$="blank.gif"]',i));h=h||500;setTimeout(function(){j.forEach(function(m){var l=m.getAttribute("data-src");a.__cache_images=a.__cache_images||{};if(!l){return}if(!a.__cache_images[l]){var k=new Image();k.src=l;k.onload=function(){m.src=l;a.__cache_images[l]=true;k=null}}else{m.src=l}m.removeAttribute("data-src")})},h)},_fnTmpl:function(i){i=a.isString(i)?i.trim():i;var h=a.__cache_tmpls=a.__cache_tmpls||{};var k=a.__cache_funs=a.__cache_funs||{};var m,l;if(a.isEmptyObject(h)){a.foreach(a.app,function(o,n){if(this.template){a.foreach(this.template,function(q,p){h[q]=p.replace(/[\r\t\n]/g,"")})}})}if(k[i]){m=k[i]}else{if(h[i]){m=a.util._tmpl(i,undefined,h[i]);k[i]=m}else{if(a.isjQueryObject(i)){m=a.util._tmpl(i,undefined,i.html());k[i]=m}else{if(a.isString(i)||a.isObject(i)){var j=a.isObject(i)?i:/[\.#]/.test(i)?a.util.queryOne(i):e.getElementById(i);m=a.util._tmpl(i,undefined,j?j.innerHTML:"");k[i]=m;h[i]=l}}}}return m},_tmpl:function(j,m,i){if(!j){return}var l=a.__cache_funs=a.__cache_funs||{},k=function(){return""},h;if(l[j]){return m?l[j](m):l[j]}else{if(!i){return k}i=i.replace(/[\r\t\n]/g,"");h="var __p_fun = [];with(jsonData){__p_fun.push('"+i.replace(/<%=(.*?)%>/g,"',$1,'").replace(/<%(.*?)%>/g,"');$1__p_fun.push('")+"');};return __p_fun.join('');";l[j]=new Function("jsonData",h);return m?l[j](m):l[j]}},_joinHook:function(h,i){if(!h||!i){return}h=a.isArray(h)?h:[h];h.forEach(function(j){if(!j){return}if(/\w*~.*/.test(j)){j=j.split("~");i.setAttribute(j[0],j[1])}else{if(!/[#\.]/.test(j)){return}j=j.replace(/\s+/g,"").replace(/#(\w+)/g,",$1,").replace(/,+/g,",").replace(/^,|,$/g,"").split(",");var k=(i.className||"").trim().split(/\s+/);j.forEach(function(l){if(l.indexOf(".")==-1){i.id=l}else{i.className=l.split(".").concat(k).unique().join(" ").trim()}})}})},_getWalker:function(h){if(!/^&[<>]\d+/.test(h)){return}return h.indexOf(">")!=-1?{c:h.replace(/^&>(\d+\:?[\d\w\.\*#]*).*/,"$1").split(":")}:{p:h.replace(/^&<(\d+).*/,"$1")}},_walker:function(l,m){if(l.c){var t=parseInt(l.c[0]),s=l.c[1]||"*",h=/[#\.\*]/.test(s),k=null;s=h?s:parseInt(s);m=m[0];if(!m){return}if(t==0){return h?a.toArray(m.children).filter(function(i){return a.util.matches(i,s)}):m.children[s]}else{var j=e.createTreeWalker(m,NodeFilter.SHOW_ELEMENT,k,false),r,q;for(var n=0;n<t;n++){if(n==t-1){r=j.nextNode()}else{j.nextNode()}}if(h){q=[];while(r){a.foreach(r.children,function(u,o){if(a.util.matches(o,s)){q.push(o)}});r=j.nextSibling()}}else{q=r.children}return h?q:q[s]}}else{var p=parseInt(l.p)+1;return function(){var u=m[1];if(!u){return}for(var o=0;o<p;o++){u=u.parentNode;if(u===e.body){break}}return u}()}},render:function(l,m,i){if(a.isString(l)){l=l.trim()}if(m){var h=m.ICwrap,k=a.util._fnTmpl(l)(m),p=e.createElement("div"),j;p.innerHTML=k;if(m.IChooks){a.foreach(m.IChooks,function(q,r){q=a.util._getWalker(q);if(!q){a.util._joinHook(r,h)}else{var o=a.util._walker(q,[p,h]);if(!o){return}o.length===undefined?a.util._joinHook(r,o):o.forEach(function(s){a.util._joinHook(r,s)})}})}k=p.innerHTML}else{return a.util._fnTmpl(l)}if(!h){return k}if(i){var n=h.childNodes;while(n.length>0){h.removeChild(n[0])}}j=p.childNodes;while(j.length>0){h.appendChild(j[0])}a.util.lazyLoad(h);p=null;if(m.callback){m.callback(h)}},storage:function(){if(!arguments.length||!window.localStorage||!window.sessionStorage){return}var h=window.localStorage,i=window.sessionStorage;if(arguments.length==1){var m=arguments[0];return a.isString(m)?(h.getItem(m)||i.getItem(m)):""}else{var m=arguments[0],k=arguments[1],l=arguments[2];if(a.isString(m)){var j=l?i:h;j.removeItem(m);j.setItem(m,a.isObject(k)?JSON.stringify(k):k)}}},clearStorage:function(j){if(!j||!window.localStorage||!window.sessionStorage){return}var h=window.localStorage,i=window.sessionStorage;if(j==h||j==i){j.clear()}else{if(h[j]){h.removeItem(j)}if(i[j]){i.removeItem(j)}}},cookie:function(){if(!arguments.length){return}if(arguments.length==1){var n=e.cookie;if(n.length<=0){return}var i=arguments[0],j=n.indexOf(i+"=");if(j!=-1){j=j+i.length+1;cEnd=n.indexOf(";",j);if(cEnd==-1){cEnd=n.length}return unescape(n.substring(j,cEnd))}}else{var i=arguments[0],m=arguments[1],l=arguments[2]||60,k=new Date(),h="";k.setTime(k.getTime()+(l*1000));h="; expires="+k.toGMTString();e.cookie=i+"="+escape(m)+h+"; path=/"}},clearCookie:function(h){a.View.cookie(h,"",-1)},makeWrap:function(u,q){if(!u){return}if(a.isString(u)){var h=e.createElement("div"),j;if(u.indexOf("~")>=0){u=u.split("~");q=a.util.queryOne(u[0]);j=u[1]}else{q=q||e.body;j=u}var n=j.trim().split("*"),r=n[0],l=n[1]||1,m="",t=r.replace(/(\w+)([\.\#\w\-\d]+)/,"<$1$2></$1>").replace(/\.([\.\w\-\d]+)/g,' class="$1"').replace(/\./g," ").replace(/\#([\w\-\d]+)/g,' id="$1"');for(var k=0;k<l;k++){m+=t}h.innerHTML=m;itemNodes=h.childNodes;while(itemNodes.length>0){q.appendChild(itemNodes[0])}}else{var p=arguments.callee;u.forEach(function(i){p(i)})}},fullUrl:function(j,h){var j=j||"",i=a.isString(h)?h:"",k=a.isBoolean(h)?h:false;j=j.replace(/^\//g,"");if(a.DemoMode&&j!==""){j=j.indexOf("?")<0?(j+".php"):j.replace(/(\?)/g,".php$1")}if(!k&&i){j=j+(j.indexOf("?")<0?"?":"&")+i}return a.PathConfig.pageRef+j}});function g(j,i){var h=this;h.tempId=j;h._render=function(l,k){a.util.waitObj(function(m){var n=a.util.queryOne(l.ICwrap);if(!n){a.__cache_timers[m]=false;return}a.__cache_timers[m]=true;l.ICwrap=n;a.View.render(h.tempId,l);var o=/form/i.test(n.tagName)?n:a.util.queryOne("form",n);if(o){h.getData=function(q){q=q||"string";var r=/json/i.test(q),p=r?{}:"";a.toArray(o.elements).forEach(function(t){var s=t.getAttribute("name"),u=t.value;if(s){r?p[s]=u:p+="&"+s+"="+u}});return r?p:p.replace(/^&/,"")}}})};if(i){h._render(i)}}g.prototype={addItem:function(h){this._render(h)},setData:function(h){this._render(h,true)},extend:function(h){a.mix(this,h)}};a.View=function(h,i){if(!h){return}if(!a.View[h]){a.View[h]=new g(h)}if(i){a.View[h].setData(i)}return a.View[h]};a.mix(a.View,{render:a.util.render,destroy:function(h){if(!h){return}h=a.isString(h)?[h]:h;h.forEach(function(i){delete a.View[i]})}});function f(i,h){this.module=i;this.initData=h}f.prototype={getInitData:function(h){return this.initData[h||""]},fetchData:function(){},storeData:function(){},extend:function(h){a.mix(this,h)}};a.Model=function(h,i){if(!h){return}if(!a.Model[h]){a.Model[h]=new f(h,i)}else{a.Model[h].initData=i||a.Model[h].initData}return a.Model[h]};a.mix(a.Model,{storage:a.util.storage,clearStorage:a.util.clearStorage,cookie:a.util.cookie,clearCookie:a.util.clearCookie,destroy:function(h){if(!h){return}h=a.isString(h)?[h]:h;h.forEach(function(i){delete a.Model[i]})}});var b=a.Event;function d(h){this.selectors=[];this.module=h}d.prototype={subscribe:function(i){var h=this;i=a.isArray(i)?i:[i];i.forEach(function(j){b.delegate(j,true);if(!h.selectors.contains(j.selector)){h.selectors.push(j.selector)}})},unsubscribe:function(i){var h=this;i=a.isArray(i)?i:[i];i.forEach(function(j){b.undelegate(j);if(h.selectors.contains(j.selector)){h.selectors.remove(j.selector)}})},addEvents:function(h){this.subscribe(h)},removeEvents:function(h){this.unsubscribe(h)},extend:function(h){a.mix(this,h)}};a.Controller=function(i,h){if(!i){return}if(!a.Controller[i]){a.Controller[i]=new d(i)}if(a.isFunction(h)){h(a.Controller[i])}else{a.Controller[i].subscribe(h)}return a.Controller[i]};a.mix(a.Controller,{addCurrent:function(h,i){if(!h){return}h=a.isString(h)?[h]:h;h.forEach(function(j){if(a.Controller[j]){b.__event_selectors=b.__event_selectors.concat(a.Controller[j].selectors)}});if(i&&a.isFunction(i)){i()}},destroy:function(h){if(!h){return}h=a.isString(h)?[h]:h;h.forEach(function(i){if(a.Controller[i]){a.Controller[i].selectors.forEach(function(j){delete b.items[j];b.__event_selectors.remove(j)})}delete a.Controller[i]})}})})(ICAT,this,document);(function(a,b,f){var d={},c={},e;a.util({getCurrentJS:function(){var g=f.getElementsByTagName("script");return g[g.length-1]},_dealURL:function(g,j){if(!g.length){return}if(g.length===1){j=true}var i,k=[],h=function(n){var l=n,m=a.PathConfig._isConcat&&!j?"_":"";if(/^\.{1,}\//.test(l)){l=/^\.\//.test(l)?l.replace(/^\.\//g,a.PathConfig[m+"appRef"]):l.replace(/^\.{2}\//g,a.PathConfig[m+"appRef"].replace(/\w+\/$/g,""))}else{if(/^\//.test(l)){l=l.replace(/^\//g,a.PathConfig.pageRef)}else{l=a.PathConfig[m+"sysRef"]+l}}return l};if(a.PathConfig._isConcat&&!j){i="";a.DebugMode?g.forEach(function(l){l=l.replace(/\?.*/,"");if(/^(http|ftp|https):\/\//i.test(l)){k.push(l.indexOf("!")>=0?l.replace(/\!/g,""):l.replace(/(\.source)?(\.(js|css))/g,".source$2"))}else{if(/^\//.test(l)){l=h(l);k.push(l.indexOf("!")>=0?l.replace(/\!/g,""):l.replace(/(\.source)?(\.(js|css))/g,".source$2"))}else{l=h(l);i+=(l.indexOf("!")>=0?l.replace(/\!/g,""):l.replace(/(\.source)?(\.(js|css))/g,".source$2"))+","}}}):g.forEach(function(l){l=l.replace(/\?.*/,"");l=l.replace(/\!/g,"");if(/^(http|ftp|https):\/\//i.test(l)){k.push(l)}else{if(/^\//.test(l)){k.push(h(l))}else{i+=h(l)+","}}});i=a.PathConfig._webRoot+i.replace(/,$/g,"");return[i].concat(k)}else{i=[];a.DebugMode?g.forEach(function(l){l=l.replace(/\?.*/,"");if(/^(http|ftp|https):\/\//i.test(l)){i.push(l.indexOf("!")>=0?l.replace(/\!/g,""):l.replace(/(\.source)?(\.(js|css))/g,".source$2"))}else{l=h(l);i.push(l.indexOf("!")>=0?l.replace(/\!/g,""):l.replace(/(\.source)?(\.(js|css))/g,".source$2"))}}):g.forEach(function(l){l=l.replace(/\?.*/,"");l=l.replace(/\!/g,"");if(/^(http|ftp|https):\/\//i.test(l)){i.push(l)}else{i.push(h(l))}});return i}},_blockImport:function(l){var j=l,k=j.indexOf("#")>0?j.replace(/(#.*)/,a.PathConfig.timestamp+"$1"):(j+a.PathConfig.timestamp);if(d[k]){return}var m=j.replace(/.*\./g,""),i=m=="css",h=i?"link":"script",g=i?' type="text/css" rel="stylesheet"':' type="text/javascript"',n=(i?"href":"src")+'="'+k+'"';f.write("<"+h+g+n+(i?"/>":"></"+h+">"));d[j]=true},_unblockImport:function(i){var g=i.file.indexOf("#")>0?i.file.replace(/(#.*)/,a.PathConfig.timestamp+"$1"):(i.file+a.PathConfig.timestamp);if(d[i.file]){if(i.callback&&a.isFunction(i.callback)){i.callback(i.context||a)}if(i.modName){c[i.modName]=true}return}var j,h=i.file.replace(/.*\./g,"");if(h==="css"){j=f.createElement("link");j.setAttribute("type","text/css");j.setAttribute("rel","stylesheet");j.setAttribute("href",g)}else{if(h==="js"){j=f.createElement("script");j.setAttribute("type","text/javascript");j.setAttribute("src",g);j.setAttribute("async",true)}}if(!j){return}a.util.waitObj(function(l){var m=f.body||f.getElementsByTagName("body")[0];if(!m){a.__cache_timers[l]=false;return}a.__cache_timers[l]=true;if(h==="js"){e=SHIM._load||function(q,n,p,o,r,k){r.onload=function(){if(p.callback&&k.isFunction(p.callback)){p.callback(p.context||k)}if(p.modName){q[p.modName]=true}n[p.file]=true};o.appendChild(r)};e(c,d,i,m,j,a)}if(h==="css"){setTimeout(function(){if(i.callback&&_icat.isFunction(i.callback)){i.callback(i.context||_icat)}if(i.modName){c[i.modName]=true}},5);d[i.file]=true;m.appendChild(j)}})}});a.PathConfig=function(j){var k=a.util.getCurrentJS(),g=k.src,o=k.getAttribute("refSlipt")||"";a.PathConfig._isConcat=g.indexOf("??")>=0;if(o&&k.baseURI.indexOf(o)==-1){o=false}if(!a.PathConfig.appRef){var i=(a.DemoMode&&!o)?k.baseURI+"?":k.baseURI,m=a.DemoMode?(o?"("+o+"/).*":"(/)([\\w\\.]+)?\\?.*"):"(//[\\w\\.]+/).*",l=new RegExp(m,"g");a.PathConfig.pageRef=a.PathConfig.pageRef||i.replace(l,"$1");a.PathConfig.weinreRef=a.IPMode?i.replace(/(\d+(\.\d+){3}).*/g,"$1:8080/"):"";if(a.PathConfig._isConcat){var h=g.replace(/(\?{2}|\.js(?=\?))/g,"$1|").split("|"),n=h[0].replace(/\?+/g,"");a.PathConfig._webRoot=h[0];a.PathConfig.timestamp=h[2]||"";h[1].split(",").forEach(function(p){if(/\/sys\//i.test(p)){a.PathConfig._sysRef=p.replace(/(\/sys\/).*/ig,"$1")}if(/\/apps\//i.test(p)){a.PathConfig._appRef=p.replace(/(\/)\w+(\.\w+)?\.js/g,"$1")}});a.PathConfig.sysRef=(n+a.PathConfig._sysRef).replace(/([^:])\/{2,}/g,"$1/");a.PathConfig.appRef=(n+a.PathConfig._appRef).replace(/([^:])\/{2,}/g,"$1/")}else{if(j===true){a.PathConfig.sysRef=/\/sys\//i.test(g)?g.replace(/(\/sys\/).*/ig,"$1"):g.replace(/(\/)\w+(\.\w+)?\.js(.*)?/g,"$1");a.PathConfig.timestamp=g.replace(/.*\.js(\?)?/g,"$1")}else{a.PathConfig.appRef=g.replace(/(\/)\w+(\.\w+)?\.js(.*)?/g,"$1");if(!a.PathConfig.timestamp){a.PathConfig.timestamp=g.replace(/.*\.js(\?)?/g,"$1")}}}}if(a.isObject(j)){a.mix(a.PathConfig,j)}};a.PathConfig(true);a.ModsConfig=function(g){if(a.isArray(g)){a.foreach(g,function(i,h){a.ModsConfig[h.modName]=(a.ModsConfig[h.modName]||[]).concat(h.paths)})}else{if(g.modName&&g.paths){a.ModsConfig[g.modName]=(a.ModsConfig[g.modName]||[]).concat(g.paths)}else{a.foreach(g,function(i,h){a.ModsConfig[i]=(a.ModsConfig[i]||[]).concat(h)})}}};a.mix(a,{inc:function(g){if(!g){return}g=a.isString(g)?[g]:g;a.foreach(a.util._dealURL(g),function(j,h){if(!h){return}a.util._blockImport(h)})},include:function(){if(!arguments.length){return}if(arguments.length==1){var g=arguments[0];if(a.isString(g)){g={files:g}}if(!a.isObject(g)||!g.files){return}g.files=a.isString(g.files)?a.util._dealURL([g.files]):a.util._dealURL(g.files,g.isSingle);(function(){if(!g.files.length){return}var i=g.files.shift(),h=arguments.callee;if(g.files.length){if(g.isDepend){a.util._unblockImport({file:i,callback:function(){h(g.files)},context:g.context})}else{a.util._unblockImport({file:i,context:g.context});h(g.files)}}else{a.util._unblockImport({file:i,callback:g.callback,context:g.context})}})()}else{arguments.callee({files:arguments[0],callback:arguments[1],isDepend:arguments[2],isSingle:arguments[3],context:arguments[4]})}},require:function(){if(!arguments.length){return}if(arguments.length==1){var g=arguments[0];if(!a.isObject(g)||!(g.files=g.files||a.ModsConfig[g.modName])){return}g.files=a.isString(g.files)?a.util._dealURL([g.files]):a.util._dealURL(g.files,g.isSingle);if(c[g.modName]){if(g.callback){g.callback(g.context)}}else{(function(){if(!g.files.length){return}var i=g.files.shift(),h=arguments.callee;if(g.files.length){a.util._unblockImport({file:i,callback:function(){h(g.files)},context:g.context,modName:g.modName})}else{a.util._unblockImport({file:i,callback:g.callback,context:g.context,modName:g.modName})}})()}}else{arguments.callee({modName:arguments[0],files:arguments[1],callback:arguments[2],isSingle:arguments[3],context:arguments[4]})}},use:function(h){if(!arguments.length){return}var g=0,j;if(arguments.length==1){var h=arguments[0];if(!a.isObject(h)){return}a.util.waitObj(function(i,l){if(!c[h.modName]){a.__cache_timers[i]=false;if(l==50&&a.ModsConfig[h.modName]){a.require({modName:h.modName,files:a.ModsConfig[h.modName],context:h.context})}return}a.__cache_timers[i]=true;if(h.callback){h.callback(h.context)}})}else{arguments.callee({modName:arguments[0],callback:arguments[1],delay:arguments[2],context:arguments[3]})}}});a.ModsConfig([{modName:"zepto_core",paths:["lib/zepto/src/zepto.js","lib/zepto/src/event.js","lib/zepto/src/ajax.js","lib/zepto/src/fx.js"]},{modName:"app_mvcBase",paths:["./mvc/template.js","./mvc/initdata.js","./mvc/view.js","./mvc/model.js","./mvc/controller.js"]}]);a.weinreStart=function(){if(!a.PathConfig.weinreRef){return}var g=a.PathConfig.weinreRef+"target/target-script-min.js!"+(location.hash||"");a.include(g)};if(a.IPMode){a.weinreStart()}})(ICAT,this,document);
